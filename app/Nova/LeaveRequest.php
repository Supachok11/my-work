<?php

namespace App\Nova;

use App\Mail\LeaveRequestNotification;
use App\Nova\Actions\ApproveLeaveRequest;
use App\Nova\Actions\RejectLeaveRequest;
use App\Nova\Metrics\LeaveRequestMetric;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class LeaveRequest extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\LeaveRequest>
     */
    public static $model = \App\Models\LeaveRequest::class;

    /**
     * The single value used to represent the resource.
     */
    public static $title = 'leave_type';

    public static function label()
    {
        return 'คำขอลา';
    }


    /**
     * Columns that should be searched.
     */
    public static $search = [
        'id',
        'leave_type',
        'status',
        'additional_info',
        'user.name', // enable search by user name for admin
    ];

    /**
     * admins see all; others see only their own. Also eager-load and sort.
     */
    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        $query->with('user')->orderByDesc('created_at');

        if (method_exists($request->user(), 'hasRole') && $request->user()->hasRole('admin')) {
            return $query;
        }

        return $query->where('user_id', $request->user()->id);
    }

    /**
     * Detail query mirrors index scoping.
     */
    public static function detailQuery(NovaRequest $request, $query): Builder
    {
        if (method_exists($request->user(), 'hasRole') && $request->user()->hasRole('admin')) {
            return $query->with('user');
        }

        return $query->where('user_id', $request->user()->id)->with('user');
    }

    /**
     * Subtitle shown under the title in resource cards.
     */
    public function subtitle(): ?string
    {
        return optional($this->created_at)->format('d/m/Y H:i');
    }

    /**
     * Fields for the resource.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('พนักงาน', 'user.name')
                ->onlyOnIndex()
                ->sortable()
                ->canSee(function ($request) {
                    return method_exists($request->user(), 'hasRole') && $request->user()->hasRole('admin');
                }),

            BelongsTo::make('พนักงาน', 'user', \App\Nova\User::class)
                ->hideFromIndex()
                ->searchable()
                ->hideWhenCreating()
                ->readonly()
                ->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                    // Ensure the creator is always the authenticated user
                    if (!$model->exists) {
                        $model->{$attribute} = $request->user()->id;
                    }
                }),

            Select::make('ประเภทการลา', 'leave_type')
                ->options([
                    'ลากิจ' => 'ลากิจ',
                    'ลาป่วย' => 'ลาป่วย',
                ])
                ->default('ลากิจ')
                ->displayUsingLabels()
                ->rules('required'),

            Select::make('ระยะเวลา', 'duration_type')
                ->options([
                    'ทั้งวัน' => 'ทั้งวัน',
                    'ชั่วโมง' => 'ชั่วโมง',
                ])
                ->default('ทั้งวัน')
                ->displayUsingLabels()
                ->rules('required'),

            Boolean::make('ลาหลายวัน', 'is_range')
                ->hideFromIndex()
                ->default(false),

            Date::make('วันที่ลา', 'leave_date')
                ->sortable()
                ->default(now())
                ->dependsOn(['is_range'], function (Date $field, NovaRequest $request, $formData) {
                    if ($formData['is_range'] ?? false) {
                        $field->hide()->rules('nullable');
                    } else {
                        $field->show()
                            ->creationRules('required', 'after_or_equal:today')
                            ->updateRules('required'); // allow past when editing
                    }
                })
                ->rules('required_unless:is_range,true'),

            Text::make('ระยะเวลาลา', function () {
                if ($this->is_range && $this->range_start_date && $this->range_end_date) {
                    $start = \Carbon\Carbon::parse($this->range_start_date);
                    $end   = \Carbon\Carbon::parse($this->range_end_date);
                    $days  = $start->diffInDays($end) + 1;

                    return sprintf(
                        '<span class="text-blue-600 font-semibold">%d วัน</span><br><small class="text-gray-500">%s - %s</small>',
                        $days,
                        $start->format('d/m/Y'),
                        $end->format('d/m/Y')
                    );
                }

                if ($this->duration_type === 'ชั่วโมง' && $this->start_time && $this->end_time) {
                    $start = \Carbon\Carbon::parse($this->start_time);
                    $end   = \Carbon\Carbon::parse($this->end_time);

                    $totalMinutes = max(0, $start->diffInMinutes($end, false));
                    $hours   = intdiv($totalMinutes, 60);
                    $minutes = $totalMinutes % 60;

                    $durationText = $hours > 0
                        ? $hours . ' ชั่วโมง' . ($minutes > 0 ? ' ' . $minutes . ' นาที' : '')
                        : $minutes . ' นาที';

                    $timeRange = $start->format('H:i') . ' - ' . $end->format('H:i');

                    return '<span class="font-semibold">' . $durationText . '</span><br><small class="text-gray-500">' . $timeRange . '</small>';
                }

                if ($this->duration_type === 'ทั้งวัน') {
                    $date = $this->leave_date ? \Carbon\Carbon::parse($this->leave_date)->format('d/m/Y') : '-';
                    return '<span class="font-semibold">ทั้งวัน</span><br><small class="text-gray-500">' . $date . '</small>';
                }

                return e($this->duration_type ?? '-');
            })->onlyOnIndex()->asHtml(),

            DateTime::make('เวลาเริ่ม', 'start_time')
                ->hideFromIndex()
                ->nullable()
                ->displayUsing(function ($value) {
                    return $value ? \Carbon\Carbon::parse($value)->format('H:i') : null;
                })
                ->dependsOn(['duration_type'], function (DateTime $field, NovaRequest $request, $formData) {
                    if (($formData['duration_type'] ?? null) === 'ชั่วโมง') {
                        $field->show()
                            ->rules('required_if:duration_type,ชั่วโมง');
                    } else {
                        $field->hide()->rules('prohibited_unless:duration_type,ชั่วโมง');
                    }
                })
                ->help('ระบุเวลาเริ่มต้นสำหรับการลาแบบชั่วโมง'),

            DateTime::make('เวลาสิ้นสุด', 'end_time')
                ->hideFromIndex()
                ->nullable()
                ->displayUsing(function ($value) {
                    return $value ? \Carbon\Carbon::parse($value)->format('H:i') : null;
                })
                ->dependsOn(['duration_type', 'start_time'], function (DateTime $field, NovaRequest $request, $formData) {
                    if (($formData['duration_type'] ?? null) === 'ชั่วโมง') {
                        $field->show()
                            ->rules('required_if:duration_type,ชั่วโมง', 'after:start_time');
                    } else {
                        $field->hide()->rules('prohibited_unless:duration_type,ชั่วโมง');
                    }
                })
                ->help('ระบุเวลาสิ้นสุดสำหรับการลาแบบชั่วโมง'),

            Date::make('วันเริ่มต้น', 'range_start_date')
                ->hideFromIndex()
                ->nullable()
                ->dependsOn(['is_range'], function (Date $field, NovaRequest $request, $formData) {
                    if ($formData['is_range'] ?? false) {
                        $field->show()
                            ->creationRules('required', 'after_or_equal:today')
                            ->updateRules('required');
                    } else {
                        $field->hide();
                    }
                })
                ->rules('required_if:is_range,true')
                ->help('วันที่เริ่มต้นสำหรับการลาหลายวัน'),

            Date::make('วันสิ้นสุด', 'range_end_date')
                ->hideFromIndex()
                ->nullable()
                ->dependsOn(['is_range', 'range_start_date'], function (Date $field, NovaRequest $request, $formData) {
                    if ($formData['is_range'] ?? false) {
                        $rules = ['required_if:is_range,true'];
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
                ->rules('nullable', 'file', 'max:2048', 'mimes:pdf,jpg,jpeg,png,doc,docx')
                ->storeAs(function (\Laravel\Nova\Http\Requests\NovaRequest $request, $model) {
                    $file = $request->file('attachment_path');
                    $ext = $file ? $file->getClientOriginalExtension() : null;
                    return 'leave_' . $request->user()->id . '_' . now()->format('Ymd_His') . ($ext ? ('.' . $ext) : '');
                })
                ->help('อัพโหลดไฟล์หลักฐาน (PDF, รูปภาพ, Word) ขนาดไม่เกิน 2MB'),

            Badge::make('สถานะ', 'status')
                ->map([
                    'รออนุมัติ' => 'warning',
                    'อนุมัติ' => 'success',
                    'ไม่อนุมัติ' => 'danger',
                ])
                ->sortable()
                ->readonly()
                ->exceptOnForms(),

            DateTime::make('วันที่อนุมัติ', 'approved_at')
                ->onlyOnDetail()
                ->nullable()
                ->displayUsing(function ($value) {
                    return $value ? \Carbon\Carbon::parse($value)->format('d/m/Y H:i') : null;
                }),

            DateTime::make('วันที่ไม่อนุมัติ', 'rejected_at')
                ->onlyOnDetail()
                ->nullable()
                ->displayUsing(function ($value) {
                    return $value ? \Carbon\Carbon::parse($value)->format('d/m/Y H:i') : null;
                }),

            Textarea::make('เหตุผลการไม่อนุมัติ', 'rejection_reason')
                ->onlyOnDetail()
                ->nullable()
                ->readonly(),

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

    // Limit selectable users in the BelongsTo field (defense in depth)
    public static function relatableUsers(NovaRequest $request, $query): \Illuminate\Database\Eloquent\Builder
    {
        if (method_exists($request->user(), 'hasRole') && $request->user()->hasRole('admin')) {
            return $query; // admins can pick anyone
        }
        return $query->where('id', $request->user()->id); // non-admins: only themselves
    }

    /**
     * Cards
     */
    public function cards(NovaRequest $request): array
    {
        return [
            new LeaveRequestMetric(),
        ];
    }

    /**
     * Filters
     */
    public function filters(NovaRequest $request): array
    {
        return [
            new \App\Nova\Filters\StatusFilter(),
        ];
    }

    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    public function actions(NovaRequest $request): array
    {
        return [
            new ApproveLeaveRequest(),
            new RejectLeaveRequest(),
        ];
    }

    /**
     * After create hook – send notification email.
     */
    public static function afterCreate(NovaRequest $request, $model): void
    {
        // Refresh to ensure relations are available
        $model->refresh();

        try {
            $recipients = array_filter((array) config('leave.admin_emails', [env('LEAVE_ADMIN_EMAIL'), 'outhailnw@gmail.com']));
            Mail::to($recipients)->send(new LeaveRequestNotification($model));
        } catch (\Throwable $e) {
            Log::error('Failed to send leave request notification email: ' . $e->getMessage());
        }
    }
}
