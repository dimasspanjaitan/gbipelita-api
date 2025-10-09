<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionsMeta extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'module',
        'menu',
        'route_name',
        'permission_name',
        'action',
        'description'
    ];
}
