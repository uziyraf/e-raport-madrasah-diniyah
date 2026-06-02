<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'teacher_code' => ['nullable', 'string', 'max:50', 'unique:teachers,teacher_code'],
            'name' => ['required', 'string', 'max:255'],
            'arabic_name' => ['nullable', 'string', 'max:255'],
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
            $rules['email'][] = 'unique:users,email';
            $rules['account.username'] = ['required', 'string', 'max:100', 'unique:users,username'];
            $rules['account.password'] = ['required', 'string', 'min:8'];
            $rules['account.role'] = ['required', 'string', 'in:kepala_sekolah,wali_kelas,guru_fan'];
        }

        return $rules;
    }
}
