<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchedulePeriod extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'department_id',
        'month',
        'year',
        'max_service_per_week',
        'max_overflow_per_week',
        'status',
        'submitted_count',
        'not_submitted_count',
        'data',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    public function __get($key)
    {
        // 1. Cek properti bawaan Eloquent dulu (kolom asli / relasi)
        $attribute = parent::getAttribute($key);

        if ($attribute !== null) {
            return $attribute;
        }

        // 2. Jika tidak ada di kolom asli, cari di dalam array 'data'
        $data = $this->getAttribute('data');
        if (is_array($data) && array_key_exists($key, $data)) {
            return $data[$key];
        }

        return null;
    }

    public function toArray(): array
    {
        $attributes = parent::toArray();

        unset($attributes['data']);

        return array_merge(
            $attributes,
            $this->data ?? []
        );
    }

    public function fill(array $attributes)
    {
        $columns = array_flip($this->getFillable());

        $fillable = [];
        $extra = [];

        foreach ($attributes as $key => $value) {
            if (isset($columns[$key]) && $key !== 'data') {
                $fillable[$key] = $value;
            } else {
                if ($key == 'id') continue;
                $extra[$key] = $value;
            }
        }

        $fillable['data'] = array_merge(
            $this->getAttribute('data') ?? [],
            $extra
        );

        return parent::fill($fillable);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function sessions()
    {
        return $this->hasMany(ServiceSession::class);
    }

    public function assignments()
    {
        return $this->hasMany(ScheduleAssignment::class);
    }

    public function userStatuses()
    {
        return $this->hasMany(ScheduleUserPeriodStatus::class);
    }

    public static function rules(): array
    {
        return [
            'department_id' => ['required', 'uuid', 'exists:departments,id'],
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'min:' . now()->year, 'max:' . (now()->year + 5)],
            'max_service_per_week' => ['required', 'integer', 'min:1'],
            'max_overflow_per_week' => [
                'required',
                'integer',
                'gte:max_service_per_week',
                function ($attribute, $value, $fail) {
                    $templates = request()->input('session_templates');

                    if (is_array($templates)) {
                        $maxLength = count($templates);

                        if ($value > $maxLength) {
                            $fail("The {$attribute} may not be greater than the total number of service templates ({$maxLength}).");
                        }
                    }
                }
            ],
            'session_templates' => ['required', 'array', 'min:1'],
            'session_templates.*.name' => ['required', 'string'],
            'session_templates.*.start' => ['required', 'date_format:H:i'],
            'session_templates.*.end' => ['required', 'date_format:H:i', 'after:session_templates.*.start'],
            'session_templates.*.start2' => ['nullable', 'date_format:H:i', 'after:session_templates.*.end'],
            'session_templates.*.end2' => ['nullable', 'date_format:H:i', 'after:session_templates.*.start2'],
            'session_templates.*.is_split_session' => ['nullable', 'boolean']
        ];
    }

    public const MESSAGES = [
        'department_id.required' => 'Department is required.',
        'department_id.uuid' => 'Department ID must be a valid UUID.',
        'department_id.exists' => 'The selected department is invalid.',
        'month.required' => 'Month is required.',
        'month.integer' => 'Month must be an integer.',
        'month.between' => 'Month must be between 1 and 12.',
        'year.required' => 'Year is required.',
        'year.integer' => 'Year must be an integer.',
        'year.min' => 'Year must be at least :min.',
        'year.max' => 'Year may not be greater than :max.',
        // Service/Overflow Per Week
        'max_service_per_week.required' => 'Max service per week is required.',
        'max_service_per_week.integer' => 'Max service per week must be an integer.',
        'max_service_per_week.min' => 'Max service per week must be at least :min.',
        'max_overflow_per_week.required' => 'Max overflow per week is required.',
        'max_overflow_per_week.integer' => 'Max overflow per week must be an integer.',
        'max_overflow_per_week.gte' => 'Max overflow per week must be greater than or equal to max service per week.',
        // Session Templates
        'session_templates.required' => 'Session templates are required.',
        'session_templates.array' => 'Session templates must be an array.',
        'session_templates.min' => 'You must provide at least :min session template.',
        'session_templates.*.name.required' => 'The template name is required.',
        'session_templates.*.name.string' => 'The template name must be a string.',
        'session_templates.*.start.required' => 'The start time is required.',
        'session_templates.*.start.date_format' => 'The start time must match the format HH:MM (e.g., 08:00).',
        'session_templates.*.end.required' => 'The end time is required.',
        'session_templates.*.end.date_format' => 'The end time must match the format HH:MM (e.g., 17:00).',
        'session_templates.*.end.after' => 'The end time must be a time after the start time.',
        'session_templates.*.start2.date_format' => 'The second start time must match the format HH:MM.',
        'session_templates.*.start2.after' => 'The second start time must be a time after the first end time.',
        'session_templates.*.end2.date_format' => 'The second end time must match the format HH:MM.',
        'session_templates.*.end2.after' => 'The second end time must be a time after the second start time.',
    ];
}
