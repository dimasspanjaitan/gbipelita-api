<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class Module extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'name',
        'order',
        'slug',
    ];

    protected $casts = [
        'name' => 'string',
        'slug' => 'string',
    ];

    public static function booted(): void
    {
        static::creating(function ($module) {
            if (empty($module->slug)) {
                $module->slug = Str::slug($module->name);
            }
        });
    }

    public function actions()
    {
        return $this->belongsToMany(Action::class, 'module_action', 'module_id', 'action_id');
    }

    public static function rules(?string $ignoreId = null): array
    {
        $ignoreId = $ignoreId ?: null;
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('modules', 'name')->ignore($ignoreId),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('modules', 'slug')->ignore($ignoreId),
            ],
            'order' => ['required', 'integer'],
            'actions' => ['required', 'array'],
            'actions.*' => ['uuid', 'exists:actions,id'],
        ];
    }

    public const MESSAGES = [
        'name.required' => 'Module name is required.',
        'name.unique' => 'Module name has already been used.',
        'slug.unique' => 'Module slug has already been used.',
        'order.required' => 'Order is required.',
        'actions.*.uuid' => 'Invalid action ID format.',
        'actions.*.exists' => 'One or more actions not found.',
    ];
}
