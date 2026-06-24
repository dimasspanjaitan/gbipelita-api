<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Department extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'name',
        'alias',
        'status',
        'content',
    ];

    public function divisions()
    {
        return $this->hasMany(Division::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_department', 'department_id', 'user_id');
    }

    public function getDivisionCountAttribute(): int
    {
        return $this->relationsLoaded('divisions')
            ? $this->divisions->count()
            : $this->divisions()->count();
    }

    public static function rules(?string $ignoreId = null): array
    {
        $ignoreId = $ignoreId ?: null;
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departments', 'name')->ignore($ignoreId)
            ],
            'alias' => ['nullable', 'string', 'max:50'],
            'content' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    public const MESSAGES = [
        'name.required' => 'Department name is required.',
        'name.unique' => 'Department name has already been used.',
        'name.string' => 'Department name must be a text string.',
    ];
}
