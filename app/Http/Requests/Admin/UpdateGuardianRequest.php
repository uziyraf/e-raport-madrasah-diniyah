<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGuardianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $guardian = $this->route('guardian');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'relationship' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'students' => ['nullable', 'array'],
            'students.*' => ['exists:students,id'],
            'account.create_account' => ['boolean'],
        ];

        if ($this->boolean('account.create_account')) {
            if ($guardian->user_id) {
                $rules['account.username'] = [
                    'required', 'string', 'max:100',
                    Rule::unique('users', 'username')->ignore($guardian->user_id),
                ];
                $rules['account.email'] = [
                    'nullable', 'email', 'max:255',
                    Rule::unique('users', 'email')->ignore($guardian->user_id),
                ];
            } else {
                $rules['account.username'] = ['required', 'string', 'max:100', 'unique:users,username'];
                $rules['account.password'] = ['required', 'string', 'min:8'];
                $rules['account.email'] = ['nullable', 'email', 'max:255', 'unique:users,email'];
            }
        }

        return $rules;
    }
}
