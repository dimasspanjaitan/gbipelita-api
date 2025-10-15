<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'app_name',
        'app_title',
        'app_description',
        'app_logo',
        'app_logo_alternative',
        'app_favicon',
        'address',
        'phone',
        'email',
        'youtube',
        'instagram',
        'facebook',
        'whatsapp',
        'visi',
        'misi',
        'motto',
        'history',
    ];
}
