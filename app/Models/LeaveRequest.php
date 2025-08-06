<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
