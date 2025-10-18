<?php

namespace App\Http\Requests\ModuleAction;

use App\Models\ModuleAction;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ModuleAction::rules();
    }

    public function messages(): array
    {
        return ModuleAction::MESSAGES;
    }
}
