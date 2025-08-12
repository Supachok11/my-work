<?php

namespace Leave\LeavePortal\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use App\Models\LeaveRequest;
use App\Models\User;

class LeavePortalController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // คำนวณสถิติการลาของผู้ใช้
        $userLeaveRequests = LeaveRequest::where('user_id', $user->id)
            ->where('status', 'อนุมัติ')
            ->get();
            
        // คำนวณจำนวนวันลา
        $totalDays = 0;
        $personalLeaveDays = 0;
        $sickLeaveDays = 0;
        
        foreach ($userLeaveRequests as $request) {
            $days = $this->calculateLeaveDays($request);
            $totalDays += $days;
            
            if ($request->leave_type === 'ลากิจ') {
                $personalLeaveDays += $days;
            } elseif ($request->leave_type === 'ลาป่วย') {
                $sickLeaveDays += $days;
            }
        }
        
        // สำหรับ Admin: ดึงจำนวนคำขอที่รออนุมัติ
        $pendingCount = 0;
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            $pendingCount = LeaveRequest::where('status', 'รออนุมัติ')->count();
        }
        
        // ดึงคำขอล่าสุด 5 รายการ
        $recentRequests = LeaveRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return Inertia::render('LeavePortal', [
            'user' => $user,
            'leaveStats' => [
                'total_days' => $totalDays,
                'personal_leave' => $personalLeaveDays,
                'sick_leave' => $sickLeaveDays,
            ],
            'pendingCount' => $pendingCount,
            'recentRequests' => $recentRequests,
        ]);
    }
    
    private function calculateLeaveDays($request)
    {
        if ($request->is_range && $request->range_start_date && $request->range_end_date) {
            $startDate = \Carbon\Carbon::parse($request->range_start_date);
            $endDate = \Carbon\Carbon::parse($request->range_end_date);
            return $startDate->diffInDays($endDate) + 1;
        } elseif ($request->duration_type === 'ชั่วโมง' && $request->start_time && $request->end_time) {
            $startTime = \Carbon\Carbon::parse($request->start_time);
            $endTime = \Carbon\Carbon::parse($request->end_time);
            return $startTime->diffInHours($endTime) / 8; // แปลงชั่วโมงเป็นวัน (8 ชั่วโมง = 1 วัน)
        } else {
            return 1; // ทั้งวัน
        }
    }
}
