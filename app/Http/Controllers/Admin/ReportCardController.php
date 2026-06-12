<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentClassEnrollment;
use App\Models\TeachingAssignment;
use App\Models\TeacherJournal;
use App\Models\Attitude;
use App\Models\AttendanceDetail;
use App\Models\AttendanceSession;
use App\Models\HomeroomAssignment;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportCardController extends Controller
{
    public function index(Request $request)
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();

        $selectedAcademicYear = $request->input('academic_year_id');
        $selectedSemester = $request->input('semester_id');
        $selectedClass = $request->input('school_class_id');
        $keyword = $request->input('keyword');

        $semesters = collect();
        $schoolClasses = collect();
        $students = collect();

        if ($selectedAcademicYear) {
            $semesters = Semester::where('academic_year_id', $selectedAcademicYear)->get();
            $schoolClasses = SchoolClass::with('level')
                ->where('status', 'active')
                ->orderBy('sort_order')
                ->get();
        }

        if ($selectedAcademicYear && $selectedSemester && $selectedClass) {
            $query = StudentClassEnrollment::with(['student', 'schoolClass.level', 'academicYear', 'semester'])
                ->where('school_class_id', $selectedClass)
                ->where('academic_year_id', $selectedAcademicYear)
                ->where('semester_id', $selectedSemester)
                ->where('is_active', true);

            if ($keyword) {
                $query->whereHas('student', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('arabic_name', 'like', "%{$keyword}%")
                      ->orWhere('nis', 'like', "%{$keyword}%");
                });
            }

            $students = $query->orderBy('created_at')->get();
        }

        return view('admin.report-cards.index', compact(
            'academicYears',
            'semesters',
            'schoolClasses',
            'students',
            'selectedAcademicYear',
            'selectedSemester',
            'selectedClass',
            'keyword'
        ));
    }

    public function show(Request $request, Student $student)
    {
        $selectedAcademicYear = $request->input('academic_year_id');
        $selectedSemester = $request->input('semester_id');
        $selectedClass = $request->input('school_class_id');

        $enrollment = StudentClassEnrollment::with(['schoolClass.level', 'academicYear', 'semester'])
            ->where('student_id', $student->id)
            ->where('school_class_id', $selectedClass)
            ->where('academic_year_id', $selectedAcademicYear)
            ->where('semester_id', $selectedSemester)
            ->where('is_active', true)
            ->first();

        if (!$enrollment) {
            abort(404, 'Enrollment santri tidak ditemukan.');
        }

        $reportData = $this->buildReportData($student, $enrollment);

        return view('admin.report-cards.show', compact('reportData'));
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
