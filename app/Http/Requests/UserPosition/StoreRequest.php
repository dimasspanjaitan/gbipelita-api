<?php

namespace App\Http\Requests\UserPosition;

use App\Models\UserPosition;
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
        return UserPosition::rules();
    }

    public function messages(): array
    {
        return UserPosition::MESSAGES;
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (UserPosition::positionExists($this->validated())) {
                $validator->errors()->add(
                    'role_id',
                    'This position is already occupied.'
                );
            }
        });
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
