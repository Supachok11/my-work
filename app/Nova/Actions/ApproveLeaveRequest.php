<?php

namespace App\Nova\Actions;

use App\Mail\LeaveRequestStatusNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class ApproveLeaveRequest extends Action
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * The displayable name of the action.
     */
    public $name = 'อนุมัติ';

    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            // ตรวจสอบว่าสถานะยังเป็น "รออนุมัติ" หรือไม่
            if ($model->status !== 'รออนุมัติ') {
                return Action::danger('ไม่สามารถอนุมัติได้ เพราะสถานะไม่ใช่ "รออนุมัติ"');
            }

            // อัปเดตสถานะเป็น "อนุมัติ"
            $model->update([
                'status' => 'อนุมัติ',
                'approved_at' => now(),
                'rejected_at' => null,
            ]);

            // // ส่งเมลแจ้งเตือนพนักงาน
            // try {
            //     Mail::to($model->user->email)->send(new LeaveRequestStatusNotification($model));
            // } catch (\Exception $e) {
            //     // Log error but don't fail the action
            //     Log::error('Failed to send approval notification: ' . $e->getMessage());
            // }
        }

        return Action::message('อนุมัติคำขอลางานเรียบร้อยแล้ว!');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Determine if the action is executable for the given request.
     */
    public function authorizedToSee(\Illuminate\Http\Request $request)
    {
        return method_exists($request->user(), 'hasRole') && $request->user()->hasRole('admin');
    }

    /**
     * Determine if the action is authorized to run for the given request.
     */
    public function authorizedToRun(\Illuminate\Http\Request $request, $model)
    {
        return method_exists($request->user(), 'hasRole') && 
               $request->user()->hasRole('admin') &&
               $model->status === 'รออนุมัติ';
    }
}
