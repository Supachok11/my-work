<?php

namespace App\Nova;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Contracts\Database\Eloquent\Builder;

class TimeLog extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\TimeLog>
     */
    public static $model = \App\Models\TimeLog::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'work_date';

    /**
     * The subtitle that should be displayed for the resource.
     *
     * @var string
     */
    public static $subtitle = 'user.name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'work_date', 'user.name'
    ];

    /**
     * Default ordering for the resource.
     *
     * @var array
     */
    public static $orderBy = ['work_date' => 'desc'];

    /**
     * Get the displayable label of the resource.
     */
    public static function label(): string
    {
        return 'ประวัติลงเวลา';
    }

    /**
     * Get the displayable singular label of the resource.
     */
    public static function singularLabel(): string
    {
        return 'ลงเวลาทำงาน';
    }

    /**
     * Build an "index" query for the given resource.
     */
    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        // แสดงเฉพาะข้อมูลของ user ที่ login
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

            BelongsTo::make('พนักงาน', 'user', \App\Nova\User::class)
                ->hideFromIndex()
                ->readonly()
                ->fillUsing(function ($request, $model, $attribute) {
                    $model->{$attribute} = $request->user()->id;
                }),

            Date::make('วันที่ทำงาน', 'work_date')
                ->sortable()
                ->rules('required', 'unique:time_logs,work_date,{{resourceId}},id,user_id,' . $request->user()->id)
                ->default(today())
                ->help('ระบุวันที่ทำงาน (ไม่สามารถลงเวลาซ้ำในวันเดียวกันได้)'),

            DateTime::make('เวลาเข้างาน', 'clock_in')
                ->sortable()
                ->nullable()
                ->rules('nullable', 'date')
                ->help('ระบุเวลาที่เข้างาน'),

            DateTime::make('เวลาออกงาน', 'clock_out')
                ->sortable()
                ->nullable()
                ->rules('nullable', 'date', 'after:clock_in')
                ->help('ระบุเวลาที่ออกงาน (ต้องหลังจากเวลาเข้างาน)'),

            Text::make('ชั่วโมงทำงาน', function () {
                if ($this->clock_in && $this->clock_out) {
                    return $this->formatted_work_hours . ' ชั่วโมง';
                }
                return '-';
            })->onlyOnIndex(),

            Text::make('สถานที่เข้างาน', 'clock_in_location')
                ->hideFromIndex()
                ->nullable()
                ->help('พิกัด GPS สถานที่เข้างาน'),

            Text::make('สถานที่ออกงาน', 'clock_out_location')
                ->hideFromIndex()
                ->nullable()
                ->help('พิกัด GPS สถานที่ออกงาน'),

            Image::make('ภาพเข้างาน', 'clock_in_photo')
                ->disk('public')
                ->path('time-attendance')
                ->nullable()
                ->hideFromIndex()
                ->help('รูปภาพขณะลงเวลาเข้างาน (อนาคต)'),

            Image::make('ภาพออกงาน', 'clock_out_photo')
                ->disk('public')
                ->path('time-attendance')
                ->nullable()
                ->hideFromIndex()
                ->help('รูปภาพขณะลงเวลาออกงาน (อนาคต)'),

            Badge::make('สถานะ', 'status')
                ->map([
                    'present' => 'success',
                    'late' => 'warning',
                    'absent' => 'danger',
                    'half_day' => 'info'
                ])
                ->displayUsing(function ($value) {
                    $labels = [
                        'present' => 'มาทำงาน',
                        'late' => 'มาสาย',
                        'absent' => 'ขาดงาน',
                        'half_day' => 'ลาครึ่งวัน'
                    ];
                    return $labels[$value] ?? $value;
                })
                ->sortable()
                ->readonly()
                ->exceptOnForms(),

            Text::make('ชั่วโมงทำงาน', 'work_hours')
                ->displayUsing(function ($value) {
                    $hours = floor($value / 60);
                    $minutes = $value % 60;
                    return sprintf('%d:%02d ชั่วโมง', $hours, $minutes);
                })
                ->hideFromIndex()
                ->readonly()
                ->onlyOnDetail(),

            Boolean::make('ทำงานล่วงเวลา', 'is_overtime')
                ->hideFromIndex()
                ->readonly()
                ->onlyOnDetail(),

            Text::make('ล่วงเวลา', 'overtime_minutes')
                ->displayUsing(function ($value) {
                    if ($value == 0) return 'ไม่มี';
                    $hours = floor($value / 60);
                    $minutes = $value % 60;
                    return sprintf('%d:%02d ชั่วโมง', $hours, $minutes);
                })
                ->hideFromIndex()
                ->readonly()
                ->onlyOnDetail(),

            Textarea::make('บันทึกเพิ่มเติม', 'notes')
                ->rows(3)
                ->nullable()
                ->hideFromIndex()
                ->help('บันทึกเพิ่มเติม เช่น เหตุผลที่มาสาย หรือรายละเอียดการทำงาน'),

            DateTime::make('สร้างเมื่อ', 'created_at')
                ->onlyOnDetail()
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
        return [];
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
