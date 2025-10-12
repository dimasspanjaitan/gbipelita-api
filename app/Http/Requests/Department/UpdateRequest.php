<?php

namespace App\Http\Requests\Department;

use App\Models\Department;
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
        return Department::rules($this->route('department')->id);
    }

    public function messages(): array
    {
        return Department::MESSAGES;
    }
}
