<?php

namespace App\Http\Requests\SchedulePeriod;

use App\Models\SchedulePeriod;
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
        return SchedulePeriod::rules($this->route('schedulePeriod')->id);
    }

    public function messages(): array
    {
        return SchedulePeriod::MESSAGES;
    }
}
