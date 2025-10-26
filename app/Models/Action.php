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
        'module_id',
        'name',
        'label',
        'permission_name',
        'order',
        'is_default_action',
    ];

    protected $casts = [
        'is_default_action' => 'boolean',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public static function rules(?string $ignoreId = null): array
    {
        return [
            'module_id' => ['required', 'uuid', 'exists:modules,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('module_actions', 'name')
                    ->where(fn($q) => $q->where('module_id', request('module_id')))
                    ->ignore($ignoreId),
            ],
            'label' => [
                'required',
                'string',
                'max:255',
                Rule::unique('module_actions', 'label')
                    ->where(fn($q) => $q->where('module_id', request('module_id')))
                    ->ignore($ignoreId),
            ],
            'permission_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('module_actions', 'permission_name')->ignore($ignoreId),
            ],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_default_action' => ['nullable', 'boolean'],
        ];
    }

    public const MESSAGES = [
        'module_id.required' => 'Module is required.',
        'module_id.exists' => 'Module not found.',
        'name.required' => 'Action name is required.',
        'name.unique' => 'Action name has already been used in this module.',
        'label.required' => 'Action label is required.',
        'label.unique' => 'Action label has already been used in this module.',
        'permission_name.required' => 'Permission name is required.',
        'permission_name.unique' => 'Permission name has already been used.',
    ];
}
