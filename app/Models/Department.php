<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
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

    public static function rules($ignored = null){
        return [
            'name' => 'sometimes|unique:departments,name'.(is_string($ignored) ? ",$ignored,id" : ''),
            'content' => 'sometimes|string'
        ];
    }

    public const MESSAGES = [
        'name.required' => 'Nama tidak',
        'name.unique' => 'Nama bla bla bla',
        'content.string' => 'Kontent bla bla bla'
    ];
}