<?php

namespace App\Http\Requests\Module;

use App\Models\Module;
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
        return Module::rules($this->route('module')->id);
    }

    public function messages(): array
    {
        return Module::MESSAGES;
    }
}
