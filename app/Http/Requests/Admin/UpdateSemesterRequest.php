<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSemesterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $semester = $this->route('semester');

        return [
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'name' => ['required', 'string', 'in:Ganjil,Genap'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'is_active' => ['boolean'],
        ];
    }

    public function withValidator($validator)
    {
        $semester = $this->route('semester');

        $validator->addRules([
            'name' => [
                'unique:semesters,name,' . $semester->id . ',id,academic_year_id,' . $this->academic_year_id,
            ],
        ]);
    }
}
