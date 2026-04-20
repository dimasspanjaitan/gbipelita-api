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

    public static function rules(?string $ignoreId = null): array
    {
        return [
            'schedule_period_id' => ['required', 'uuid', 'exists:schedule_periods,id'],
            'service_session_id' => ['required', 'uuid', 'exists:service_sessions,id'],
            'user_id' => ['required', 'uuid', 'exists:users,id'],
            'is_available' => ['boolean'],
        ];
    }

    public const MESSAGES = [
        'schedule_period_id.required' => 'Schedule period is required.',
        'schedule_period_id.uuid' => 'Schedule period ID must be a valid UUID.',
        'schedule_period_id.exists' => 'The selected schedule period is invalid.',

        'service_session_id.required' => 'Service session is required.',
        'service_session_id.uuid' => 'Service session ID must be a valid UUID.',
        'service_session_id.exists' => 'The selected service session is invalid.',

        'user_id.required' => 'User is required.',
        'user_id.uuid' => 'User ID must be a valid UUID.',
        'user_id.exists' => 'The selected user is invalid.',

        'is_available.boolean' => 'Availability must be true or false.',
    ];
}
