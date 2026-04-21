<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ServiceSession extends Model
{
    use HasUuids;

    protected $fillable = [
        'schedule_period_id',
        'service_date',
        'week_number',
        'session_number',
        'start_time',
        'end_time',
    ];

    public function schedulePeriod()
    {
        return $this->belongsTo(SchedulePeriod::class, 'schedule_period_id');
    }

    public function requirements()
    {
        return $this->hasMany(ServiceRequirement::class);
    }

    public function assignments()
    {
        return $this->hasMany(ScheduleAssignment::class);
    }
}
