<?php

namespace App\Http\Requests\Division;

use App\Models\Division;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return Division::rules();
    }

    public function messages(): array
    {
        return Division::MESSAGES;
    }
}
