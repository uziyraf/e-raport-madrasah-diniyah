<?php

namespace App\Http\Controllers\Homeroom;

use App\Http\Controllers\Controller;
use App\Models\HomeroomAssignment;
use App\Models\Student;
use App\Models\StudentClassEnrollment;
use App\Models\TeachingAssignment;
use App\Models\TeacherJournal;
use App\Models\Attitude;
use App\Models\AttendanceDetail;
use App\Models\AttendanceSession;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportCardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $homeroom = HomeroomAssignment::with(['schoolClass.level', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->first();

        if (!$homeroom) {
            return view('homeroom.report-cards.index', [
                'homeroom' => null,
                'students' => collect(),
            ]);
        }

        $students = StudentClassEnrollment::with('student')
            ->where('school_class_id', $homeroom->school_class_id)
            ->where('academic_year_id', $homeroom->academic_year_id)
            ->where('semester_id', $homeroom->semester_id)
            ->where('is_active', true)
            ->orderBy('created_at')
            ->get();

        return view('homeroom.report-cards.index', compact('homeroom', 'students'));
    }

    public function show(Student $student)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $homeroom = HomeroomAssignment::with(['schoolClass.level', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->first();

        if (!$homeroom) {
            abort(403, 'Anda tidak memiliki akses wali kelas.');
        }

        $enrollment = StudentClassEnrollment::with('schoolClass.level', 'academicYear', 'semester')
            ->where('student_id', $student->id)
            ->where('school_class_id', $homeroom->school_class_id)
            ->where('academic_year_id', $homeroom->academic_year_id)
            ->where('semester_id', $homeroom->semester_id)
            ->where('is_active', true)
            ->first();

        if (!$enrollment) {
            abort(403, 'Santri tidak berada di kelas wali anda.');
        }

        $reportData = $this->buildReportData($student, $enrollment);

        return view('homeroom.report-cards.show', compact('reportData'));
    }

    private function buildReportData($student, $enrollment)
    {
        $schoolClass = $enrollment->schoolClass;
        $academicYear = $enrollment->academicYear;
        $semester = $enrollment->semester;

        $teachingAssignments = TeachingAssignment::with(['subject', 'teacher'])
            ->where('school_class_id', $schoolClass->id)
            ->where('academic_year_id', $academicYear->id)
            ->where('semester_id', $semester->id)
            ->orderBy('created_at')
            ->get();

        $teachingAssignmentIds = $teachingAssignments->pluck('id');

        $dailyScores = TeacherJournal::whereIn('teaching_assignment_id', $teachingAssignmentIds)
            ->where('student_id', $student->id)
            ->where('journal_type', 'nilai_harian')
            ->where('status', 'submitted')
            ->selectRaw('teaching_assignment_id, MAX(daily_score) as daily_score')
            ->groupBy('teaching_assignment_id')
            ->pluck('daily_score', 'teaching_assignment_id');

        $examScores = TeacherJournal::whereIn('teaching_assignment_id', $teachingAssignmentIds)
            ->where('student_id', $student->id)
            ->where('journal_type', 'tamrinan')
            ->where('status', 'submitted')
            ->selectRaw('teaching_assignment_id, MAX(exam_score) as exam_score')
            ->groupBy('teaching_assignment_id')
            ->pluck('exam_score', 'teaching_assignment_id');

        $attitude = Attitude::where('student_id', $student->id)
            ->where('school_class_id', $schoolClass->id)
            ->where('academic_year_id', $academicYear->id)
            ->where('semester_id', $semester->id)
            ->first();

        $sessionIds = AttendanceSession::where('school_class_id', $schoolClass->id)
            ->where('academic_year_id', $academicYear->id)
            ->where('semester_id', $semester->id)
            ->pluck('id');

        $attendanceCounts = AttendanceDetail::where('student_id', $student->id)
            ->whereIn('attendance_session_id', $sessionIds)
            ->selectRaw("
                SUM(CASE WHEN status = 'permission' THEN 1 ELSE 0 END) as permission_count,
                SUM(CASE WHEN status = 'sick' THEN 1 ELSE 0 END) as sick_count,
                SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_count
            ")
            ->first();

        $subjectRows = collect();
        $totalNumericScores = 0;
        $countNumericScores = 0;
        $dailyScoreTotal = 0;
        $examScoreTotal = 0;

        $sortedAssignments = $teachingAssignments->sortBy(function ($ta) {
            return $ta->subject->sort_order ?? 999;
        })->values();

        foreach ($sortedAssignments as $ta) {
            $daily = $dailyScores->get($ta->id);
            $exam = $examScores->get($ta->id);

            if ($daily !== null) {
                $totalNumericScores += (float) $daily;
                $countNumericScores++;
                $dailyScoreTotal += (float) $daily;
            }
            if ($exam !== null) {
                $totalNumericScores += (float) $exam;
                $countNumericScores++;
                $examScoreTotal += (float) $exam;
            }

            $subjectRows->push([
                'number' => $subjectRows->count() + 1,
                'arabic_name' => $ta->subject->arabic_name ?? $ta->subject->name,
                'daily_score' => $daily !== null ? number_format((float) $daily, 0) : '-',
                'exam_score' => $exam !== null ? number_format((float) $exam, 0) : '-',
                'teacher_arabic_name' => $ta->teacher->arabic_name ?? $ta->teacher->name,
            ]);
        }

        $average = $countNumericScores > 0
            ? number_format($totalNumericScores / $countNumericScores, 2)
            : '-';

        $homeroomAssignment = HomeroomAssignment::with('teacher')
            ->where('school_class_id', $schoolClass->id)
            ->where('academic_year_id', $academicYear->id)
            ->where('semester_id', $semester->id)
            ->first();

        $homeroomTeacher = $homeroomAssignment?->teacher;

        $principal = Teacher::whereHas('user', function ($q) {
            $q->whereHas('roles', function ($r) {
                $r->where('name', 'kepala_sekolah');
            });
        })->first();

        return [
            'student' => $student,
            'schoolClass' => $schoolClass,
            'academicYear' => $academicYear,
            'semester' => $semester,
            'subjectRows' => $subjectRows,
            'totalScore' => $countNumericScores > 0 ? number_format($totalNumericScores, 0) : '-',
            'average' => $average,
            'attitude' => $attitude,
            'permissionCount' => $attendanceCounts->permission_count ?? 0,
            'sickCount' => $attendanceCounts->sick_count ?? 0,
            'absentCount' => $attendanceCounts->absent_count ?? 0,
            'homeroomTeacher' => $homeroomTeacher,
            'principal' => $principal,
            'dailyScoreTotal' => $dailyScoreTotal,
            'examScoreTotal' => $examScoreTotal,
            'subjectCount' => $subjectRows->count(),
        ];
    }
}
