<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $student = $this->route('student');
        $studentId = $student->id ?? $student;

        return [
            'nis' => ['required', 'string', 'max:30', Rule::unique('students', 'nis')->ignore($studentId)],
            'name' => ['required', 'string', 'max:255'],
            'arabic_name' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'in:male,female'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'school_class_id' => ['nullable', 'string', 'required_with:academic_year_id,semester_id', 'exists:school_classes,id'],
            'academic_year_id' => ['nullable', 'string', 'required_with:school_class_id,semester_id', 'exists:academic_years,id'],
            'semester_id' => ['nullable', 'string', 'required_with:school_class_id,academic_year_id', 'exists:semesters,id'],
        ];
    }
}
