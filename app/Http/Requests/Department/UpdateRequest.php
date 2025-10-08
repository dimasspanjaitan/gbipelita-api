<?php

namespace App\Http\Requests\Department;

use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @method mixed route(string|null $param = null, mixed $default = null)
 */
class UpdateRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan untuk membuat request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Tentukan rules validasi yang berlaku untuk request (Update/Edit).
     */
    public function rules(): array
    {
        return Department::rules($this->route('department')->id);
        
    }
    
    /**
     * Tentukan pesan error kustom untuk validasi.
     */
    public function messages(): array
    {
        return Department::MESSAGES;
        return [
            'name.required' => 'Nama department wajib diisi.',
            'name.unique' => 'Nama department sudah digunakan.',
            'name.string' => 'Nama department harus berupa teks.',
        ];
    }
}
