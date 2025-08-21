<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProjectIssue extends Model
{
    protected $connection = 'mysql';
    protected $table = 'project_issue';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'ProjectInfoID',
        'IssueName',
        'IssueDescription',
        'ResponsibleUserID',
        'StartDate',
        'EndDate',
        'ActualStartDate',
        'ActualEndDate',
        'Status',
        'Priority',
        'ParentID',
        'EstimatedHours',
        'ActualHours',
        'CreateDate',
        'IssueType',
        'Severity'
    ];

    protected $casts = [
        'StartDate' => 'datetime',
        'EndDate' => 'datetime', 
        'ActualStartDate' => 'datetime',
        'ActualEndDate' => 'datetime',
        'CreateDate' => 'datetime',
        'EstimatedHours' => 'decimal:2',
        'ActualHours' => 'decimal:2'
    ];

    protected $appends = ['ActualDays', 'PlannedEnd', 'OnPlan', 'SlippageDays'];

    public function project()
    {
        return $this->belongsTo(ProjectInfo::class, 'ProjectInfoID', 'ID');
    }
    
    public function assignee()
    {
        return $this->belongsTo(SysUser::class, 'ResponsibleUserID', 'ID');
    }
    
    public function parent()
    {
        return $this->belongsTo(ProjectIssue::class, 'ParentID', 'ID');
    }
    
    public function children()
    {
        return $this->hasMany(ProjectIssue::class, 'ParentID', 'ID');
    }
    
    public function situations()
    {
        return $this->hasMany(ProjectIssueSituation::class, 'ProjectIssueID', 'ID');
    }

    // Accessors สำหรับการ์ด
    public function getActualDaysAttribute()
    {
        if (!$this->ActualStartDate || !$this->ActualEndDate) return null;
        return Carbon::parse($this->ActualStartDate)->diffInDays(Carbon::parse($this->ActualEndDate)) + 1;
    }
    
    public function getPlannedEndAttribute()
    {
        return $this->EndDate ?: null; // ใช้ EndDate ของงานเป็นจุดจบแผน
    }
    
    public function getOnPlanAttribute()
    {
        if (!$this->PlannedEnd || !$this->ActualEndDate) return null;
        return Carbon::parse($this->ActualEndDate)->lte(Carbon::parse($this->PlannedEnd));
    }
    public function getSlippageDaysAttribute()
    {
        if (!$this->PlannedEnd || !$this->ActualEndDate) return null;
        return Carbon::parse($this->ActualEndDate)->diffInDays(Carbon::parse($this->PlannedEnd), false); // ติดลบ=ช้า
    }
}
