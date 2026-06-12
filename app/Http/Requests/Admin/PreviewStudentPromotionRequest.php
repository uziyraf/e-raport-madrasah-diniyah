<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PreviewStudentPromotionRequest extends FormRequest
{
    protected $errorBag = 'preview';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'source_academic_year_id' => ['nullable', 'exists:academic_years,id'],
            'source_semester_id' => ['nullable', 'exists:semesters,id'],
            'source_school_class_id' => ['nullable', 'exists:school_classes,id'],
            'target_academic_year_id' => ['required', 'exists:academic_years,id'],
            'target_semester_id' => ['required', 'exists:semesters,id'],
            'target_school_class_id' => ['nullable', 'required_if:placement_status,naik', 'required_if:placement_status,tetap', 'required_if:placement_status,pindah', 'exists:school_classes,id'],
            'placement_status' => ['required', 'string', 'in:naik,tetap,pindah,lulus,keluar'],
            'mode' => ['required', 'string', 'in:class,bulk'],
            'students' => ['nullable', 'array', 'required_if:mode,class'],
            'students.*' => ['exists:students,id'],
            'bulk_input' => ['nullable', 'string', 'required_if:mode,bulk'],
        ];
    }
}
