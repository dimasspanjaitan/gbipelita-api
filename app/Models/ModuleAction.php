<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class ModuleAction extends Model
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
        'module_id.required' => 'Module wajib diisi.',
        'module_id.exists' => 'Module tidak ditemukan.',
        'name.required' => 'Nama action wajib diisi.',
        'name.unique' => 'Nama action sudah digunakan pada module ini.',
        'label.required' => 'Label action wajib diisi.',
        'label.unique' => 'Label action sudah digunakan pada module ini.',
        'permission_name.required' => 'Permission name wajib diisi.',
        'permission_name.unique' => 'Permission name sudah digunakan.',
    ];
}
