<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ModuleAction extends Model
{
    use HasFactory, HasUuids;

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
}
