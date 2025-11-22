<?php

namespace App\Http\Requests\Module;

use App\Models\Module;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return Module::rules();
    }

    public function messages(): array
    {
        return Module::MESSAGES;
    }
}
