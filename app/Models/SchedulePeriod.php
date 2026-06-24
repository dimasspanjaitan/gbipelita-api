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
        'status',
        'submitted_count',
        'not_submitted_count',
    ];

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
    ];
}
