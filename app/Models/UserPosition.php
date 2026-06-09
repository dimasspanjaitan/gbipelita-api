<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPosition extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $table = 'user_positions';

    protected $fillable = [
        'user_id',
        'role_id',
        'department_id',
        'division_id',
    ];

    protected $appends = [
        'display_name'
    ];

    public function getDisplayNameAttribute(): string
    {
        if ($this->department_id) {
            return "{$this->role->name} {$this->department->name}";
        }

        return "{$this->role->name} {$this->division->name}";
    }

    /**
     * User yang memiliki posisi ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Role/jabatan yang diassign.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Department yang dipimpin.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Division yang dipimpin.
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function managedDepartmentIds(): array
    {
        return $this->positions()
            ->whereNotNull('department_id')
            ->pluck('department_id')
            ->unique()
            ->values()
            ->all();
    }

    public function managedDivisionIds(): array
    {
        return $this->positions()
            ->whereNotNull('division_id')
            ->pluck('division_id')
            ->unique()
            ->values()
            ->all();
    }

    /** ────────────────────────────────
     *  VALIDATION RULES
     *  ──────────────────────────────── */
    public static function rules(): array
    {
        return [
            'user_id' => ['required', 'uuid', 'exists:users,id'],
            'role_id' => ['required', 'uuid', 'exists:roles,id'],
            'department_id' => [
                'nullable',
                'uuid',
                'exists:departments,id',
                'required_without:division_id',
                'prohibited_with:division_id',
            ],

            'division_id' => [
                'nullable',
                'uuid',
                'exists:divisions,id',
                'required_without:department_id',
                'prohibited_with:department_id',
            ],
        ];
    }

    public const MESSAGES = [
        'user_id.required' => 'User is required.',
        'user_id.uuid' => 'User ID must be a valid UUID.',
        'user_id.exists' => 'Selected user does not exist.',
        'role_id.required' => 'Role is required.',
        'role_id.uuid' => 'Role ID must be a valid UUID.',
        'role_id.exists' => 'Selected role does not exist.',
        'department_id.uuid' => 'Department ID must be a valid UUID.',
        'department_id.exists' => 'Selected department does not exist.',
        'division_id.uuid' => 'Division ID must be a valid UUID.',
        'division_id.exists' => 'Selected division does not exist.',
    ];
}
