<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Module extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'order',
        'description'
    ];

    public static function booted(): void
    {
        static::creating(function ($module) {
            if(empty($module->slug)) {
                $module->slug = Str::slug($module->name);
            }
        });
    }

    public function permissionsMetas()
    {
        return $this->hasMany(PermissionsMeta::class);
    }
}
