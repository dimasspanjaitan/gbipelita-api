<?php

namespace App\Http\Requests\Role;

use App\Models\Role;
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
        $role = $this->route('role');
        return Role::rules($role ? $role->id : null);
    }

    public function messages(): array
    {
        return Role::MESSAGES;
    }
}
