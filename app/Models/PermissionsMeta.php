<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionsMeta extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'module_id',
        'module',
        'menu',
        'route_name',
        'permission_name',
        'action',
        'description'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
