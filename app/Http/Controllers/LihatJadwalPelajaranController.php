<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\HomeroomAssignment;
use App\Models\JadwalPelajaran;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class LihatJadwalPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $role = $user->getRoleNames()->first();

        if ($role === 'wali_santri') {
            abort(403, 'Akses ditolak.');
        }

        $query = JadwalPelajaran::with([
            'tahunAjaran', 'semester', 'kelas.level', 'mapel', 'guru',
        ]);

        if ($role === 'wali_kelas') {
            $teacher = $user->teacher;
            if (!$teacher) {
                abort(403, 'Akun guru tidak ditemukan.');
            }

            $activeAcademicYear = AcademicYear::where('is_active', true)->first();
            $activeSemester = Semester::where('is_active', true)->first();

            $classIds = HomeroomAssignment::where('teacher_id', $teacher->id)
                ->when($activeAcademicYear, fn($q) => $q->where('academic_year_id', $activeAcademicYear->id))
                ->when($activeSemester, fn($q) => $q->where('semester_id', $activeSemester->id))
                ->pluck('school_class_id');

            if ($classIds->isEmpty()) {
                $jadwals = collect();
                $academicYears = AcademicYear::orderByDesc('start_date')->pluck('name', 'id');
                $semesters = Semester::orderByDesc('start_date')->pluck('name', 'id');
                $schoolClasses = collect();
                $teachers = collect();
                $subjects = Subject::orderBy('name')->pluck('name', 'id');
                $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

                return view('jadwal-pelajaran.index', compact(
                    'jadwals', 'academicYears', 'semesters', 'schoolClasses', 'teachers', 'subjects', 'days',
                ))->with('info', 'Anda belum memiliki kelas yang diampu.');
            }

            $query->whereIn('kelas_id', $classIds);

            $availableClasses = SchoolClass::active()->with('level')
                ->whereIn('id', $classIds)->orderBy('sort_order')->get()
                ->mapWithKeys(fn($c) => [$c->id => $c->level->name . ' - ' . $c->name]);
        } elseif ($role === 'guru_fan') {
            $teacher = $user->teacher;
            if (!$teacher) {
                abort(403, 'Akun guru tidak ditemukan.');
            }

            $query->where('guru_id', $teacher->id);

            $availableClasses = SchoolClass::active()->with('level')->orderBy('sort_order')->get()
                ->mapWithKeys(fn($c) => [$c->id => $c->level->name . ' - ' . $c->name]);
        } else {
            $availableClasses = SchoolClass::active()->with('level')->orderBy('sort_order')->get()
                ->mapWithKeys(fn($c) => [$c->id => $c->level->name . ' - ' . $c->name]);
        }

        if ($request->filled('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        }
        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        if ($request->filled('guru_id')) {
            $query->where('guru_id', $request->guru_id);
        }
        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->mapel_id);
        }
        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        $jadwals = $query->orderBy('hari')->orderBy('jam_mulai')->paginate(20)->withQueryString();

        $academicYears = AcademicYear::orderByDesc('start_date')->pluck('name', 'id');
        $semesters = Semester::orderByDesc('start_date')->pluck('name', 'id');
        $teachers = Teacher::active()->orderBy('name')->pluck('name', 'id');
        $subjects = Subject::orderBy('name')->pluck('name', 'id');
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $schoolClasses = $availableClasses ?? SchoolClass::active()->with('level')->orderBy('sort_order')->get()
            ->mapWithKeys(fn($c) => [$c->id => $c->level->name . ' - ' . $c->name]);

        return view('jadwal-pelajaran.index', compact(
            'jadwals', 'academicYears', 'semesters', 'schoolClasses', 'teachers', 'subjects', 'days',
        ));
    }
}
