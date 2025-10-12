<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @method mixed route(string|null $param = null, mixed $default = null)
 */
class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Atau bisa dihubungkan ke Policy
    }

    public function rules(): array
    {
        return User::rules($this->route("user")->id);
    }

    public function messages(): array
    {
        return User::MESSAGES;
    }
}
