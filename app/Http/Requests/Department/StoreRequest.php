<?php

namespace App\Http\Requests\Department;

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
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                // Harus unik saat membuat baru
                Rule::unique('departments', 'name'),
            ],
            'content' => ['nullable', 'string'],
        ];
    }

    /**
     * Siapkan data untuk validasi.
     *
     * Ini memastikan bahwa jika 'content' tidak ada dalam request,
     * secara eksplisit diatur ke null agar tidak memicu error 'Undefined array key'.
     */
    protected function prepareForValidation()
    {
        // Mengatasi 'Undefined array key' jika 'content' tidak disertakan dalam payload
        if (!$this->has('content')) {
            $this->merge([
                'content' => null,
            ]);
        }
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
