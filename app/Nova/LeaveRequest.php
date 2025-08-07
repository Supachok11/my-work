<?php

namespace App\Nova;

use App\Models\User;
use App\Nova\Metrics\LeaveRequestMetric;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Contracts\Database\Eloquent\Builder;

class LeaveRequest extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\LeaveRequest>
     */
    public static $model = \App\Models\LeaveRequest::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'leave_type',
        'status',
        'additional_info'
    ];

    /**
     * Build an "index" query for the given resource.
     */
    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        // แสดงเฉพาะ leave requests ของ user ที่ login
        return $query->where('user_id', $request->user()->id);
    }

    /**
     * Build a "detail" query for the given resource.
     */
    public static function detailQuery(NovaRequest $request, $query): Builder
    {
        return $query->where('user_id', $request->user()->id);
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            // BelongsTo::make('พนักงาน', 'user', User::class)
            //     ->sortable()
            //     ->searchable()
            //     ->required()
            //     ->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
            //         $model->{$attribute} = $request->user()->id;
            //     }),

            Select::make('ประเภทการลา', 'leave_type')
                ->options([
                    'ลากิจ' => 'ลากิจ',
                    'ลาป่วย' => 'ลาป่วย',
                ])
                ->rules('required')
                ->displayUsingLabels(),

            Select::make('ระยะเวลา', 'duration_type')
                ->options([
                    'ทั้งวัน' => 'ทั้งวัน',
                    'ชั่วโมง' => 'ชั่วโมง',
                ])
                ->rules('required')
                ->displayUsingLabels(),

            Date::make('วันที่ลา', 'leave_date')
                ->rules('required')
                ->sortable(),

            DateTime::make('เวลาเริ่ม', 'start_time')
                ->hideFromIndex()
                ->nullable(),

            DateTime::make('เวลาสิ้นสุด', 'end_time')
                ->hideFromIndex()
                ->nullable(),

            Boolean::make('ลาหลายวัน', 'is_range')
                ->hideFromIndex(),

            Date::make('วันเริ่มต้น', 'range_start_date')
                ->hideFromIndex()
                ->nullable(),

            Date::make('วันสิ้นสุด', 'range_end_date')
                ->hideFromIndex()
                ->nullable(),

            Textarea::make('ข้อมูลเพิ่มเติม', 'additional_info')
                ->rules('required'),

            File::make('ไฟล์แนบ', 'attachment_path')
                ->disk('public')
                ->path('attachments')
                ->prunable()
                ->hideFromIndex(),

            Badge::make('สถานะ', 'status')->map([
                'รออนุมัติ' => 'warning',
                'อนุมัติ' => 'success',
                'ไม่อนุมัติ' => 'danger',
            ])->sortable()
                ->default('รออนุมัติ'),

            DateTime::make('วันที่อนุมัติ', 'approved_at')
                ->onlyOnDetail()
                ->nullable(),

            DateTime::make('วันที่ไม่อนุมัติ', 'rejected_at')
                ->onlyOnDetail()
                ->nullable(),

            DateTime::make('สร้างเมื่อ', 'created_at')
                ->onlyOnDetail()
                ->sortable(),

            DateTime::make('อัปเดตเมื่อ', 'updated_at')
                ->onlyOnDetail(),
        ];
    }

    /**
     * Get the cards available for the resource.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [
            new LeaveRequestMetric()
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
