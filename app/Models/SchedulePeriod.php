<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SchedulePeriod extends Model
{
    use HasUuids;

    protected $fillable = [
        'department_id',
        'month',
        'year',
        'max_service_per_week',
        'status',
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

    public function userStatues()
    {
        return $this->hasMany(ScheduleUserPeriodStatus::class);
    }
}
