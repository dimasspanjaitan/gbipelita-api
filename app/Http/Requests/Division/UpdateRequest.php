<?php

namespace App\Http\Requests\Division;

use App\Models\Division;
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
        return Division::rules($this->route('division')->id);
    }

    public function messages(): array
    {
        return Division::MESSAGES;
    }
}
