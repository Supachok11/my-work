<?php

namespace Company\MyTasks\Http\Controllers;

use App\Models\ProjectIssue;
use App\Models\ProjectInfo;
use App\Models\SysUser;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Http\Requests\NovaRequest;

class MyTasksController
{
    public function index(NovaRequest $request): JsonResponse
    {
        try {
            // ได้รับ User ID จาก Laravel Nova user
            $user = $request->user();
            
            // สร้างข้อมูลงานตัวอย่าง 5 งาน สำหรับทุก user
            $mockTasks = [
                [
                    'ID' => 1,
                    'ProjectInfoID' => 1,
                    'IssueName' => 'พัฒนา API สำหรับระบบ MOC Frontend',
                    'IssueDescription' => 'สร้าง RESTful API สำหรับเชื่อมต่อกับ Frontend ระบบ MOC',
                    'ResponsibleUserID' => 1,
                    'StartDate' => '2025-08-15',
                    'EndDate' => '2025-08-30',
                    'ActualStartDate' => '2025-08-15',
                    'Status' => 'IN_PROGRESS',
                    'Priority' => 'HIGH',
                    'project' => ['ProjectName' => 'MOC Frontend'],
                    'assignee' => ['FirstName' => 'ผู้ดูแลระบบ', 'LastName' => 'เอ็มเวิร์ค']
                ],
                [
                    'ID' => 2,
                    'ProjectInfoID' => 1,
                    'IssueName' => 'ออกแบบฐานข้อมูลระบบจัดเก็บข้อมูลบุคลากร',
                    'IssueDescription' => 'ออกแบบและสร้างตารางฐานข้อมูลสำหรับจัดเก็บข้อมูลบุคลากร',
                    'ResponsibleUserID' => 1,
                    'StartDate' => '2025-08-20',
                    'EndDate' => '2025-09-05',
                    'ActualStartDate' => '2025-08-20',
                    'Status' => 'IN_PROGRESS',
                    'Priority' => 'HIGH',
                    'project' => ['ProjectName' => 'MOC Frontend'],
                    'assignee' => ['FirstName' => 'ผู้ดูแลระบบ', 'LastName' => 'เอ็มเวิร์ค']
                ],
                [
                    'ID' => 3,
                    'ProjectInfoID' => 2,
                    'IssueName' => 'พัฒนาหน้าจอแสดงผลข้อมูลบุคลากร',
                    'IssueDescription' => 'สร้างหน้าจอสำหรับแสดงผลข้อมูลบุคลากรในรูปแบบตาราง',
                    'ResponsibleUserID' => 1,
                    'StartDate' => '2025-08-18',
                    'EndDate' => '2025-08-25',
                    'ActualStartDate' => '2025-08-18',
                    'ActualEndDate' => '2025-08-24',
                    'Status' => 'COMPLETED',
                    'Priority' => 'MEDIUM',
                    'project' => ['ProjectName' => 'MOC Frontend'],
                    'assignee' => ['FirstName' => 'ผู้ดูแลระบบ', 'LastName' => 'เอ็มเวิร์ค']
                ],
                [
                    'ID' => 4,
                    'ProjectInfoID' => 2,
                    'IssueName' => 'ทดสอบระบบและแก้ไขบัค',
                    'IssueDescription' => 'ทดสอบการทำงานของระบบทั้งหมดและแก้ไขข้อผิดพลาดที่พบ',
                    'ResponsibleUserID' => 1,
                    'StartDate' => '2025-08-25',
                    'EndDate' => '2025-09-10',
                    'Status' => 'NEW',
                    'Priority' => 'MEDIUM',
                    'project' => ['ProjectName' => 'MOC Frontend'],
                    'assignee' => ['FirstName' => 'ผู้ดูแลระบบ', 'LastName' => 'เอ็มเวิร์ค']
                ],
                [
                    'ID' => 5,
                    'ProjectInfoID' => 1,
                    'IssueName' => 'จัดทำเอกสารคู่มือการใช้งานระบบ',
                    'IssueDescription' => 'สร้างเอกสารคู่มือการใช้งานสำหรับผู้ใช้ปลายทาง',
                    'ResponsibleUserID' => 1,
                    'StartDate' => '2025-09-01',
                    'EndDate' => '2025-09-15',
                    'Status' => 'NEW',
                    'Priority' => 'LOW',
                    'project' => ['ProjectName' => 'MOC Frontend'],
                    'assignee' => ['FirstName' => 'ผู้ดูแลระบบ', 'LastName' => 'เอ็มเวิร์ค']
                ]
            ];

            // สร้างข้อมูลโปรเจคตัวอย่าง
            $mockProjects = [
                ['ID' => 1, 'ProjectName' => 'MOC Frontend'],
                ['ID' => 2, 'ProjectName' => 'MOC Frontend']
            ];

            return response()->json([
                'tasks' => $mockTasks,
                'projects' => $mockProjects,
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching my tasks: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'เกิดข้อผิดพลาดในการดึงข้อมูล',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(NovaRequest $request, $id): JsonResponse
    {
        try {
            $task = ProjectIssue::with([
                'project',
                'assignee', 
                'parent',
                'children',
                'situations.user'
            ])->findOrFail($id);

            // ตรวจสอบสิทธิ์ - ต้องเป็นผู้รับผิดชอบงานนี้
            $user = $request->user();
            $sysUser = SysUser::where('Email', $user->email)->first();
            
            if (!$sysUser || $task->ResponsibleUserID !== $sysUser->ID) {
                return response()->json([
                    'error' => 'ไม่มีสิทธิ์เข้าถึงงานนี้'
                ], 403);
            }

            return response()->json([
                'task' => $task
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'ไม่พบงานที่ระบุ',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function updateStatus(NovaRequest $request, $id): JsonResponse
    {
        try {
            $task = ProjectIssue::findOrFail($id);

            // ตรวจสอบสิทธิ์
            $user = $request->user();
            $sysUser = SysUser::where('Email', $user->email)->first();
            
            if (!$sysUser || $task->ResponsibleUserID !== $sysUser->ID) {
                return response()->json([
                    'error' => 'ไม่มีสิทธิ์แก้ไขงานนี้'
                ], 403);
            }

            $newStatus = $request->input('status');
            $validStatuses = ['NEW', 'IN_PROGRESS', 'COMPLETED', 'ON_HOLD'];
            
            if (!in_array($newStatus, $validStatuses)) {
                return response()->json([
                    'error' => 'สถานะไม่ถูกต้อง'
                ], 400);
            }

            $task->Status = $newStatus;
            
            // อัปเดตวันที่เริ่ม/สิ้นสุดจริง
            if ($newStatus === 'IN_PROGRESS' && !$task->ActualStartDate) {
                $task->ActualStartDate = now();
            } elseif ($newStatus === 'COMPLETED' && !$task->ActualEndDate) {
                $task->ActualEndDate = now();
            }

            $task->save();

            return response()->json([
                'message' => 'อัปเดตสถานะเรียบร้อย',
                'task' => $task->fresh(['project', 'assignee'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'เกิดข้อผิดพลาดในการอัปเดตสถานะ',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function addComment(NovaRequest $request, $id): JsonResponse
    {
        try {
            $task = ProjectIssue::findOrFail($id);

            // ตรวจสอบสิทธิ์
            $user = $request->user();
            $sysUser = SysUser::where('Email', $user->email)->first();
            
            if (!$sysUser || $task->ResponsibleUserID !== $sysUser->ID) {
                return response()->json([
                    'error' => 'ไม่มีสิทธิ์เพิ่มความคิดเห็นในงานนี้'
                ], 403);
            }

            $comment = $request->input('comment');
            if (empty($comment)) {
                return response()->json([
                    'error' => 'กรุณาใส่ความคิดเห็น'
                ], 400);
            }

            // สร้าง Situation ใหม่
            $situation = new \App\Models\ProjectIssueSituation();
            $situation->ProjectIssueID = $task->ID;
            $situation->UserID = $sysUser->ID;
            $situation->SituationDescription = $comment;
            $situation->CreateDate = now();
            $situation->SituationType = 'COMMENT';
            $situation->Status = 'ACTIVE';
            $situation->save();

            return response()->json([
                'message' => 'เพิ่มความคิดเห็นเรียบร้อย',
                'situation' => $situation->load('user')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'เกิดข้อผิดพลาดในการเพิ่มความคิดเห็น',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
