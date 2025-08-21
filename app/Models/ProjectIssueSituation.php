<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectIssueSituation extends Model
{
    protected $connection = 'mysql';
    protected $table = 'project_issue_situation';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'ProjectIssueID',
        'UserID',
        'SituationDescription',
        'CreateDate',
        'SituationType',
        'Status'
    ];

    protected $casts = [
        'CreateDate' => 'datetime'
    ];

    public function issue() 
    { 
        return $this->belongsTo(ProjectIssue::class, 'ProjectIssueID', 'ID'); 
    }
    
    public function user()  
    { 
        return $this->belongsTo(SysUser::class, 'UserID', 'ID'); 
    }
}
