<?php

namespace App\Http\Requests\Action;

use App\Models\Action;
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
        return Action::rules($this->route('module_action')->id);
    }

    public function messages(): array
    {
        return Action::MESSAGES;
    }
}
