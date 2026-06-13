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
        'data'
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    public function toArray(): array
    {
        $attributes = parent::toArray();

        unset($attributes['data']);

        return array_merge(
            $attributes,
            $this->data ?? []
        );
    }

    public function fill(array $attributes)
    {
        $columns = array_flip($this->getFillable());

        $fillable = [];
        $extra = [];

        foreach ($attributes as $key => $value) {
            if (isset($columns[$key]) && $key !== 'data') {
                $fillable[$key] = $value;
            } else {
                $extra[$key] = $value;
            }
        }

        $fillable['data'] = array_merge(
            $this->getAttribute('data') ?? [],
            $extra
        );

        return parent::fill($fillable);
    }
}
