<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentPromotionRequest extends FormRequest
{
    protected $errorBag = 'store';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'target_academic_year_id' => ['required', 'exists:academic_years,id'],
            'target_semester_id' => ['required', 'exists:semesters,id'],
            'target_school_class_id' => ['nullable', 'required_if:placement_status,naik', 'required_if:placement_status,tetap', 'required_if:placement_status,pindah', 'exists:school_classes,id'],
            'placement_status' => ['required', 'string', 'in:naik,tetap,pindah,lulus,keluar'],
            'students' => ['required', 'array', 'min:1'],
            'students.*' => ['required', 'exists:students,id'],
        ];
    }
}
