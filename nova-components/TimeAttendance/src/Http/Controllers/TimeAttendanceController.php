<?php

namespace Company\TimeAttendance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use App\Models\TimeLog;
use App\Models\User;
use Carbon\Carbon;

class TimeAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // ดึงข้อมูลการลงเวลาของวันนี้
        $todayLog = TimeLog::where('user_id', $user->id)
            ->whereDate('work_date', Carbon::today())
            ->first();
            
        // ดึงสถิติการลงเวลาของเดือนนี้
        $monthlyLogs = TimeLog::where('user_id', $user->id)
            ->currentMonth()
            ->get();
            
        $monthlyStats = [
            'total_days' => $monthlyLogs->count(),
            'present_days' => $monthlyLogs->where('status', 'present')->count(),
            'late_days' => $monthlyLogs->where('status', 'late')->count(),
            'early_departure' => $monthlyLogs->where('status', 'early_departure')->count(),
        ];
        
        // ดึงประวัติการลงเวลา 7 วันล่าสุด
        $recentLogs = TimeLog::where('user_id', $user->id)
            ->orderBy('work_date', 'desc')
            ->take(7)
            ->get();
            
        // คำนวณเวลาทำงานเฉลี่ย
        $averageWorkingHours = 0;
        if ($monthlyLogs->count() > 0) {
            $totalHours = 0;
            $workingDays = 0;
            
            foreach ($monthlyLogs as $log) {
                if ($log->clock_in && $log->clock_out) {
                    $clockIn = Carbon::parse($log->clock_in);
                    $clockOut = Carbon::parse($log->clock_out);
                    $totalHours += $clockIn->diffInHours($clockOut);
                    $workingDays++;
                }
            }
            
            if ($workingDays > 0) {
                $averageWorkingHours = round($totalHours / $workingDays, 1);
            }
        }
        
        return Inertia::render('TimeAttendance', [
            'user' => $user,
            'todayLog' => $todayLog,
            'monthlyStats' => $monthlyStats,
            'recentLogs' => $recentLogs,
            'averageWorkingHours' => $averageWorkingHours,
            'currentTime' => Carbon::now()->toISOString(),
        ]);
    }
    
    public function clockIn(Request $request)
    {
        $user = $request->user();
        $now = Carbon::now();
        
        // ตรวจสอบว่าลงเวลาเข้างานวันนี้แล้วหรือยัง
        $existingLog = TimeLog::where('user_id', $user->id)
            ->whereDate('work_date', $now->toDateString())
            ->first();
            
        if ($existingLog && $existingLog->clock_in) {
            return response()->json([
                'success' => false,
                'message' => 'คุณได้ลงเวลาเข้างานวันนี้แล้ว'
            ], 400);
        }
        
        // รับข้อมูล location จาก request
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $location = null;
        
        if ($latitude && $longitude) {
            $location = "{$latitude},{$longitude}";
        }
        
        // กำหนดสถานะตามเวลา (สมมติว่าเวลาทำงานคือ 9:00)
        $status = 'present';
        if ($now->hour >= 9) {
            $status = 'late';
        }
        
        // สร้างหรืออัพเดต TimeLog
        if ($existingLog) {
            $existingLog->update([
                'clock_in' => $now,
                'clock_in_location' => $location,
                'status' => $status,
            ]);
            $existingLog->updateStatus();
            $existingLog->save();
            $timeLog = $existingLog;
        } else {
            $timeLog = TimeLog::create([
                'user_id' => $user->id,
                'work_date' => $now->toDateString(),
                'clock_in' => $now,
                'clock_in_location' => $location,
                'status' => $status,
            ]);
            $timeLog->updateStatus();
            $timeLog->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'บันทึกเวลาเข้างานเรียบร้อย',
            'time' => $now->format('H:i:s'),
            'status' => $status,
            'timeLog' => $timeLog
        ]);
    }
    
    public function clockOut(Request $request)
    {
        $user = $request->user();
        $now = Carbon::now();
        
        // ค้นหา TimeLog ของวันนี้
        $timeLog = TimeLog::where('user_id', $user->id)
            ->whereDate('work_date', $now->toDateString())
            ->first();
            
        if (!$timeLog || !$timeLog->clock_in) {
            return response()->json([
                'success' => false,
                'message' => 'กรุณาลงเวลาเข้างานก่อน'
            ], 400);
        }
        
        if ($timeLog->clock_out) {
            return response()->json([
                'success' => false,
                'message' => 'คุณได้ลงเวลาออกงานวันนี้แล้ว'
            ], 400);
        }
        
        // รับข้อมูล location จาก request
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $location = null;
        
        if ($latitude && $longitude) {
            $location = "{$latitude},{$longitude}";
        }
        
        // อัพเดต TimeLog
        $timeLog->update([
            'clock_out' => $now,
            'clock_out_location' => $location,
        ]);
        
        // คำนวณชั่วโมงทำงาน
        $timeLog->calculateWorkHours();
        $timeLog->save();
        
        // คำนวณชั่วโมงทำงาน
        $workingHours = number_format($timeLog->work_hours / 60, 1);
        
        return response()->json([
            'success' => true,
            'message' => 'บันทึกเวลาออกงานเรียบร้อย',
            'time' => $now->format('H:i:s'),
            'working_hours' => $workingHours,
            'status' => $timeLog->status,
            'timeLog' => $timeLog
        ]);
    }
    
    public function getLocation(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        
        // สำหรับการพัฒนา เราจะ mock location name
        // ในการใช้งานจริงสามารถใช้ Google Maps API หรือ reverse geocoding
        $locationName = 'สำนักงาน บริษัท ABC จำกัด';
        
        // ตรวจสอบว่าอยู่ในรัศมีที่กำหนดหรือไม่
        $officeLatitude = 13.7563; // พิกัดสำนักงาน (ตัวอย่าง)
        $officeLongitude = 100.5018;
        
        $distance = $this->calculateDistance($latitude, $longitude, $officeLatitude, $officeLongitude);
        $isInRange = $distance <= 0.1; // รัศมี 100 เมตร
        
        return response()->json([
            'location_name' => $locationName,
            'is_in_range' => $isInRange,
            'distance' => round($distance * 1000, 0), // แปลงเป็นเมตร
        ]);
    }
    
    public function getStats(Request $request)
    {
        $user = $request->user();
        
        // สถิติเดือนนี้
        $currentMonth = TimeLog::where('user_id', $user->id)->currentMonth()->get();
        
        // สถิติปีนี้
        $currentYear = TimeLog::where('user_id', $user->id)
            ->whereYear('work_date', now()->year)
            ->get();
            
        // คำนวณเวลาทำงานรวม
        $totalWorkingMinutes = $currentMonth->sum('work_hours');
        $totalOvertimeMinutes = $currentMonth->sum('overtime_minutes');
        
        return response()->json([
            'monthly' => [
                'total_days' => $currentMonth->count(),
                'present_days' => $currentMonth->where('status', 'present')->count(),
                'late_days' => $currentMonth->where('status', 'late')->count(),
                'total_working_hours' => round($totalWorkingMinutes / 60, 1),
                'total_overtime_hours' => round($totalOvertimeMinutes / 60, 1),
            ],
            'yearly' => [
                'total_days' => $currentYear->count(),
                'present_days' => $currentYear->where('status', 'present')->count(),
                'late_days' => $currentYear->where('status', 'late')->count(),
            ]
        ]);
    }
    
    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // รัศมีโลกในหน่วยกิโลเมตร
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng/2) * sin($dLng/2);
             
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }
}
