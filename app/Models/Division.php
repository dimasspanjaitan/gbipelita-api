<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'name',
        'alias',
        'department_id',
        'status',
        'content',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_division', 'division_id', 'user_id')
            ->withPivot('priority');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public static function rules(?string $ignoreId = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                "unique:divisions,name,{$ignoreId},id,department_id," . request('department_id'),
            ],
            'alias' => ['nullable', 'string', 'max:50'],
            'department_id' => ['required', 'uuid', 'exists:departments,id'],
            'content' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    public const MESSAGES = [
        'name.required' => 'Division name is required.',
        'name.unique' => 'Division name has already been used in this department.',
        'name.string' => 'Division name must be a text string.',
        'department_id.required' => 'Department is required.',
        'department_id.uuid' => 'Department ID must be a valid UUID.',
        'department_id.exists' => 'Selected department does not exist.',
    ];
}
