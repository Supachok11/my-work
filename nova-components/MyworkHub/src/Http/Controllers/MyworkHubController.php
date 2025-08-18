<?php

namespace Company\MyworkHub\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use App\Models\User;
use App\Models\TimeLog;
use App\Models\LeaveRequest;
use Carbon\Carbon;

class MyworkHubController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // ดึงข้อมูลสถิติต่างๆ สำหรับหน้าหลัก
        $stats = $this->getUserStats($user);
        
        return Inertia::render('MyworkHub', [
            'user' => $user,
            'stats' => $stats,
            'currentTime' => Carbon::now()->toISOString(),
        ]);
    }
    
    private function getUserStats(User $user)
    {
        // สถิติการลงเวลาทำงาน
        $attendanceStats = [
            'today_status' => $this->getTodayAttendanceStatus($user),
            'this_month_days' => $this->getMonthlyAttendanceDays($user),
            'this_week_hours' => $this->getWeeklyWorkingHours($user),
        ];
        
        // สถิติการลา
        $leaveStats = [
            'pending_requests' => LeaveRequest::where('user_id', $user->id)
                ->where('status', 'รออนุมัติ')
                ->count(),
            'approved_this_month' => LeaveRequest::where('user_id', $user->id)
                ->where('status', 'อนุมัติ')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'remaining_leave_days' => $this->calculateRemainingLeaveDays($user),
        ];
        
        return [
            'attendance' => $attendanceStats,
            'leave' => $leaveStats,
        ];
    }
    
    private function getTodayAttendanceStatus(User $user)
    {
        $todayLog = TimeLog::where('user_id', $user->id)
            ->whereDate('work_date', today())
            ->first();
            
        if (!$todayLog) {
            return 'not_checked_in';
        }
        
        if ($todayLog->clock_in && !$todayLog->clock_out) {
            return 'checked_in';
        }
        
        if ($todayLog->clock_in && $todayLog->clock_out) {
            return 'completed';
        }
        
        return 'not_checked_in';
    }
    
    private function getMonthlyAttendanceDays(User $user)
    {
        return TimeLog::where('user_id', $user->id)
            ->whereMonth('work_date', now()->month)
            ->whereYear('work_date', now()->year)
            ->whereNotNull('clock_in')
            ->count();
    }
    
    private function getWeeklyWorkingHours(User $user)
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        
        $totalMinutes = TimeLog::where('user_id', $user->id)
            ->whereBetween('work_date', [$startOfWeek, $endOfWeek])
            ->whereNotNull('clock_in')
            ->whereNotNull('clock_out')
            ->sum('work_hours');
            
        return round($totalMinutes / 60, 1); // แปลงเป็นชั่วโมง
    }
    
    private function calculateRemainingLeaveDays(User $user)
    {
        // สมมติว่าพนักงานมีสิทธิลา 10 วัน/ปี
        $totalLeaveDays = 10;
        
        $usedDays = LeaveRequest::where('user_id', $user->id)
            ->where('status', 'อนุมัติ')
            ->whereYear('created_at', now()->year)
            ->count();
            
        return max(0, $totalLeaveDays - $usedDays);
    }
}
