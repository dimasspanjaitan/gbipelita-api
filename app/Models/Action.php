<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Action extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'name' => 'string'
    ];

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_action', 'action_id', 'module_id');
    }

    public static function rules(?string $ignoreId = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('actions', 'name')->ignore($ignoreId),
            ],
        ];
    }

    public const MESSAGES = [
        'name.required' => 'Action name is required.',
        'name.unique' => 'Action name has already been used.',
    ];
}
