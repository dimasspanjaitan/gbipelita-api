<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @method mixed route(string|null $param = null, mixed $default = null)
 */
class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Atau bisa dihubungkan ke Policy
    }

    public function rules(): array
    {
        $user = $this->route('user');
        $userId = $user instanceof \App\Models\User ? $user->getKey() : $user;

        return [
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:50|unique:users,username,' . $userId,
            'email' => 'sometimes|email|unique:users,email,' . $userId,
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'sometimes|in:active,inactive',
            'photo' => 'nullable|image|max:2048', // max 2MB
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Email already exists.',
            'username.unique' => 'Username already exists.',
            'password.confirmed' => 'Password confirmation does not match.',
            'photo.image' => 'Photo must be an image file.',
        ];
    }
}
