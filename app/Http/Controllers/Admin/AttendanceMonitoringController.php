<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AttendanceDetail;
use App\Models\AttendanceSession;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentClassEnrollment;
use App\Models\TeachingAssignment;
use Illuminate\Http\Request;

class AttendanceMonitoringController extends Controller
{
    public function index()
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->where('is_active', true)->firstOrFail();

        $classes = SchoolClass::active()
            ->with('level')
            ->orderBy('sort_order')
            ->paginate(15);

        $classIds = $classes->pluck('id');

        $enrollmentCounts = StudentClassEnrollment::selectRaw('
                school_class_id, COUNT(*) as total
            ')
            ->where('academic_year_id', $activeYear->id)
            ->where('semester_id', $activeSemester->id)
            ->where('is_active', true)
            ->whereIn('school_class_id', $classIds)
            ->groupBy('school_class_id')
            ->pluck('total', 'school_class_id');

        $sessionCounts = AttendanceSession::selectRaw('
                school_class_id,
                attendance_type,
                COUNT(*) as total
            ')
            ->where('academic_year_id', $activeYear->id)
            ->where('semester_id', $activeSemester->id)
            ->whereIn('school_class_id', $classIds)
            ->groupBy('school_class_id', 'attendance_type')
            ->get()
            ->groupBy('school_class_id');

        $detailCounts = AttendanceDetail::selectRaw('
                attendance_sessions.school_class_id,
                attendance_details.status,
                COUNT(*) as total
            ')
            ->join('attendance_sessions', 'attendance_sessions.id', '=', 'attendance_details.attendance_session_id')
            ->where('attendance_sessions.academic_year_id', $activeYear->id)
            ->where('attendance_sessions.semester_id', $activeSemester->id)
            ->whereIn('attendance_sessions.school_class_id', $classIds)
            ->groupBy('attendance_sessions.school_class_id', 'attendance_details.status')
            ->get()
            ->groupBy('school_class_id');

        $sessionStatusCounts = AttendanceSession::selectRaw('
                school_class_id,
                status,
                COUNT(*) as total
            ')
            ->where('academic_year_id', $activeYear->id)
            ->where('semester_id', $activeSemester->id)
            ->whereIn('school_class_id', $classIds)
            ->groupBy('school_class_id', 'status')
            ->get()
            ->groupBy('school_class_id');

        return view('admin.attendances.index', compact(
            'classes', 'activeYear', 'activeSemester',
            'enrollmentCounts', 'sessionCounts', 'detailCounts',
            'sessionStatusCounts',
        ));
    }

    public function classDetail(SchoolClass $schoolClass)
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->where('is_active', true)->firstOrFail();

        $schoolClass->loadMissing('level');

        $homeroom = $schoolClass->homeroomAssignments()
            ->with('teacher')
            ->where('academic_year_id', $activeYear->id)
            ->where('semester_id', $activeSemester->id)
            ->first();

        $homeroomSessions = AttendanceSession::where('attendance_type', 'homeroom')
            ->where('school_class_id', $schoolClass->id)
            ->where('academic_year_id', $activeYear->id)
            ->where('semester_id', $activeSemester->id);

        $homeroomSessionCount = (clone $homeroomSessions)->count();
        $homeroomLatestDate = (clone $homeroomSessions)->max('attendance_date');

        $homeroomDetailCounts = AttendanceDetail::selectRaw('
                status, COUNT(*) as total
            ')
            ->whereHas('session', fn($q) => $q
                ->where('attendance_type', 'homeroom')
                ->where('school_class_id', $schoolClass->id)
                ->where('academic_year_id', $activeYear->id)
                ->where('semester_id', $activeSemester->id)
            )
            ->groupBy('status')
            ->pluck('total', 'status');

        $teachingAssignments = TeachingAssignment::with(['teacher', 'subject', 'schoolClass.level'])
            ->where('school_class_id', $schoolClass->id)
            ->where('academic_year_id', $activeYear->id)
            ->where('semester_id', $activeSemester->id)
            ->orderBy('subject_id')
            ->get();

        $teachingSummaries = [];
        foreach ($teachingAssignments as $assignment) {
            $sessions = AttendanceSession::where('attendance_type', 'teaching')
                ->where('teaching_assignment_id', $assignment->id)
                ->where('school_class_id', $schoolClass->id)
                ->where('academic_year_id', $activeYear->id)
                ->where('semester_id', $activeSemester->id);

            $sessionCount = (clone $sessions)->count();
            $latestDate = (clone $sessions)->max('attendance_date');

            $detailCounts = AttendanceDetail::selectRaw('
                    status, COUNT(*) as total
                ')
                ->whereHas('session', fn($q) => $q
                    ->where('attendance_type', 'teaching')
                    ->where('teaching_assignment_id', $assignment->id)
                    ->where('school_class_id', $schoolClass->id)
                    ->where('academic_year_id', $activeYear->id)
                    ->where('semester_id', $activeSemester->id)
                )
                ->groupBy('status')
                ->pluck('total', 'status');

            $teachingSummaries[] = [
                'assignment' => $assignment,
                'session_count' => $sessionCount,
                'latest_date' => $latestDate,
                'present' => $detailCounts->get('present', 0),
                'permission' => $detailCounts->get('permission', 0),
                'sick' => $detailCounts->get('sick', 0),
                'absent' => $detailCounts->get('absent', 0),
            ];
        }

        return view('admin.attendances.class', compact(
            'schoolClass', 'activeYear', 'activeSemester',
            'homeroom', 'homeroomSessionCount', 'homeroomLatestDate', 'homeroomDetailCounts',
            'teachingSummaries',
        ));
    }

    public function homeroom(SchoolClass $schoolClass)
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->where('is_active', true)->firstOrFail();

        $schoolClass->loadMissing('level');

        $students = Student::whereHas('classEnrollments', fn($q) => $q
            ->where('school_class_id', $schoolClass->id)
            ->where('academic_year_id', $activeYear->id)
            ->where('semester_id', $activeSemester->id)
            ->where('is_active', true)
        )
            ->orderBy('name')
            ->paginate(20);

        $summaryQuery = AttendanceDetail::selectRaw('
                student_id,
                COUNT(*) as total_sessions,
                SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN status = "permission" THEN 1 ELSE 0 END) as permission_count,
                SUM(CASE WHEN status = "sick" THEN 1 ELSE 0 END) as sick_count,
                SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent_count
            ')
            ->whereHas('session', fn($q) => $q
                ->where('attendance_type', 'homeroom')
                ->where('school_class_id', $schoolClass->id)
                ->where('academic_year_id', $activeYear->id)
                ->where('semester_id', $activeSemester->id)
            )
            ->groupBy('student_id')
            ->get()
            ->keyBy('student_id');

        return view('admin.attendances.homeroom', compact(
            'schoolClass', 'activeYear', 'activeSemester', 'students', 'summaryQuery',
        ));
    }

    public function teaching(SchoolClass $schoolClass, TeachingAssignment $teachingAssignment)
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->where('is_active', true)->firstOrFail();

        if ($teachingAssignment->school_class_id !== $schoolClass->id) {
            abort(404);
        }

        $teachingAssignment->loadMissing(['teacher', 'subject', 'schoolClass.level']);

        $students = Student::whereHas('classEnrollments', fn($q) => $q
            ->where('school_class_id', $schoolClass->id)
            ->where('academic_year_id', $activeYear->id)
            ->where('semester_id', $activeSemester->id)
            ->where('is_active', true)
        )
            ->orderBy('name')
            ->paginate(20);

        $summaryQuery = AttendanceDetail::selectRaw('
                student_id,
                COUNT(*) as total_sessions,
                SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN status = "permission" THEN 1 ELSE 0 END) as permission_count,
                SUM(CASE WHEN status = "sick" THEN 1 ELSE 0 END) as sick_count,
                SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent_count
            ')
            ->whereHas('session', fn($q) => $q
                ->where('attendance_type', 'teaching')
                ->where('teaching_assignment_id', $teachingAssignment->id)
                ->where('school_class_id', $schoolClass->id)
                ->where('academic_year_id', $activeYear->id)
                ->where('semester_id', $activeSemester->id)
            )
            ->groupBy('student_id')
            ->get()
            ->keyBy('student_id');

        return view('admin.attendances.teaching', compact(
            'schoolClass', 'activeYear', 'activeSemester',
            'teachingAssignment', 'students', 'summaryQuery',
        ));
    }

    public function student(SchoolClass $schoolClass, Student $student)
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->where('is_active', true)->firstOrFail();

        $enrolled = StudentClassEnrollment::where('student_id', $student->id)
            ->where('school_class_id', $schoolClass->id)
            ->where('academic_year_id', $activeYear->id)
            ->where('semester_id', $activeSemester->id)
            ->where('is_active', true)
            ->exists();

        if (!$enrolled) {
            abort(404);
        }

        $schoolClass->loadMissing('level');

        $viewMode = request('view', 'table');

        if ($viewMode === 'calendar') {
            $month = (int) request('month', now()->month);
            $year = (int) request('year', now()->year);

            $attendanceDetails = AttendanceDetail::with([
                'session.teachingAssignment.subject',
                'session.teacher',
            ])
                ->where('student_id', $student->id)
                ->whereHas('session', fn($q) => $q
                    ->where('school_class_id', $schoolClass->id)
                    ->where('academic_year_id', $activeYear->id)
                    ->where('semester_id', $activeSemester->id)
                    ->whereMonth('attendance_date', $month)
                    ->whereYear('attendance_date', $year)
                )
                ->get()
                ->groupBy(fn($d) => $d->session->attendance_date->format('Y-m-d'));

            $attendanceDetails = $attendanceDetails->sortKeys();

            return view('admin.attendances.student', compact(
                'student', 'schoolClass', 'activeYear', 'activeSemester',
                'viewMode', 'month', 'year', 'attendanceDetails',
            ));
        }

        $attendances = AttendanceSession::with([
            'details' => fn($q) => $q->where('student_id', $student->id),
            'teachingAssignment.subject',
            'teacher',
        ])
            ->where('school_class_id', $schoolClass->id)
            ->where('academic_year_id', $activeYear->id)
            ->where('semester_id', $activeSemester->id)
            ->orderByDesc('attendance_date')
            ->paginate(20);

        $statusLabels = [
            'present' => 'Hadir',
            'permission' => 'Izin',
            'sick' => 'Sakit',
            'absent' => 'Alfa',
        ];

        $attendanceTypes = [
            'homeroom' => 'Absensi Kelas',
            'teaching' => 'Absensi Mengajar',
        ];

        return view('admin.attendances.student', compact(
            'attendances', 'student', 'schoolClass', 'activeYear', 'activeSemester',
            'statusLabels', 'attendanceTypes', 'viewMode',
        ));
    }
}
