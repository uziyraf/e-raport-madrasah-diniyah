<?php

namespace App\Http\Controllers\Homeroom;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceRequest;
use App\Models\AcademicYear;
use App\Models\AttendanceDetail;
use App\Models\AttendanceSession;
use App\Models\HomeroomAssignment;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentClassEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            return view('homeroom.attendances.index', [
                'homeroom' => null,
                'sessions' => collect(),
                'presentCount' => 0,
                'permissionCount' => 0,
                'sickCount' => 0,
                'absentCount' => 0,
            ]);
        }

        $homeroom = HomeroomAssignment::with(['schoolClass.level', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->first();

        if (!$homeroom) {
            return view('homeroom.attendances.index', [
                'homeroom' => null,
                'sessions' => collect(),
                'presentCount' => 0,
                'permissionCount' => 0,
                'sickCount' => 0,
                'absentCount' => 0,
            ]);
        }

        $query = AttendanceSession::with(['teacher', 'schoolClass.level', 'academicYear', 'semester', 'details'])
            ->where('teacher_id', $teacher->id)
            ->where('attendance_type', 'homeroom')
            ->where('school_class_id', $homeroom->school_class_id)
            ->where('academic_year_id', $homeroom->academic_year_id)
            ->where('semester_id', $homeroom->semester_id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('attendance_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('attendance_date', '<=', $request->date_to);
        }

        $sessions = $query->orderByDesc('attendance_date')
            ->orderByDesc('created_at')
            ->paginate(15);

        $labels = [
            'present' => 'Hadir',
            'permission' => 'Izin',
            'sick' => 'Sakit',
            'absent' => 'Alfa',
        ];

        return view('homeroom.attendances.index', compact(
            'homeroom', 'sessions', 'labels'
        ));
    }

    public function create()
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $homeroom = HomeroomAssignment::with(['schoolClass.level', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->firstOrFail();

        $students = StudentClassEnrollment::with('student')
            ->where('school_class_id', $homeroom->school_class_id)
            ->where('academic_year_id', $homeroom->academic_year_id)
            ->where('semester_id', $homeroom->semester_id)
            ->where('is_active', true)
            ->orderBy('created_at')
            ->get();

        return view('homeroom.attendances.create', compact('homeroom', 'students'));
    }

    public function store(StoreAttendanceRequest $request)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $homeroom = HomeroomAssignment::where('teacher_id', $teacher->id)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->firstOrFail();

        $existing = AttendanceSession::where('attendance_date', $request->attendance_date)
            ->where('teacher_id', $teacher->id)
            ->where('attendance_type', 'homeroom')
            ->where('school_class_id', $homeroom->school_class_id)
            ->where('academic_year_id', $homeroom->academic_year_id)
            ->where('semester_id', $homeroom->semester_id)
            ->first();

        if ($existing) {
            return redirect()->route('homeroom.attendances.edit', $existing)
                ->with('error', 'Sudah ada sesi absensi untuk tanggal ini. Silakan edit sesi yang sudah ada.');
        }

        $session = AttendanceSession::create([
            'attendance_date' => $request->attendance_date,
            'attendance_type' => 'homeroom',
            'teacher_id' => $teacher->id,
            'school_class_id' => $homeroom->school_class_id,
            'academic_year_id' => $homeroom->academic_year_id,
            'semester_id' => $homeroom->semester_id,
            'status' => $request->status,
            'created_by' => Auth::id(),
        ]);

        foreach ($request->details as $detail) {
            $session->details()->create([
                'student_id' => $detail['student_id'],
                'status' => $detail['status'],
                'note' => $detail['note'] ?? null,
            ]);
        }

        return redirect()->route('homeroom.attendances.show', $session)
            ->with('success', 'Absensi berhasil disimpan.');
    }

    public function show(AttendanceSession $attendanceSession)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403);
        }

        $homeroom = HomeroomAssignment::where('teacher_id', $teacher->id)
            ->where('school_class_id', $attendanceSession->school_class_id)
            ->where('academic_year_id', $attendanceSession->academic_year_id)
            ->where('semester_id', $attendanceSession->semester_id)
            ->first();

        if ($attendanceSession->teacher_id !== $teacher->id && !$homeroom) {
            abort(403, 'Anda tidak memiliki akses ke sesi absensi ini.');
        }

        $session = $attendanceSession->loadMissing([
            'teacher', 'schoolClass.level', 'academicYear', 'semester',
            'details.student', 'teachingAssignment.subject',
        ]);

        $labels = [
            'present' => 'Hadir',
            'permission' => 'Izin',
            'sick' => 'Sakit',
            'absent' => 'Alfa',
        ];

        return view('homeroom.attendances.show', compact('session', 'labels'));
    }

    public function edit(AttendanceSession $attendanceSession)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $homeroom = HomeroomAssignment::where('teacher_id', $teacher->id)
            ->where('school_class_id', $attendanceSession->school_class_id)
            ->where('academic_year_id', $attendanceSession->academic_year_id)
            ->where('semester_id', $attendanceSession->semester_id)
            ->first();

        if ($attendanceSession->teacher_id !== $teacher->id && !$homeroom) {
            abort(403, 'Anda tidak memiliki akses ke sesi absensi ini.');
        }

        $session = $attendanceSession->loadMissing([
            'schoolClass.level', 'academicYear', 'semester',
            'details.student',
        ]);

        $students = StudentClassEnrollment::with('student')
            ->where('school_class_id', $session->school_class_id)
            ->where('academic_year_id', $session->academic_year_id)
            ->where('semester_id', $session->semester_id)
            ->where('is_active', true)
            ->orderBy('created_at')
            ->get();

        return view('homeroom.attendances.edit', compact('session', 'homeroom', 'students'));
    }

    public function update(StoreAttendanceRequest $request, AttendanceSession $attendanceSession)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $homeroom = HomeroomAssignment::where('teacher_id', $teacher->id)
            ->where('school_class_id', $attendanceSession->school_class_id)
            ->where('academic_year_id', $attendanceSession->academic_year_id)
            ->where('semester_id', $attendanceSession->semester_id)
            ->first();

        if ($attendanceSession->teacher_id !== $teacher->id && !$homeroom) {
            abort(403, 'Anda tidak memiliki akses ke sesi absensi ini.');
        }

        $attendanceSession->update([
            'attendance_date' => $request->attendance_date,
            'status' => $request->status,
        ]);

        $attendanceSession->details()->delete();

        foreach ($request->details as $detail) {
            $attendanceSession->details()->create([
                'student_id' => $detail['student_id'],
                'status' => $detail['status'],
                'note' => $detail['note'] ?? null,
            ]);
        }

        return redirect()->route('homeroom.attendances.show', $attendanceSession)
            ->with('success', 'Absensi berhasil diperbarui.');
    }

    public function destroy(AttendanceSession $attendanceSession)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403);
        }

        $homeroom = HomeroomAssignment::where('teacher_id', $teacher->id)
            ->where('school_class_id', $attendanceSession->school_class_id)
            ->where('academic_year_id', $attendanceSession->academic_year_id)
            ->where('semester_id', $attendanceSession->semester_id)
            ->first();

        if ($attendanceSession->teacher_id !== $teacher->id && !$homeroom) {
            abort(403, 'Anda tidak memiliki akses ke sesi absensi ini.');
        }

        $attendanceSession->delete();

        return redirect()->route('homeroom.attendances.index')
            ->with('success', 'Sesi absensi berhasil dihapus.');
    }

    public function studentHistory(Student $student)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $homeroom = HomeroomAssignment::with(['schoolClass.level', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->firstOrFail();

        $enrolled = StudentClassEnrollment::where('student_id', $student->id)
            ->where('school_class_id', $homeroom->school_class_id)
            ->where('academic_year_id', $homeroom->academic_year_id)
            ->where('semester_id', $homeroom->semester_id)
            ->where('is_active', true)
            ->exists();

        if (!$enrolled) {
            abort(403, 'Santri ini tidak terdaftar di kelas wali anda.');
        }

        $viewMode = request('view', 'table');

        if ($viewMode === 'calendar') {
            $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
            $activeSemester = Semester::whereHas('academicYear', fn($q) => $q->where('is_active', true))
                ->where('is_active', true)->firstOrFail();

            $month = (int) request('month', now()->month);
            $year = (int) request('year', now()->year);

            $attendanceDetails = AttendanceDetail::with([
                'session.teachingAssignment.subject',
                'session.teacher',
            ])
                ->where('student_id', $student->id)
                ->whereHas('session', fn($q) => $q
                    ->where('school_class_id', $homeroom->school_class_id)
                    ->where('academic_year_id', $activeYear->id)
                    ->where('semester_id', $activeSemester->id)
                    ->whereMonth('attendance_date', $month)
                    ->whereYear('attendance_date', $year)
                )
                ->get()
                ->groupBy(fn($d) => $d->session->attendance_date->format('Y-m-d'));

            $attendanceDetails = $attendanceDetails->sortKeys();

            return view('homeroom.attendances.student', compact(
                'student', 'homeroom', 'viewMode', 'month', 'year', 'attendanceDetails',
            ));
        }

        $attendances = AttendanceSession::with(['details' => fn($q) => $q->where('student_id', $student->id), 'teachingAssignment.subject', 'schoolClass.level'])
            ->where('teacher_id', $teacher->id)
            ->where('attendance_type', 'homeroom')
            ->where('school_class_id', $homeroom->school_class_id)
            ->where('academic_year_id', $homeroom->academic_year_id)
            ->where('semester_id', $homeroom->semester_id)
            ->orderByDesc('attendance_date')
            ->paginate(15);

        $statusLabels = [
            'present' => 'Hadir',
            'permission' => 'Izin',
            'sick' => 'Sakit',
            'absent' => 'Alfa',
        ];

        return view('homeroom.attendances.student', compact(
            'attendances', 'student', 'homeroom', 'statusLabels', 'viewMode',
        ));
    }
}
