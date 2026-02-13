<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ScheduleUserPeriodStatus extends Model
{
    use HasUuids;

    protected $fillable = [
        'schedule_period_id',
        'user_id',
        'has_submitted',
    ];

    protected $casts = [
        'has_submitted' => 'boolean'
    ];

    public function period()
    {
        return $this->belongsTo(SchedulePeriod::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
