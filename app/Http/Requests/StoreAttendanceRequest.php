<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'attendance_date' => ['required', 'date'],
            'status' => ['required', 'in:draft,submitted'],
            'details' => ['required', 'array'],
            'details.*.student_id' => ['required', 'exists:students,id'],
            'details.*.status' => ['required', 'in:present,permission,sick,absent'],
            'details.*.note' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'attendance_date.required' => 'Tanggal absensi wajib diisi.',
            'attendance_date.date' => 'Tanggal absensi tidak valid.',
            'status.required' => 'Status absensi wajib dipilih.',
            'status.in' => 'Status absensi tidak valid.',
            'details.required' => 'Data santri wajib diisi.',
            'details.array' => 'Data santri tidak valid.',
            'details.*.student_id.required' => 'Santri wajib dipilih.',
            'details.*.student_id.exists' => 'Santri tidak valid.',
            'details.*.status.required' => 'Status absensi santri wajib dipilih.',
            'details.*.status.in' => 'Status absensi santri tidak valid.',
        ];
    }
}
