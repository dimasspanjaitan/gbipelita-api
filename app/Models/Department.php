<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
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
}