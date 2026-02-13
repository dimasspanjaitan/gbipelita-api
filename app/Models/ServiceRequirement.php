<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ServiceRequirement extends Model
{
    use HasUuids;

    protected $fillable = [
        'service_session_id',
        'division_id',
        'skill_id',
        'required_qty',
    ];

    public function session()
    {
        return $this->belongsTo(ServiceSession::class, 'service_session_id');
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function assignments()
    {
        return $this->hasMany(ScheduleAssignment::class);
    }
}
