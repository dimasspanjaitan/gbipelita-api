<?php

namespace App\Http\Requests\ScheduleAssignment;

use Illuminate\Foundation\Http\FormRequest;

class BulkUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assignments' => ['required', 'array'],

            'assignments.*.id' => ['required', 'uuid'],
            'assignments.*.user_id' => ['required', 'uuid'],
        ];
    }
}