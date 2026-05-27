<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'teaching_assignment_id' => [
                Rule::requiredIf($this->routeIs('teacher.grades.store')),
                'exists:teaching_assignments,id',
            ],
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
