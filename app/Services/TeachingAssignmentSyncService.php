<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\JadwalPelajaran;
use App\Models\Semester;
use App\Models\TeachingAssignment;
use Illuminate\Support\Collection;

class TeachingAssignmentSyncService
{
    public function activeAcademicYearId(): ?int
    {
        return AcademicYear::where('is_active', true)->value('id');
    }

    public function activeSemesterId(): ?int
    {
        $academicYearId = $this->activeAcademicYearId();

        if (! $academicYearId) {
            return null;
        }

        return Semester::where('academic_year_id', $academicYearId)
            ->where('is_active', true)
            ->value('id');
    }

    public function syncForTeacher(int $teacherId, ?int $academicYearId, ?int $semesterId): Collection
    {
        if (! $academicYearId || ! $semesterId) {
            return collect();
        }

        $jadwals = JadwalPelajaran::query()
            ->where('guru_id', $teacherId)
            ->where('tahun_ajaran_id', $academicYearId)
            ->where('semester_id', $semesterId)
            ->get();

        return $this->sync($jadwals);
    }

    public function syncForClass(int $schoolClassId, int $academicYearId, int $semesterId): Collection
    {
        $jadwals = JadwalPelajaran::query()
            ->where('kelas_id', $schoolClassId)
            ->where('tahun_ajaran_id', $academicYearId)
            ->where('semester_id', $semesterId)
            ->get();

        return $this->sync($jadwals);
    }

    public function idsForTeacher(int $teacherId, ?int $academicYearId, ?int $semesterId): array
    {
        return $this->syncForTeacher($teacherId, $academicYearId, $semesterId)
            ->pluck('id')
            ->unique()
            ->values()
            ->all();
    }

    public function idsForClass(int $schoolClassId, int $academicYearId, int $semesterId): array
    {
        return $this->syncForClass($schoolClassId, $academicYearId, $semesterId)
            ->pluck('id')
            ->unique()
            ->values()
            ->all();
    }

    public function isFromJadwal(TeachingAssignment $assignment): bool
    {
        return JadwalPelajaran::query()
            ->where('guru_id', $assignment->teacher_id)
            ->where('mapel_id', $assignment->subject_id)
            ->where('kelas_id', $assignment->school_class_id)
            ->where('tahun_ajaran_id', $assignment->academic_year_id)
            ->where('semester_id', $assignment->semester_id)
            ->exists();
    }

    private function sync(Collection $jadwals): Collection
    {
        return $jadwals
            ->map(fn (JadwalPelajaran $jadwal) => TeachingAssignment::firstOrCreate([
                'teacher_id' => $jadwal->guru_id,
                'subject_id' => $jadwal->mapel_id,
                'school_class_id' => $jadwal->kelas_id,
                'academic_year_id' => $jadwal->tahun_ajaran_id,
                'semester_id' => $jadwal->semester_id,
            ]))
            ->unique('id')
            ->values();
    }
}
