<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $teacher = $this->route('teacher');
        $teacherId = $teacher->id ?? $teacher;

        $rules = [
            'teacher_code' => ['nullable', 'string', 'max:50', Rule::unique('teachers', 'teacher_code')->ignore($teacherId)],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'in:male,female'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'signature' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'account.create_account' => ['boolean'],
        ];

        if ($this->boolean('account.create_account')) {
            $rules['email'][] = 'required';

            if ($teacher->user_id) {
                $rules['email'][] = Rule::unique('users', 'email')->ignore($teacher->user_id);
                $rules['account.username'] = [
                    'required', 'string', 'max:100',
                    Rule::unique('users', 'username')->ignore($teacher->user_id),
                ];
            } else {
                $rules['email'][] = 'unique:users,email';
                $rules['account.username'] = ['required', 'string', 'max:100', 'unique:users,username'];
                $rules['account.password'] = ['required', 'string', 'min:8'];
            }

            $rules['account.role'] = ['required', 'string', 'in:kepala_sekolah,wali_kelas,guru_fan'];
        }

        return $rules;
    }
}
