<?php

namespace App\Http\Requests\Department;

use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
     * Tentukan rules validasi yang berlaku untuk request (Store/Add).
     */
    public function rules(): array
    {
        return Department::rules();
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
            'name.max' => 'Nama department tidak boleh lebih dari 225 karakter',
        ];
    }
}
