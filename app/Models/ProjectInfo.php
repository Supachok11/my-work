<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectInfo extends Model
{
    protected $connection = 'mysql';
    protected $table = 'project_info';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'ProjectName',
        'CustomerID',
        'ProjectDescription', 
        'StartDate',
        'EndDate',
        'Status',
        'ProjectManagerID',
        'Budget',
        'CreateDate',
        'Priority',
        'ProjectType'
    ];

    protected $casts = [
        'StartDate' => 'datetime',
        'EndDate' => 'datetime',
        'CreateDate' => 'datetime',
        'Budget' => 'decimal:2'
    ];

    public function issues()
    {
        return $this->hasMany(ProjectIssue::class, 'ProjectInfoID', 'ID');
    }

    public function manager()
    {
        return $this->belongsTo(SysUser::class, 'ProjectManagerID', 'ID');
    }

    // สถานะโปรเจค
    public function getStatusTextAttribute()
    {
        $statuses = [
            'NEW' => 'ใหม่',
            'IN_PROGRESS' => 'กำลังดำเนินการ',
            'ON_HOLD' => 'ระงับ',
            'COMPLETED' => 'เสร็จสิ้น',
            'CANCELLED' => 'ยกเลิก'
        ];
        
        return $statuses[$this->Status] ?? $this->Status;
    }

    // ความคืบหน้าโปรเจค
    public function getProgressAttribute()
    {
        $totalIssues = $this->issues()->count();
        if ($totalIssues === 0) return 0;
        
        $completedIssues = $this->issues()
            ->whereNotNull('ActualEndDate')
            ->count();
            
        return round(($completedIssues / $totalIssues) * 100, 2);
    }
}
