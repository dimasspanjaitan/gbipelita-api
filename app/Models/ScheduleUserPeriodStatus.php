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
        'notes',
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

    public static function rules(): array
    {
        return [
            'session_ids' => ['array'],
            'session_ids.*' => ['uuid', 'exists:service_sessions,id'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public const MESSAGES = [
        'session_ids.*.uuid' => 'Service Session ID must be a valid UUID.',
        'session_ids.*.exists' => 'The selected service session is invalid.',
        'notes.string' => 'Year must be strings.',
    ];
}
