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
            return "Kepala Departemen {$this->department->name}";
        }

        return "Ketua Divisi {$this->division->name} Division";
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

    public static function positionExists(
        array $data,
        ?string $ignoreId = null
    ): bool {
        $query = static::query()
            ->where('role_id', $data['role_id']);

        if ($ignoreId) {
            $query->whereKeyNot($ignoreId);
        }

        if (!empty($data['department_id'])) {
            $query->where('department_id', $data['department_id']);
        }

        if (!empty($data['division_id'])) {
            $query->where('division_id', $data['division_id']);
        }

        return $query->exists();
    }

    /** ────────────────────────────────
     *  VALIDATION RULES
     *  ──────────────────────────────── */
    public static function rules(): array
    {
        return [
            'user_id' => ['bail', 'required', 'uuid', 'exists:users,id'],
            'role_id' => ['bail', 'required', 'uuid', 'exists:roles,id'],
            'department_id' => [
                'nullable',
                'uuid',
                'exists:departments,id',
                'required_without:division_id',
                'prohibits:division_id',
            ],

            'division_id' => [
                'nullable',
                'uuid',
                'exists:divisions,id',
                'required_without:department_id',
                'prohibits:department_id',
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
        'department_id.required_without' => 'Department is required when division is not selected.',
        'department_id.prohibits' => 'Department and division cannot be selected together.',
        'division_id.uuid' => 'Division ID must be a valid UUID.',
        'division_id.exists' => 'Selected division does not exist.',
        'division_id.required_without' => 'Division is required when department is not selected.',
        'division_id.prohibits' => 'Division and department cannot be selected together.',
    ];
}
