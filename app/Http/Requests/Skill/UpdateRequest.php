<?php

namespace App\Http\Requests\Skill;

use App\Models\Skill;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @method mixed route(string|null $param = null, mixed $default = null)
 */
class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return Skill::rules($this->route('skill')->id);
    }

    public function messages(): array
    {
        return Skill::MESSAGES;
    }
}
