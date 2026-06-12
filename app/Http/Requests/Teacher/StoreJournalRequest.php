<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreJournalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'journal_date' => ['required', 'date'],
            'journal_type' => ['required', 'in:hafalan,legalisir_kitab,nilai_harian,tamrinan,catatan'],
            'student_id' => ['required', 'exists:students,id'],
            'teaching_assignment_id' => ['nullable', 'exists:teaching_assignments,id'],
            'school_class_id' => ['nullable', 'exists:school_classes,id'],
            'academic_year_id' => ['nullable', 'exists:academic_years,id'],
            'semester_id' => ['nullable', 'exists:semesters,id'],
            'memorization_type' => ['nullable', 'string', 'max:100'],
            'memorization_target' => ['nullable', 'string', 'max:255'],
            'memorization_result' => ['nullable', 'string', 'max:255'],
            'kitab_name' => ['nullable', 'string', 'max:255'],
            'kitab_page' => ['nullable', 'string', 'max:100'],
            'legalization_status' => ['nullable', 'string', 'max:100'],
            'daily_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'exam_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'predicate' => ['nullable', 'string', 'max:50'],
            'note' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,submitted'],
        ];

        if ($this->input('journal_type') === 'tamrinan') {
            $rules['teaching_assignment_id'] = ['required', 'exists:teaching_assignments,id'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'journal_date.required' => 'Tanggal jurnal wajib diisi.',
            'journal_date.date' => 'Tanggal jurnal tidak valid.',
            'journal_type.required' => 'Jenis jurnal wajib dipilih.',
            'journal_type.in' => 'Jenis jurnal tidak valid.',
            'student_id.required' => 'Santri wajib dipilih.',
            'student_id.exists' => 'Santri tidak valid.',
            'daily_score.numeric' => 'Nilai harian harus berupa angka.',
            'daily_score.min' => 'Nilai harian minimal 0.',
            'daily_score.max' => 'Nilai harian maksimal 100.',
            'exam_score.numeric' => 'Nilai ujian harus berupa angka.',
            'exam_score.min' => 'Nilai ujian minimal 0.',
            'exam_score.max' => 'Nilai ujian maksimal 100.',
            'status.required' => 'Status jurnal wajib dipilih.',
            'status.in' => 'Status jurnal tidak valid.',
            'teaching_assignment_id.required' => 'Fan/Mapel yang diujikan wajib dipilih untuk jurnal tamrinan.',
        ];
    }
}
