<?php

namespace App\Http\Requests\Department;

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
        // Autorisasi ditangani oleh middleware 'permission' di controller.
        return true;
    }

    /**
     * Tentukan rules validasi yang berlaku untuk request (Update/Edit).
     */
    public function rules(): array
    {
        // Dapatkan ID department dari parameter route (misal: departments/{id})
        $department = $this->route('id');
        // $departmentId = $department instanceof \App\Models\Department ? $department->getKey() : $department;

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                // Harus unik, tetapi abaikan ID department saat ini.
                Rule::unique('departments', 'name')->ignore($department, 'id'),
            ],
            'content' => ['sometimes', 'nullable', 'string'],
        ];
    }
    
    /**
     * Tentukan pesan error kustom untuk validasi.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama department wajib diisi.',
            'name.unique' => 'Nama department sudah digunakan.',
            'name.string' => 'Nama department harus berupa teks.',
        ];
    }
}
