<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TimeLog extends Model
{
    protected $fillable = [
        'user_id',
        'work_date',
        'clock_in',
        'clock_out',
        'clock_in_location',
        'clock_out_location',
        'clock_in_photo',
        'clock_out_photo',
        'status',
        'work_hours',
        'notes',
        'is_overtime',
        'overtime_minutes'
    ];

    protected $casts = [
        'work_date' => 'datetime:Y-m-d',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'is_overtime' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // คำนวณชั่วโมงทำงาน
    public function calculateWorkHours(): void
    {
        if ($this->clock_in && $this->clock_out) {
            $minutes = $this->clock_in->diffInMinutes($this->clock_out);
            
            // หักเวลาพักเที่ยง 60 นาที (ถ้าทำงานเกิน 6 ชั่วโมง)
            if ($minutes > 360) {
                $minutes -= 60;
            }
            
            $this->work_hours = $minutes;
            
            // คำนวณล่วงเวลา (เกิน 8 ชั่วโมง = 480 นาที)
            if ($minutes > 480) {
                $this->is_overtime = true;
                $this->overtime_minutes = $minutes - 480;
            }
        }
    }

    // ตรวจสอบสถานะการมาสาย
    public function updateStatus(): void
    {
        if ($this->clock_in) {
            $workStartTime = $this->work_date->copy()->setTime(9, 0, 0);
            
            if ($this->clock_in->gt($workStartTime)) {
                $this->status = 'late';
            } else {
                $this->status = 'present';
            }
        } else {
            $this->status = 'absent';
        }
    }

    // Scope สำหรับค้นหาข้อมูลของเดือนปัจจุบัน
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('work_date', now()->month)
                    ->whereYear('work_date', now()->year);
    }

    // Scope สำหรับค้นหาข้อมูลของวันนี้
    public function scopeToday($query)
    {
        return $query->whereDate('work_date', today());
    }

    // ฟอร์แมตเวลาทำงาน
    public function getFormattedWorkHoursAttribute(): string
    {
        $hours = floor($this->work_hours / 60);
        $minutes = $this->work_hours % 60;
        
        return sprintf('%d:%02d', $hours, $minutes);
    }

    // ฟอร์แมตล่วงเวลา
    public function getFormattedOvertimeAttribute(): string
    {
        if (!$this->is_overtime) return '0:00';
        
        $hours = floor($this->overtime_minutes / 60);
        $minutes = $this->overtime_minutes % 60;
        
        return sprintf('%d:%02d', $hours, $minutes);
    }
}
