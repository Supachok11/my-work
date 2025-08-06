<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\LeaveRequestNotification;
use App\Mail\LeaveRequestStatusNotification;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentYear = now()->year;

        // คำนวณสถิติการลางานในปีปัจจุบัน - คำนวณจำนวนวันจริง
        $totalDays = 0;
        $personalLeaveDays = 0;
        $sickLeaveDays = 0;

        $leaveRequests = LeaveRequest::where('user_id', $user->id)
            ->whereYear('leave_date', $currentYear)
            ->where('status', 'อนุมัติ')
            ->get();

        foreach ($leaveRequests as $request) {
            if ($request->is_range && $request->range_start_date && $request->range_end_date) {
                // คำนวณจำนวนวันสำหรับการลาหลายวัน
                $days = \Carbon\Carbon::parse($request->range_start_date)->diffInDays(\Carbon\Carbon::parse($request->range_end_date)) + 1;
            } else {
                // การลาวันเดียว
                $days = 1;
            }

            $totalDays += $days;

            if ($request->leave_type === 'ลากิจ') {
                $personalLeaveDays += $days;
            } elseif ($request->leave_type === 'ลาป่วย') {
                $sickLeaveDays += $days;
            }
        }

        $leaveStats = [
            'total_days' => $totalDays,
            'personal_leave' => $personalLeaveDays,
            'sick_leave' => $sickLeaveDays,
        ];

        $leaveRequests = LeaveRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard', compact('leaveStats', 'leaveRequests'));
    }

    public function store(Request $request)
    {
        // ตรวจสอบประเภทการเลือกวันที่
        $dateSelectionType = $request->input('date_selection_type', 'single');
        
        $validationRules = [
            'leave_type' => 'required|in:ลากิจ,ลาป่วย',
            'duration_type' => 'required|in:ทั้งวัน,ชั่วโมง',
            'start_time' => 'required_if:duration_type,ชั่วโมง|nullable|date_format:H:i',
            'end_time' => 'required_if:duration_type,ชั่วโมง|nullable|date_format:H:i|after:start_time',
            'additional_info' => 'required|string|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
        ];

        // เพิ่มกฎการตรวจสอบตามประเภทการเลือกวันที่
        if ($dateSelectionType === 'range') {
            $validationRules['start_date'] = 'required|date|after_or_equal:today';
            $validationRules['end_date'] = 'required|date|after_or_equal:start_date';
        } else {
            $validationRules['leave_date'] = 'required|date|after_or_equal:today';
        }

        $request->validate($validationRules);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('leave_attachments', 'public');
        }

        // สร้างคำขอลางานตามประเภทการเลือกวันที่
        if ($dateSelectionType === 'range') {
            // ลาหลายวัน - สร้างคำขอเดียวที่แทนทั้งช่วงวันที่
            $startDate = new \DateTime($request->start_date);
            $endDate = new \DateTime($request->end_date);
            $totalDays = $endDate->diff($startDate)->days + 1;
            
            // สร้างคำขอหลักสำหรับการลาหลายวัน
            LeaveRequest::create([
                'user_id' => Auth::id(),
                'leave_type' => $request->leave_type,
                'duration_type' => $request->duration_type,
                'leave_date' => $request->start_date, // ใช้วันแรกเป็น leave_date หลัก
                'start_time' => $request->duration_type === 'ชั่วโมง' ? $request->start_time : null,
                'end_time' => $request->duration_type === 'ชั่วโมง' ? $request->end_time : null,
                'additional_info' => $request->additional_info,
                'attachment_path' => $attachmentPath,
                'status' => 'รออนุมัติ',
                'is_range' => true,
                'range_start_date' => $request->start_date,
                'range_end_date' => $request->end_date,
            ]);
            
            $successMessage = "ส่งคำขอลางานเรียบร้อยแล้ว (รวม {$totalDays} วัน) และแจ้งเตือนหัวหน้าแล้ว";
        } else {
            // ลาวันเดียว
            LeaveRequest::create([
                'user_id' => Auth::id(),
                'leave_type' => $request->leave_type,
                'duration_type' => $request->duration_type,
                'leave_date' => $request->leave_date,
                'start_time' => $request->duration_type === 'ชั่วโมง' ? $request->start_time : null,
                'end_time' => $request->duration_type === 'ชั่วโมง' ? $request->end_time : null,
                'additional_info' => $request->additional_info,
                'attachment_path' => $attachmentPath,
                'status' => 'รออนุมัติ',
                'is_range' => false,
            ]);
            
            $successMessage = 'ส่งคำขอลางานเรียบร้อยแล้ว และแจ้งเตือนหัวหน้าแล้ว';
        }

        // ส่งอีเมลแจ้งเตือน (ใช้คำขอล่าสุด)
        $leaveRequest = LeaveRequest::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->first();

        Mail::to('outhailnw@gmail.com')->send(new LeaveRequestNotification($leaveRequest));

        return redirect()->route('dashboard')->with('success', $successMessage);
    }

    public function history()
    {
        $user = Auth::user();
        $currentYear = now()->year;

        // เพิ่มการคำนวณสถิติการลางานเหมือนในหน้า dashboard
        $totalDays = 0;
        $personalLeaveDays = 0;
        $sickLeaveDays = 0;

        $approvedRequests = LeaveRequest::where('user_id', $user->id)
            ->whereYear('leave_date', $currentYear)
            ->where('status', 'อนุมัติ')
            ->get();

        foreach ($approvedRequests as $request) {
            if ($request->is_range && $request->range_start_date && $request->range_end_date) {
                // คำนวณจำนวนวันสำหรับการลาหลายวัน
                $days = \Carbon\Carbon::parse($request->range_start_date)->diffInDays(\Carbon\Carbon::parse($request->range_end_date)) + 1;
            } else {
                // การลาวันเดียว
                $days = 1;
            }

            $totalDays += $days;

            if ($request->leave_type === 'ลากิจ') {
                $personalLeaveDays += $days;
            } elseif ($request->leave_type === 'ลาป่วย') {
                $sickLeaveDays += $days;
            }
        }

        $leaveStats = [
            'total_days' => $totalDays,
            'personal_leave' => $personalLeaveDays,
            'sick_leave' => $sickLeaveDays,
        ];

        $leaveRequests = LeaveRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('leave-history', compact('leaveRequests', 'leaveStats'));
    }

    public function approve($id, Request $request)
    {
        // ตรวจสอบ token เพื่อความปลอดภัย
        $token = $request->query('token');
        if (!$this->verifyToken($id, $token)) {
            return view('leave-action-result', [
                'success' => false,
                'message' => 'ลิงก์ไม่ถูกต้องหรือหมดอายุ',
                'action' => 'อนุมัติ'
            ]);
        }

        $leaveRequest = LeaveRequest::find($id);

        if (!$leaveRequest) {
            return view('leave-action-result', [
                'success' => false,
                'message' => 'ไม่พบคำขอลางานนี้',
                'action' => 'อนุมัติ'
            ]);
        }

        if ($leaveRequest->status !== 'รออนุมัติ') {
            return view('leave-action-result', [
                'success' => false,
                'message' => 'คำขอลางานนี้ได้รับการพิจารณาแล้ว (สถานะปัจจุบัน: ' . $leaveRequest->status . ')',
                'action' => 'อนุมัติ'
            ]);
        }

        // อนุมัติคำขอลางาน
        $leaveRequest->update([
            'status' => 'อนุมัติ',
            'approved_at' => now()
        ]);

        return view('leave-action-result', [
            'success' => true,
            'message' => 'อนุมัติคำขอลางานเรียบร้อยแล้ว',
            'action' => 'อนุมัติ',
            'leaveRequest' => $leaveRequest
        ]);
    }

    public function reject($id, Request $request)
    {
        // ตรวจสอบ token เพื่อความปลอดภัย
        $token = $request->query('token');
        if (!$this->verifyToken($id, $token)) {
            return view('leave-action-result', [
                'success' => false,
                'message' => 'ลิงก์ไม่ถูกต้องหรือหมดอายุ',
                'action' => 'ไม่อนุมัติ'
            ]);
        }

        $leaveRequest = LeaveRequest::find($id);

        if (!$leaveRequest) {
            return view('leave-action-result', [
                'success' => false,
                'message' => 'ไม่พบคำขอลางานนี้',
                'action' => 'ไม่อนุมัติ'
            ]);
        }

        if ($leaveRequest->status !== 'รออนุมัติ') {
            return view('leave-action-result', [
                'success' => false,
                'message' => 'คำขอลางานนี้ได้รับการพิจารณาแล้ว (สถานะปัจจุบัน: ' . $leaveRequest->status . ')',
                'action' => 'ไม่อนุมัติ'
            ]);
        }

        // ไม่อนุมัติคำขอลางาน
        $leaveRequest->update([
            'status' => 'ไม่อนุมัติ',
            'rejected_at' => now()
        ]);

        return view('leave-action-result', [
            'success' => true,
            'message' => 'ไม่อนุมัติคำขอลางานเรียบร้อยแล้ว',
            'action' => 'ไม่อนุมัติ',
            'leaveRequest' => $leaveRequest
        ]);
    }

    private function verifyToken($id, $token)
    {
        if (!$token) {
            return false;
        }

        $decoded = base64_decode($token);
        $parts = explode('|', $decoded);

        if (count($parts) !== 2) {
            return false;
        }

        $tokenId = $parts[0];
        $timestamp = $parts[1];

        // ตรวจสอบ ID ตรงกัน และลิงก์ยังไม่หมดอายุ (7 วัน)
        return $tokenId == $id && (time() - $timestamp) <= (7 * 24 * 60 * 60);
    }
}

