<?php

namespace App\Http\Requests\Volunteer;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                'max:50',
                "unique:users,username",
            ],
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'nickname' => [
                'required',
                'string',
                'max:50',
                "unique:users,nickname",
            ],
            'email' => [
                'nullable',
                'email',
                "unique:users,email",
            ],
            'password' => ['required', 'string', 'min:8'],
            'status' => ['required', 'in:active,inactive'],
            'departments' => ['sometimes', 'array'],
            'departments.*' => ['required', 'string', 'uuid', 'exists:departments,id'],
            'divisions' => ['sometimes', 'array'],
            'divisions.*' => ['required', 'string', 'uuid', 'exists:divisions,id'],
            'skills' => ['array'],
            'skills.*.skill_id' => ['required', 'uuid', 'exists:skills,id'],
            'skills.*.is_primary' => ['boolean'],
            'skills.*.order' => ['integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username is required.',
            'username.unique' => 'Username has already been used.',
            'nickname.required' => 'Nickname is required.',
            'nickname.unique' => 'Nickname has already been used.',
            'email.email' => 'Invalid email format.',
            'email.unique' => 'Email has already been used.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'departments.*.uuid' => 'Department ID must be a valid UUID.',
            'departments.*.exists' => 'Selected department does not exist.',
            'divisions.*.uuid' => 'Division ID must be a valid UUID.',
            'divisions.*.exists' => 'Selected division does not exist.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors'  => $validator->errors(),
        ], 422));
    }
}
