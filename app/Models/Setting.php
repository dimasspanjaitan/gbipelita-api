<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasUuids;
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
        'visi',
        'misi',
        'motto',
        'history',
        'data'
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    public function toArray()
    {
        $data = parent::toArray();
        $extra = $data['data'] ?? [];
        unset($data['data']);

        return [
            ...$data,
            ...$extra,
        ];
    }
}
