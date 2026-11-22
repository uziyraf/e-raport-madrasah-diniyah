<?php

namespace App\Http\Requests\Homeroom;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'grades' => ['required', 'array', 'min:1'],
            'grades.*.student_id' => ['required', 'exists:students,id'],
            'grades.*.score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.predicate' => ['nullable', 'string', 'max:50'],
            'grades.*.note' => ['nullable', 'string', 'max:500'],
            'grades.*.status' => ['required', 'in:draft,submitted'],
        ];
    }

    public function messages(): array
    {
        return [
            'grades.*.score.numeric' => 'Nilai harus berupa angka.',
            'grades.*.score.min' => 'Nilai minimal 0.',
            'grades.*.score.max' => 'Nilai maksimal 100.',
            'grades.*.status.required' => 'Status nilai harus dipilih.',
            'grades.*.status.in' => 'Status nilai harus draft atau submitted.',
        ];
    }
}
