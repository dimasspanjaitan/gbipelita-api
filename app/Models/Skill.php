<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'division_id'

    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_skills')
            ->withPivot(['is_primary', 'order'])
            ->withTimestamps();
    }

    /** ────────────────────────────────
     *  VALIDATION RULES
     *  ──────────────────────────────── */
    public static function rules(?string $ignoreId = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                "unique:skills,name,{$ignoreId},id",
            ],
            'division_id' => ['required', 'uuid', 'exists:divisions,id'],
        ];
    }

    public const MESSAGES = [
        'name.required' => 'Skill name is required.',
        'name.unique' => 'Skill name has already been used in this division.',
        'name.string' => 'Skill name must be a text string.',
        'division_id.required' => 'Divisi is required.',
        'division_id.uuid' => 'Divisi ID must be a valid UUID.',
        'division_id.exists' => 'Selected divisi does not exist.',
    ];
}
