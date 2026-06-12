<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreJadwalPelajaranRequest;
use App\Http\Requests\Admin\UpdateJadwalPelajaranRequest;
use App\Models\AcademicYear;
use App\Models\JadwalPelajaran;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class JadwalPelajaranController extends Controller
{
    public function template()
    {
        $user = auth()->user();
        $role = $user->getRoleNames()->first();

        if (!in_array($role, ['super_admin', 'kepala_sekolah'])) {
            abort(403, 'Akses ditolak.');
        }

        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        $jadwalByClass = JadwalPelajaran::with(['kelas.level', 'guru', 'mapel'])
            ->when($activeAcademicYear, fn($q) => $q->where('tahun_ajaran_id', $activeAcademicYear->id))
            ->when($activeSemester, fn($q) => $q->where('semester_id', $activeSemester->id))
            ->get()
            ->groupBy('kelas_id')
            ->map(fn($items) => $items->groupBy('hari'));

        $classIds = $jadwalByClass->keys();
        $classes = SchoolClass::whereIn('id', $classIds)->with('level')->orderBy('sort_order')->get();

        $days = ['Sabtu', 'Ahad', 'Senin', 'Selasa', 'Rabu', 'Kamis'];

        return view('admin.jadwal-pelajaran.template', compact(
            'jadwalByClass', 'classes', 'days', 'activeAcademicYear', 'activeSemester'
        ));
    }

    public function index()
    {
        $jadwals = JadwalPelajaran::with([
            'tahunAjaran', 'semester', 'kelas.level', 'mapel', 'guru',
        ])->orderBy('hari')->orderBy('jam_mulai')->paginate(20);

        $academicYears = AcademicYear::orderByDesc('start_date')->pluck('name', 'id');
        $semesters = Semester::orderByDesc('start_date')->pluck('name', 'id');
        $schoolClasses = SchoolClass::active()->with('level')->orderBy('sort_order')->get()
            ->mapWithKeys(fn($c) => [$c->id => $c->level->name . ' - ' . $c->name]);
        $teachers = Teacher::active()->orderBy('name')->pluck('name', 'id');
        $subjects = Subject::orderBy('name')->pluck('name', 'id');
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('admin.jadwal-pelajaran.index', compact(
            'jadwals', 'academicYears', 'semesters', 'schoolClasses', 'teachers', 'subjects', 'days',
        ));
    }

    public function create()
    {
        $academicYears = AcademicYear::orderByDesc('start_date')->pluck('name', 'id');
        $semesters = Semester::orderByDesc('start_date')->pluck('name', 'id');
        $schoolClasses = SchoolClass::active()->with('level')->orderBy('sort_order')->get()
            ->mapWithKeys(fn($c) => [$c->id => $c->level->name . ' - ' . $c->name]);
        $teachers = Teacher::active()->orderBy('name')->pluck('name', 'id');
        $subjects = Subject::orderBy('name')->pluck('name', 'id');
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('admin.jadwal-pelajaran.create', compact(
            'academicYears', 'semesters', 'schoolClasses', 'teachers', 'subjects', 'days',
        ));
    }

    public function store(StoreJadwalPelajaranRequest $request)
    {
        JadwalPelajaran::create([
            ...$request->validated(),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.jadwal-pelajaran.index')
            ->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function edit(JadwalPelajaran $jadwalPelajaran)
    {
        $jadwal = $jadwalPelajaran->loadMissing([
            'tahunAjaran', 'semester', 'kelas.level', 'mapel', 'guru',
        ]);

        $academicYears = AcademicYear::orderByDesc('start_date')->pluck('name', 'id');
        $semesters = Semester::orderByDesc('start_date')->pluck('name', 'id');
        $schoolClasses = SchoolClass::active()->with('level')->orderBy('sort_order')->get()
            ->mapWithKeys(fn($c) => [$c->id => $c->level->name . ' - ' . $c->name]);
        $teachers = Teacher::active()->orderBy('name')->pluck('name', 'id');
        $subjects = Subject::orderBy('name')->pluck('name', 'id');
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('admin.jadwal-pelajaran.edit', compact(
            'jadwal', 'academicYears', 'semesters', 'schoolClasses', 'teachers', 'subjects', 'days',
        ));
    }

    public function update(UpdateJadwalPelajaranRequest $request, JadwalPelajaran $jadwalPelajaran)
    {
        $jadwalPelajaran->update($request->validated());

        return redirect()->route('admin.jadwal-pelajaran.index')
            ->with('success', 'Jadwal pelajaran berhasil diperbarui.');
    }

    public function destroy(JadwalPelajaran $jadwalPelajaran)
    {
        $jadwalPelajaran->delete();

        return redirect()->route('admin.jadwal-pelajaran.index')
            ->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }
}
