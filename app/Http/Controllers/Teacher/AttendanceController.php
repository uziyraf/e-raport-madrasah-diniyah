<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceRequest;
use App\Models\AcademicYear;
use App\Models\AttendanceDetail;
use App\Models\AttendanceSession;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentClassEnrollment;
use App\Models\TeachingAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $assignments = TeachingAssignment::with(['subject', 'schoolClass.level', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->orderByDesc('created_at')
            ->get();

        $selectedAssignment = null;

        if ($request->filled('teaching_assignment_id')) {
            $selectedAssignment = $assignments->firstWhere('id', $request->teaching_assignment_id);
        }

        if (!$selectedAssignment && $assignments->count() === 1) {
            $selectedAssignment = $assignments->first();
        }

        $sessions = collect();
        $labels = [
            'present' => 'Hadir',
            'permission' => 'Izin',
            'sick' => 'Sakit',
            'absent' => 'Alfa',
        ];

        if ($selectedAssignment) {
            $query = AttendanceSession::with(['teacher', 'schoolClass.level', 'academicYear', 'semester', 'details'])
                ->where('teacher_id', $teacher->id)
                ->where('attendance_type', 'teaching')
                ->where('teaching_assignment_id', $selectedAssignment->id)
                ->where('school_class_id', $selectedAssignment->school_class_id)
                ->where('academic_year_id', $selectedAssignment->academic_year_id)
                ->where('semester_id', $selectedAssignment->semester_id);

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
        }

        return view('teacher.attendances.index', compact(
            'assignments', 'selectedAssignment', 'sessions', 'labels'
        ));
    }

    public function create(Request $request)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $assignment = TeachingAssignment::with(['subject', 'schoolClass.level', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->findOrFail($request->query('teaching_assignment_id'));

        $students = StudentClassEnrollment::with('student')
            ->where('school_class_id', $assignment->school_class_id)
            ->where('academic_year_id', $assignment->academic_year_id)
            ->where('semester_id', $assignment->semester_id)
            ->where('is_active', true)
            ->orderBy('created_at')
            ->get();

        return view('teacher.attendances.create', compact('assignment', 'students'));
    }

    public function store(StoreAttendanceRequest $request)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $assignment = TeachingAssignment::where('teacher_id', $teacher->id)
            ->findOrFail($request->teaching_assignment_id);

        $existing = AttendanceSession::where('attendance_date', $request->attendance_date)
            ->where('teacher_id', $teacher->id)
            ->where('attendance_type', 'teaching')
            ->where('teaching_assignment_id', $assignment->id)
            ->where('school_class_id', $assignment->school_class_id)
            ->where('academic_year_id', $assignment->academic_year_id)
            ->where('semester_id', $assignment->semester_id)
            ->first();

        if ($existing) {
            return redirect()->route('teacher.attendances.edit', $existing)
                ->with('error', 'Sudah ada sesi absensi untuk tanggal ini. Silakan edit sesi yang sudah ada.');
        }

        $session = AttendanceSession::create([
            'attendance_date' => $request->attendance_date,
            'attendance_type' => 'teaching',
            'teacher_id' => $teacher->id,
            'teaching_assignment_id' => $assignment->id,
            'school_class_id' => $assignment->school_class_id,
            'academic_year_id' => $assignment->academic_year_id,
            'semester_id' => $assignment->semester_id,
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

        return redirect()->route('teacher.attendances.show', $session)
            ->with('success', 'Absensi berhasil disimpan.');
    }

    public function show(AttendanceSession $attendanceSession)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403);
        }

        if ($attendanceSession->teacher_id !== $teacher->id) {
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

        return view('teacher.attendances.show', compact('session', 'labels'));
    }

    public function edit(AttendanceSession $attendanceSession)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        if ($attendanceSession->teacher_id !== $teacher->id) {
            abort(403, 'Anda tidak memiliki akses ke sesi absensi ini.');
        }

        $session = $attendanceSession->loadMissing([
            'schoolClass.level', 'academicYear', 'semester',
            'details.student', 'teachingAssignment.subject',
        ]);

        $assignment = TeachingAssignment::with(['subject', 'schoolClass.level', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->findOrFail($session->teaching_assignment_id);

        $students = StudentClassEnrollment::with('student')
            ->where('school_class_id', $session->school_class_id)
            ->where('academic_year_id', $session->academic_year_id)
            ->where('semester_id', $session->semester_id)
            ->where('is_active', true)
            ->orderBy('created_at')
            ->get();

        return view('teacher.attendances.edit', compact('session', 'assignment', 'students'));
    }

    public function update(StoreAttendanceRequest $request, AttendanceSession $attendanceSession)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        if ($attendanceSession->teacher_id !== $teacher->id) {
            abort(403, 'Anda tidak memiliki akses ke sesi absensi ini.');
        }

        $assignment = TeachingAssignment::where('teacher_id', $teacher->id)
            ->findOrFail($request->teaching_assignment_id ?? $attendanceSession->teaching_assignment_id);

        $attendanceSession->update([
            'attendance_date' => $request->attendance_date,
            'teaching_assignment_id' => $assignment->id,
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

        return redirect()->route('teacher.attendances.show', $attendanceSession)
            ->with('success', 'Absensi berhasil diperbarui.');
    }

    public function destroy(AttendanceSession $attendanceSession)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403);
        }

        if ($attendanceSession->teacher_id !== $teacher->id) {
            abort(403, 'Anda tidak memiliki akses ke sesi absensi ini.');
        }

        $attendanceSession->delete();

        return redirect()->route('teacher.attendances.index')
            ->with('success', 'Sesi absensi berhasil dihapus.');
    }

    public function studentHistory(Student $student, Request $request)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $assignment = TeachingAssignment::with(['subject', 'schoolClass.level', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->findOrFail($request->query('teaching_assignment_id'));

        $enrolled = StudentClassEnrollment::where('student_id', $student->id)
            ->where('school_class_id', $assignment->school_class_id)
            ->where('academic_year_id', $assignment->academic_year_id)
            ->where('semester_id', $assignment->semester_id)
            ->where('is_active', true)
            ->exists();

        if (!$enrolled) {
            abort(403, 'Santri ini tidak terdaftar di kelas penugasan anda.');
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
                    ->where('school_class_id', $assignment->school_class_id)
                    ->where('academic_year_id', $activeYear->id)
                    ->where('semester_id', $activeSemester->id)
                    ->whereMonth('attendance_date', $month)
                    ->whereYear('attendance_date', $year)
                )
                ->get()
                ->groupBy(fn($d) => $d->session->attendance_date->format('Y-m-d'));

            $attendanceDetails = $attendanceDetails->sortKeys();

            return view('teacher.attendances.student', compact(
                'student', 'assignment', 'viewMode', 'month', 'year', 'attendanceDetails',
            ));
        }

        $attendances = AttendanceSession::with([
            'details' => fn($q) => $q->where('student_id', $student->id),
            'teachingAssignment.subject', 'schoolClass.level',
        ])
            ->where('teacher_id', $teacher->id)
            ->where('attendance_type', 'teaching')
            ->where('teaching_assignment_id', $assignment->id)
            ->where('school_class_id', $assignment->school_class_id)
            ->where('academic_year_id', $assignment->academic_year_id)
            ->where('semester_id', $assignment->semester_id)
            ->orderByDesc('attendance_date')
            ->paginate(15);

        $statusLabels = [
            'present' => 'Hadir',
            'permission' => 'Izin',
            'sick' => 'Sakit',
            'absent' => 'Alfa',
        ];

        return view('teacher.attendances.student', compact(
            'attendances', 'student', 'assignment', 'statusLabels', 'viewMode',
        ));
    }
}
