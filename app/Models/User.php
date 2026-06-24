<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Trashable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasUuids, HasFactory, Notifiable, HasRoles, SoftDeletes, Trashable;

    protected $guard_name = 'api';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'nickname',
        'email',
        'password',
        'status',
        'photo',
        'address',
        'phone',
        'birth_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = ["full_name"];

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = !empty($value) ? $value : null;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /** ────────────────────────────────
     *  RELATIONSHIPS
     *  ──────────────────────────────── */
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'user_department', 'user_id', 'department_id');
    }

    public function divisions()
    {
        return $this->belongsToMany(Division::class, 'user_division', 'user_id', 'division_id')
            ->withPivot('priority');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
            ->withPivot(['is_primary', 'order'])
            ->withTimestamps();
    }

    public function userSkills()
    {
        return $this->hasMany(UserSkill::class);
    }

    public function availabilities()
    {
        return $this->hasMany(ScheduleAvailability::class);
    }

    public function schedulePeriodStatuses()
    {
        return $this->hasMany(ScheduleUserPeriodStatus::class);
    }

    public function assignments()
    {
        return $this->hasMany(ScheduleAssignment::class);
    }

    public function positions()
    {
        return $this->hasMany(UserPosition::class);
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
    public static function rules(?string $ignoreId = null): array
    {
        $ignoreId = $ignoreId ?: null;
        return [
            'username' => [
                $ignoreId ? 'sometimes' : 'required',
                'string',
                'max:50',
                Rule::unique('users', 'username')->ignore($ignoreId),
            ],
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'nickname' => [
                $ignoreId ? 'sometimes' : 'required',
                'string',
                'max:50',
                Rule::unique('users', 'nickname')->ignore($ignoreId),
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($ignoreId),
            ],
            'password' => [$ignoreId ? 'sometimes' : 'required', 'string', 'min:8', 'confirmed'],
            'status' => [$ignoreId ? 'sometimes' : 'required', 'in:active,inactive'],
            'roles' => [$ignoreId ? 'sometimes' : 'required', 'array'],
            'roles.*' => 'uuid|exists:roles,id',
            'departments' => ['sometimes', 'array'],
            'departments.*' => ['required', 'string', 'uuid', 'exists:departments,id'],
            'divisions' => ['sometimes', 'array'],
            'divisions.*' => ['required', 'string', 'uuid', 'exists:divisions,id'],
            'skills' => ['array'],
            'skills.*.skill_id' => ['required', 'uuid', 'exists:skills,id'],
            'skills.*.is_primary' => ['boolean'],
            'skills.*.order' => ['integer'],
            'photo' => ['nullable'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'birth_date' => ['nullable', 'string'],
        ];
    }

    public const MESSAGES = [
        'username.required' => 'Username is required.',
        'username.unique' => 'Username has already been used.',
        'nickname.required' => 'Nickname is required.',
        'nickname.unique' => 'Nickname has already been used.',
        'email.email' => 'Invalid email format.',
        'email.unique' => 'Email has already been used.',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 8 characters.',
        'password.confirmed' => 'Password confirmation does not match.',
        'roles.required' => 'Role is required.',
        'roles.*.uuid' => 'Invalid role ID format.',
        'roles.*.exists' => 'One or more roles not found.',
        'departments.*.uuid' => 'Department ID must be a valid UUID.',
        'departments.*.exists' => 'Selected department does not exist.',
        'divisions.*.uuid' => 'Division ID must be a valid UUID.',
        'divisions.*.exists' => 'Selected division does not exist.',
    ];
}
