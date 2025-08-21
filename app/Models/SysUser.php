<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysUser extends Model
{
    protected $connection = 'mysql';
    protected $table = 'sys_user';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'UserFrontType',
        'UserType', 
        'UserGroupID',
        'ProfileImageID',
        'Username',
        'Password',
        'TitleName',
        'FirstName',
        'LastName',
        'Gender',
        'BirthDate',
        'Email',
        'Telephone',
        'Fax',
        'Mobile',
        'WorkingGroup',
        'DirectorType',
        'DepartmentID',
        'PositionID',
        'LastLoginDate',
        'LastAttemptDate',
        'AttemptAmount',
        'PasswordExpireDate',
        'ActivateCode',
        'MocAccountID',
        'MocAccountDepartmentID',
        'MocAccountDepartmentName',
        'Created',
        'CreatedBy',
        'Modified',
        'ModifiedBy',
        'IsEnable',
        'IsDeleted',
        'MITV2UserID'
    ];

    protected $casts = [
        'BirthDate' => 'datetime',
        'LastLoginDate' => 'datetime',
        'LastAttemptDate' => 'datetime',
        'PasswordExpireDate' => 'datetime',
        'Created' => 'datetime',
        'Modified' => 'datetime'
    ];

    protected $hidden = [
        'Password'
    ];

    // ชื่อเต็ม
    public function getFullNameAttribute()
    {
        return trim($this->FirstName . ' ' . $this->LastName);
    }

    // โปรเจคที่รับผิดชอบ
    public function managedProjects()
    {
        return $this->hasMany(ProjectInfo::class, 'ProjectManagerID', 'ID');
    }

    // งานที่ถูกมอบหมาย
    public function assignedIssues()
    {
        return $this->hasMany(ProjectIssue::class, 'ResponsibleUserID', 'ID');
    }

    // สถานการณ์ที่สร้าง
    public function situations()
    {
        return $this->hasMany(ProjectIssueSituation::class, 'UserID', 'ID');
    }
}
