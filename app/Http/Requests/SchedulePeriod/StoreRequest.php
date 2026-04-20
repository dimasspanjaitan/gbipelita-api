<?php

namespace App\Http\Requests\SchedulePeriod;

use App\Models\SchedulePeriod;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return SchedulePeriod::rules();
    }

    public function messages(): array
    {
        return SchedulePeriod::MESSAGES;
    }
}
