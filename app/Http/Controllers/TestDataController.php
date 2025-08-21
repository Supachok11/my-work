<?php

namespace App\Http\Controllers;

use App\Models\ProjectInfo;
use App\Models\ProjectIssue;
use App\Models\SysUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestDataController extends Controller
{
    public function createTestData()
    {
        try {
            // สร้าง Laravel Users ที่ตรงกับ SysUsers ที่มีอยู่แล้ว
            $existingSysUsers = [
                [
                    'email' => 'admin@emworkgroup.com',
                    'name' => 'ผู้ดูแลระบบ เอ็มเวิร์ค',
                    'password' => 'password'
                ],
                [
                    'email' => 'praphan@emworkgroup.com', 
                    'name' => 'ประพันธ์ สุรปภา',
                    'password' => 'password'
                ],
                [
                    'email' => 'kerkkiat@emworkgroup.com',
                    'name' => 'เกริกเกียรติ เสริมศักดิ์สกุล', 
                    'password' => 'password'
                ]
            ];

            foreach ($existingSysUsers as $userData) {
                User::firstOrCreate(
                    ['email' => $userData['email']], 
                    [
                        'name' => $userData['name'],
                        'password' => Hash::make($userData['password']),
                        'role' => 'admin'
                    ]
                );
            }

            // สร้างข้อมูลโปรเจคทดสอบ (ใช้ฟิลด์พื้นฐานเท่านั้น)
            $sampleProjects = [
                [
                    'ID' => 1,
                    'Created' => now(),
                    'Modified' => now(),
                    'IsDeleted' => 'N'
                ],
                [
                    'ID' => 2, 
                    'Created' => now(),
                    'Modified' => now(),
                    'IsDeleted' => 'N'
                ]
            ];

            foreach ($sampleProjects as $projectData) {
                DB::connection('mysql')->table('project_info')->updateOrInsert(
                    ['ID' => $projectData['ID']],
                    $projectData
                );
            }

            // สร้างข้อมูลงานทดสอบ 5 งาน
            $sampleTasks = [
                [
                    'ID' => 1,
                    'ProjectInfoID' => 1,
                    'IssueName' => 'พัฒนา API สำหรับระบบ MOC Frontend',
                    'IssueDescription' => 'สร้าง RESTful API สำหรับเชื่อมต่อกับ Frontend ระบบ MOC',
                    'ResponsibleUserID' => 1, // admin
                    'StartDate' => '2025-08-15 00:00:00',
                    'EndDate' => '2025-08-30 23:59:59',
                    'ActualStartDate' => '2025-08-15 09:00:00',
                    'Status' => 'IN_PROGRESS',
                    'Priority' => 'HIGH',
                    'Created' => now(),
                    'Modified' => now(),
                    'IsDeleted' => 'N'
                ],
                [
                    'ID' => 2,
                    'ProjectInfoID' => 1,
                    'IssueName' => 'ออกแบบฐานข้อมูลระบบจัดเก็บข้อมูลบุคลากร',
                    'IssueDescription' => 'ออกแบบและสร้างตารางฐานข้อมูลสำหรับจัดเก็บข้อมูลบุคลากร',
                    'ResponsibleUserID' => 1,
                    'StartDate' => '2025-08-20 00:00:00',
                    'EndDate' => '2025-09-05 23:59:59',
                    'ActualStartDate' => '2025-08-20 10:00:00',
                    'Status' => 'IN_PROGRESS',
                    'Priority' => 'HIGH',
                    'Created' => now(),
                    'Modified' => now(),
                    'IsDeleted' => 'N'
                ],
                [
                    'ID' => 3,
                    'ProjectInfoID' => 2,
                    'IssueName' => 'พัฒนาหน้าจอแสดงผลข้อมูลบุคลากร',
                    'IssueDescription' => 'สร้างหน้าจอสำหรับแสดงผลข้อมูลบุคลากรในรูปแบบตาราง',
                    'ResponsibleUserID' => 1,
                    'StartDate' => '2025-08-18 00:00:00',
                    'EndDate' => '2025-08-25 23:59:59',
                    'ActualStartDate' => '2025-08-18 14:00:00',
                    'ActualEndDate' => '2025-08-24 16:30:00',
                    'Status' => 'COMPLETED',
                    'Priority' => 'MEDIUM',
                    'Created' => now(),
                    'Modified' => now(),
                    'IsDeleted' => 'N'
                ],
                [
                    'ID' => 4,
                    'ProjectInfoID' => 2,
                    'IssueName' => 'ทดสอบระบบและแก้ไขบัค',
                    'IssueDescription' => 'ทดสอบการทำงานของระบบทั้งหมดและแก้ไขข้อผิดพลาดที่พบ',
                    'ResponsibleUserID' => 1,
                    'StartDate' => '2025-08-25 00:00:00',
                    'EndDate' => '2025-09-10 23:59:59',
                    'Status' => 'NEW',
                    'Priority' => 'MEDIUM',
                    'Created' => now(),
                    'Modified' => now(),
                    'IsDeleted' => 'N'
                ],
                [
                    'ID' => 5,
                    'ProjectInfoID' => 1,
                    'IssueName' => 'จัดทำเอกสารคู่มือการใช้งานระบบ',
                    'IssueDescription' => 'สร้างเอกสารคู่มือการใช้งานสำหรับผู้ใช้ปลายทาง',
                    'ResponsibleUserID' => 1,
                    'StartDate' => '2025-09-01 00:00:00',
                    'EndDate' => '2025-09-15 23:59:59',
                    'Status' => 'NEW',
                    'Priority' => 'LOW',
                    'Created' => now(),
                    'Modified' => now(),
                    'IsDeleted' => 'N'
                ]
            ];

            foreach ($sampleTasks as $taskData) {
                DB::connection('mysql')->table('project_issue')->updateOrInsert(
                    ['ID' => $taskData['ID']],
                    $taskData
                );
            }

            return response()->json([
                'message' => 'สร้างข้อมูลทดสอบเรียบร้อยแล้ว',
                'users' => count($existingSysUsers),
                'projects' => count($sampleProjects),
                'tasks' => count($sampleTasks),
                'note' => 'ข้อมูลงาน 5 งานถูกสร้างและกำหนดให้กับ admin user สำหรับทดสอบ'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'เกิดข้อผิดพลาดในการสร้างข้อมูลทดสอบ',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
