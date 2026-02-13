<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ScheduleAssignment extends Model
{
    use HasUuids;

    protected $fillable = [
        'schedule_period_id',
        'service_session_id',
        'service_requirement_id',
        'user_id',
        'is_system_generated',
    ];

    protected $casts = [
        'is_system_generated' => 'boolean'
    ];

    public function period()
    {
        return $this->belongsTo(SchedulePeriod::class);
    }

    public function session()
    {
        return $this->belongsTo(ServiceSession::class, 'service_session_id');
    }

    public function requirement()
    {
        return $this->belongsTo(ServiceRequirement::class, 'service_requirement_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
