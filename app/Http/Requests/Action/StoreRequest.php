<?php

namespace App\Http\Requests\Action;

use App\Models\Action;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return Action::rules();
    }

    public function messages(): array
    {
        return Action::MESSAGES;
    }
}
