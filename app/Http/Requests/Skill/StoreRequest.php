<?php

namespace App\Http\Requests\Skill;

use App\Models\Skill;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return Skill::rules();
    }

    public function messages(): array
    {
        return Skill::MESSAGES;
    }
}
