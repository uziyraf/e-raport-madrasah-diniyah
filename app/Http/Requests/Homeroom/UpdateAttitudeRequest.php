<?php

namespace App\Http\Requests\Homeroom;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttitudeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'akhlak' => ['nullable', 'string', 'max:50'],
            'discipline' => ['nullable', 'string', 'max:50'],
            'cleanliness' => ['nullable', 'string', 'max:50'],
            'attitude_note' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'akhlak.max' => 'Akhlak maksimal 50 karakter.',
            'discipline.max' => 'Kedisiplinan maksimal 50 karakter.',
            'cleanliness.max' => 'Kebersihan maksimal 50 karakter.',
        ];
    }
}
