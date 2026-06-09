<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreJadwalPelajaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tahun_ajaran_id' => ['required', 'exists:academic_years,id'],
            'semester_id' => ['required', 'exists:semesters,id'],
            'kelas_id' => ['required', 'exists:school_classes,id'],
            'mapel_id' => ['required', 'exists:subjects,id'],
            'guru_id' => ['required', 'exists:teachers,id'],
            'hari' => ['required', 'string', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
            'hari.in' => 'Hari tidak valid. Pilih: Senin, Selasa, Rabu, Kamis, Jumat, Sabtu.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $data = $this->validated();

            if (!$validator->errors()->has('jam_mulai') && !$validator->errors()->has('jam_selesai')) {
                $this->checkBentrokGuru($validator, $data, null);
                $this->checkBentrokKelas($validator, $data, null);
            }
        });
    }

    private function checkBentrokGuru(Validator $validator, array $data, ?int $ignoreId): void
    {
        $exists = \App\Models\JadwalPelajaran::where('hari', $data['hari'])
            ->where('guru_id', $data['guru_id'])
            ->where('jam_mulai', '<', $data['jam_selesai'])
            ->where('jam_selesai', '>', $data['jam_mulai']);

        if ($ignoreId) {
            $exists->where('id', '!=', $ignoreId);
        }

        if ($exists->exists()) {
            $validator->errors()->add('guru_id', 'Guru sudah memiliki jadwal pada hari dan jam tersebut.');
        }
    }

    private function checkBentrokKelas(Validator $validator, array $data, ?int $ignoreId): void
    {
        $exists = \App\Models\JadwalPelajaran::where('hari', $data['hari'])
            ->where('kelas_id', $data['kelas_id'])
            ->where('jam_mulai', '<', $data['jam_selesai'])
            ->where('jam_selesai', '>', $data['jam_mulai']);

        if ($ignoreId) {
            $exists->where('id', '!=', $ignoreId);
        }

        if ($exists->exists()) {
            $validator->errors()->add('kelas_id', 'Kelas sudah memiliki jadwal pada hari dan jam tersebut.');
        }
    }
}
