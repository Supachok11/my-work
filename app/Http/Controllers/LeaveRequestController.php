<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentYear = now()->year;
        
        // คำนวณสถิติการลางานในปีปัจจุบัน - ใช้ LeaveRequest model โดยตรง
        $leaveStats = [
            'total_days' => LeaveRequest::where('user_id', $user->id)
                ->whereYear('leave_date', $currentYear)
                ->where('status', 'อนุมัติ')
                ->count(),
            'personal_leave' => LeaveRequest::where('user_id', $user->id)
                ->whereYear('leave_date', $currentYear)
                ->where('status', 'อนุมัติ')
                ->where('leave_type', 'ลากิจ')
                ->count(),
            'sick_leave' => LeaveRequest::where('user_id', $user->id)
                ->whereYear('leave_date', $currentYear)
                ->where('status', 'อนุมัติ')
                ->where('leave_type', 'ลาป่วย')
                ->count(),
        ];

        $leaveRequests = LeaveRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard', compact('leaveStats', 'leaveRequests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|in:ลากิจ,ลาป่วย',
            'duration_type' => 'required|in:ทั้งวัน,ชั่วโมง',
            'leave_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required_if:duration_type,ชั่วโมง|nullable|date_format:H:i',
            'end_time' => 'required_if:duration_type,ชั่วโมง|nullable|date_format:H:i|after:start_time',
            'additional_info' => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('leave_attachments', 'public');
        }

        LeaveRequest::create([
            'user_id' => Auth::id(),
            'leave_type' => $request->leave_type,
            'duration_type' => $request->duration_type,
            'leave_date' => $request->leave_date,
            'start_time' => $request->duration_type === 'ชั่วโมง' ? $request->start_time : null,
            'end_time' => $request->duration_type === 'ชั่วโมง' ? $request->end_time : null,
            'additional_info' => $request->additional_info,
            'attachment_path' => $attachmentPath,
        ]);

        return redirect()->route('dashboard')->with('success', 'ส่งคำขอลางานเรียบร้อยแล้ว');
    }

    public function history()
    {
        $user = Auth::user();
        $leaveRequests = LeaveRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('leave-history', compact('leaveRequests'));
    }
}
