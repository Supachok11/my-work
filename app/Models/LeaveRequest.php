<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class LeaveRequest extends Model
{
    protected $fillable = [
        'user_id',
        'leave_type',
        'duration_type',
        'leave_date',
        'start_time',
        'end_time',
        'additional_info',
        'attachment_path',
        'status',
        'is_range',
        'range_start_date',
        'range_end_date',
        'approved_at',
        'rejected_at'
    ];

    protected $casts = [
        'leave_date' => 'datetime',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'range_start_date' => 'date',
        'range_end_date' => 'date',
        'is_range' => 'boolean',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($leaveRequest) {
            // ตั้งค่า user_id หากไม่ได้ระบุ
            if (!$leaveRequest->user_id) {
                $leaveRequest->user_id = Auth::id() ?? 1;
            }
            if (!$leaveRequest->status) {
                $leaveRequest->status = 'รออนุมัติ';
            }
            if ($leaveRequest->is_range && $leaveRequest->range_start_date && !$leaveRequest->leave_date) {
                $leaveRequest->leave_date = $leaveRequest->range_start_date;
            }
        });

        static::updating(function ($leaveRequest) {
            if ($leaveRequest->is_range && $leaveRequest->range_start_date) {
                $leaveRequest->leave_date = $leaveRequest->range_start_date;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
