<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ScheduleAvailability extends Model
{
    use HasUuids;

    protected $fillable = [
        'schedule_period_id',
        'service_session_id',
        'user_id',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function period()
    {
        return $this->belongsTo(SchedulePeriod::class);
    }

    public function session()
    {
        return $this->belongsTo(ServiceSession::class, 'service_session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
