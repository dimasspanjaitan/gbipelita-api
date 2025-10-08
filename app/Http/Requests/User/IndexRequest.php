<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $sortColumn = request('sort_column');

        return [
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
            'search' => ['nullable', 'string'],
            'sort_column' => ['nullable', 'string', Rule::in(['name', 'email', 'username'])],
            'sort_direction' => [
                $sortColumn ? 'required' : 'nullable',
                'string',
                Rule::in(['asc', 'desc']),
            ],
            'trashed' => ['nullable', 'boolean'],
        ];
    }
}
