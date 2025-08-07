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
    public static $title = 'leave_type';

    /**
     * The subtitle that should be displayed for the resource.
     *
     * @var string
     */
    public static $subtitle = 'created_at';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'leave_type',
        'status',
        'additional_info',
        'user.name'
    ];

    /**
     * Default ordering for the resource.
     *
     * @var array
     */
    public static $orderBy = ['created_at' => 'desc'];

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

            // Text::make('พนักงาน', function () {
            //     return $this->user ? $this->user->name : 'ไม่ระบุ';
            // })->onlyOnIndex()
            //     ->sortable(),

            // BelongsTo::make('พนักงาน', 'user', User::class)
            //     ->hideFromIndex()
            //     ->searchable()
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

            Boolean::make('ลาหลายวัน', 'is_range')
                ->hideFromIndex()
                ->default(false),

            Date::make('วันที่ลา', 'leave_date')
                ->sortable()
                ->dependsOn(['is_range'], function (Date $field, NovaRequest $request, $formData) {
                    if ($formData['is_range']) {
                        $field->hide()->rules('nullable');
                    } else {
                        $field->show()->rules('required', 'after_or_equal:today');
                    }
                }),

            Text::make('ระยะเวลาลา', function () {
                if ($this->is_range && $this->range_start_date && $this->range_end_date) {
                    $start = \Carbon\Carbon::parse($this->range_start_date);
                    $end = \Carbon\Carbon::parse($this->range_end_date);
                    $days = $start->diffInDays($end) + 1;
                    return '<span class="text-blue-600 font-semibold">' . $days . ' วัน</span><br><small class="text-gray-500">' . $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y') . '</small>';
                }

                if ($this->duration_type === 'ชั่วโมง' && $this->start_time && $this->end_time) {
                    $start = \Carbon\Carbon::parse($this->start_time);
                    $end = \Carbon\Carbon::parse($this->end_time);
                    
                    // คำนวณชั่วโมงและนาที
                    $totalMinutes = $start->diffInMinutes($end);
                    $hours = floor($totalMinutes / 60);
                    $minutes = $totalMinutes % 60;
                    
                    // สร้างข้อความแสดงผล
                    $durationText = '';
                    if ($hours > 0) {
                        $durationText .= $hours . ' ชั่วโมง';
                        if ($minutes > 0) {
                            $durationText .= ' ' . $minutes . ' นาที';
                        }
                    } else {
                        $durationText = $minutes . ' นาที';
                    }
                    
                    $timeRange = $start->format('H:i') . ' - ' . $end->format('H:i');
                    return '<span class="text-green-600 font-semibold">' . $durationText . '</span><br><small class="text-gray-500">' . $timeRange . '</small>';
                }

                if ($this->duration_type === 'ทั้งวัน') {
                    $date = $this->leave_date ? \Carbon\Carbon::parse($this->leave_date)->format('d/m/Y') : '-';
                    return '<span class="text-purple-600 font-semibold">ทั้งวัน</span><br><small class="text-gray-500">' . $date . '</small>';
                }

                return $this->duration_type ?? '-';
            })->onlyOnIndex()
                ->asHtml(),

            DateTime::make('เวลาเริ่ม', 'start_time')
                ->hideFromIndex()
                ->nullable()
                ->displayUsing(function ($value) {
                    return $value ? \Carbon\Carbon::parse($value)->format('H:i') : null;
                })
                ->dependsOn(['duration_type'], function (DateTime $field, NovaRequest $request, $formData) {
                    if ($formData['duration_type'] === 'ชั่วโมง') {
                        $field->show()->rules('required');
                    } else {
                        $field->hide();
                    }
                })
                ->help('ระบุเวลาเริ่มต้นสำหรับการลาแบบชั่วโมง'),

            DateTime::make('เวลาสิ้นสุด', 'end_time')
                ->hideFromIndex()
                ->nullable()
                ->displayUsing(function ($value) {
                    return $value ? \Carbon\Carbon::parse($value)->format('H:i') : null;
                })
                ->dependsOn(['duration_type'], function (DateTime $field, NovaRequest $request, $formData) {
                    if ($formData['duration_type'] === 'ชั่วโมง') {
                        $field->show()->rules('required', 'after:start_time');
                    } else {
                        $field->hide();
                    }
                })
                ->help('ระบุเวลาสิ้นสุดสำหรับการลาแบบชั่วโมง'),

            Date::make('วันเริ่มต้น', 'range_start_date')
                ->hideFromIndex()
                ->nullable()
                ->dependsOn(['is_range'], function (Date $field, NovaRequest $request, $formData) {
                    if ($formData['is_range']) {
                        $field->show()->rules('required', 'after_or_equal:today');
                    } else {
                        $field->hide();
                    }
                })
                ->help('วันที่เริ่มต้นสำหรับการลาหลายวัน (จะใช้เป็นวันที่ลาหลักด้วย)'),

            Date::make('วันสิ้นสุด', 'range_end_date')
                ->hideFromIndex()
                ->nullable()
                ->dependsOn(['is_range', 'range_start_date'], function (Date $field, NovaRequest $request, $formData) {
                    if ($formData['is_range']) {
                        $rules = ['required'];
                        if (!empty($formData['range_start_date'])) {
                            $rules[] = 'after_or_equal:' . $formData['range_start_date'];
                        }
                        $field->show()->rules($rules);
                    } else {
                        $field->hide();
                    }
                })
                ->help('วันที่สิ้นสุดสำหรับการลาหลายวัน (ต้องไม่ก่อนวันที่เริ่มต้น)'),

            Textarea::make('ข้อมูลเพิ่มเติม', 'additional_info')
                ->rows(4)
                ->hideFromIndex()
                ->rules('required', 'max:500')
                ->placeholder('โปรดระบุเหตุผลการลาอย่างชัดเจน...'),

            File::make('ไฟล์แนบ', 'attachment_path')
                ->disk('public')
                ->path('attachments')
                ->prunable()
                ->hideFromIndex()
                ->nullable()
                ->acceptedTypes('.pdf,.jpg,.jpeg,.png,.doc,.docx')
                ->help('อัพโหลดไฟล์หลักฐาน (PDF, รูปภาพ, Word) ขนาดไม่เกิน 2MB'),

            Badge::make('สถานะ', 'status')->map([
                'รออนุมัติ' => 'warning',
                'อนุมัติ' => 'success',
                'ไม่อนุมัติ' => 'danger',
            ])->sortable()
                ->readonly()
                ->hideWhenCreating()
                ->exceptOnForms(),


            DateTime::make('วันที่อนุมัติ', 'approved_at')
                ->onlyOnDetail()
                ->nullable()
                ->displayUsing(function ($value) {
                    return $value ? \Carbon\Carbon::parse($value)->format('d/m/Y H:i') : null;
                })
                ->hideFromIndex(),

            DateTime::make('วันที่ไม่อนุมัติ', 'rejected_at')
                ->onlyOnDetail()
                ->nullable()
                ->displayUsing(function ($value) {
                    return $value ? \Carbon\Carbon::parse($value)->format('d/m/Y H:i') : null;
                })
                ->hideFromIndex(),

            DateTime::make('สร้างเมื่อ', 'created_at')
                ->onlyOnDetail()
                ->sortable()
                ->displayUsing(function ($value) {
                    return $value ? \Carbon\Carbon::parse($value)->format('d/m/Y H:i') : null;
                }),

            DateTime::make('อัปเดตเมื่อ', 'updated_at')
                ->onlyOnDetail()
                ->displayUsing(function ($value) {
                    return $value ? \Carbon\Carbon::parse($value)->format('d/m/Y H:i') : null;
                }),
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
