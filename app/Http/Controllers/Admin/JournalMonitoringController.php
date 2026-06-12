<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Level;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentClassEnrollment;
use App\Models\TeacherJournal;
use Illuminate\Http\Request;

class JournalMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->where('is_active', true)->firstOrFail();

        $levels = Level::active()->orderBy('sort_order')->pluck('name', 'id');

        $gradeLevels = SchoolClass::active()
            ->whereNotNull('grade_level')
            ->distinct()
            ->orderBy('grade_level')
            ->pluck('grade_level');

        $classes = SchoolClass::active()
            ->with('level')
            ->orderBy('sort_order')
            ->when($request->filled('level_id'), fn($q) => $q->where('level_id', $request->level_id))
            ->when($request->filled('grade_level'), fn($q) => $q->where('grade_level', $request->grade_level))
            ->when($request->filled('keyword'), function ($q) use ($request) {
                $keyword = $request->keyword;
                $q->where(function ($sq) use ($keyword) {
                    $sq->where('name', 'like', "%{$keyword}%")
                      ->orWhere('code', 'like', "%{$keyword}%")
                      ->orWhere('parallel_name', 'like', "%{$keyword}%")
                      ->orWhere('grade_level', 'like', "%{$keyword}%")
                      ->orWhereHas('level', fn($lq) => $lq->where('name', 'like', "%{$keyword}%"));
                });
            })
            ->paginate(15);

        $classIds = $classes->pluck('id');

        $enrollmentCounts = StudentClassEnrollment::selectRaw('school_class_id, COUNT(*) as total')
            ->where('academic_year_id', $activeYear->id)
            ->where('semester_id', $activeSemester->id)
            ->where('is_active', true)
            ->whereIn('school_class_id', $classIds)
            ->groupBy('school_class_id')
            ->pluck('total', 'school_class_id');

        $journalTotals = TeacherJournal::selectRaw('
                school_class_id,
                journal_type,
                COUNT(*) as total
            ')
            ->where('academic_year_id', $activeYear->id)
            ->where('semester_id', $activeSemester->id)
            ->whereIn('school_class_id', $classIds)
            ->groupBy('school_class_id', 'journal_type')
            ->get()
            ->groupBy('school_class_id');

        $classJournalCounts = TeacherJournal::selectRaw('
                school_class_id,
                COUNT(*) as total,
                MAX(journal_date) as latest_date
            ')
            ->where('academic_year_id', $activeYear->id)
            ->where('semester_id', $activeSemester->id)
            ->whereIn('school_class_id', $classIds)
            ->groupBy('school_class_id')
            ->get()
            ->keyBy('school_class_id');

        $journalTypes = [
            'hafalan' => 'Hafalan',
            'legalisir_kitab' => 'Legalisir Kitab',
            'nilai_harian' => 'Nilai Harian',
            'tamrinan' => 'Tamrinan',
            'catatan' => 'Catatan',
        ];

        return view('admin.journals.index', compact(
            'classes', 'activeYear', 'activeSemester', 'levels', 'gradeLevels',
            'enrollmentCounts', 'journalTotals', 'classJournalCounts', 'journalTypes',
        ));
    }

    public function classDetail(SchoolClass $schoolClass)
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->where('is_active', true)->firstOrFail();

        $schoolClass->loadMissing('level');

        $enrollmentTotal = StudentClassEnrollment::where('school_class_id', $schoolClass->id)
            ->where('academic_year_id', $activeYear->id)
            ->where('semester_id', $activeSemester->id)
            ->where('is_active', true)
            ->count();

        $journalTypes = [
            'hafalan' => 'Hafalan',
            'legalisir_kitab' => 'Legalisir Kitab',
            'nilai_harian' => 'Nilai Harian',
            'tamrinan' => 'Tamrinan',
            'catatan' => 'Catatan',
        ];

        $typeStats = [];
        foreach (array_keys($journalTypes) as $type) {
            $query = TeacherJournal::where('school_class_id', $schoolClass->id)
                ->where('academic_year_id', $activeYear->id)
                ->where('semester_id', $activeSemester->id)
                ->where('journal_type', $type);

            $totalRecords = (clone $query)->count();
            $latestDate = (clone $query)->max('journal_date');
            $draftCount = (clone $query)->where('status', 'draft')->count();
            $submittedCount = (clone $query)->where('status', 'submitted')->count();
            $studentCount = (clone $query)->distinct('student_id')->count('student_id');

            $typeStats[$type] = [
                'total_records' => $totalRecords,
                'latest_date' => $latestDate,
                'draft_count' => $draftCount,
                'submitted_count' => $submittedCount,
                'student_count' => $studentCount,
            ];
        }

        return view('admin.journals.class', compact(
            'schoolClass', 'activeYear', 'activeSemester', 'enrollmentTotal',
            'journalTypes', 'typeStats',
        ));
    }

    public function typeStudents(SchoolClass $schoolClass, string $journalType)
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->where('is_active', true)->firstOrFail();

        $validTypes = ['hafalan', 'legalisir_kitab', 'nilai_harian', 'tamrinan', 'catatan'];
        if (!in_array($journalType, $validTypes)) {
            abort(404);
        }

        $schoolClass->loadMissing('level');

        $journalTypes = [
            'hafalan' => 'Hafalan',
            'legalisir_kitab' => 'Legalisir Kitab',
            'nilai_harian' => 'Nilai Harian',
            'tamrinan' => 'Tamrinan',
            'catatan' => 'Catatan',
        ];

        $students = Student::whereHas('classEnrollments', fn($q) => $q
            ->where('school_class_id', $schoolClass->id)
            ->where('academic_year_id', $activeYear->id)
            ->where('semester_id', $activeSemester->id)
            ->where('is_active', true)
        )
            ->when(request('search'), function ($q) {
                $s = request('search');
                $q->where(function ($sq) use ($s) {
                    $sq->where('name', 'like', "%{$s}%")
                      ->orWhere('nis', 'like', "%{$s}%");
                });
            })
            ->orderBy('name')
            ->paginate(20);

        $studentJournalCounts = TeacherJournal::selectRaw('
                student_id,
                COUNT(*) as total_records,
                MAX(journal_date) as latest_date
            ')
            ->where('school_class_id', $schoolClass->id)
            ->where('academic_year_id', $activeYear->id)
            ->where('semester_id', $activeSemester->id)
            ->where('journal_type', $journalType)
            ->groupBy('student_id')
            ->get()
            ->keyBy('student_id');

        return view('admin.journals.type', compact(
            'schoolClass', 'activeYear', 'activeSemester',
            'journalType', 'journalTypes', 'students', 'studentJournalCounts',
        ));
    }

    public function studentHistory(SchoolClass $schoolClass, string $journalType, Student $student)
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        $activeSemester = Semester::whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->where('is_active', true)->firstOrFail();

        $validTypes = ['hafalan', 'legalisir_kitab', 'nilai_harian', 'tamrinan', 'catatan'];
        if (!in_array($journalType, $validTypes)) {
            abort(404);
        }

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

        $journalTypes = [
            'hafalan' => 'Hafalan',
            'legalisir_kitab' => 'Legalisir Kitab',
            'nilai_harian' => 'Nilai Harian',
            'tamrinan' => 'Tamrinan',
            'catatan' => 'Catatan',
        ];

        $journals = TeacherJournal::with([
            'teacher', 'teachingAssignment.subject',
        ])
            ->where('school_class_id', $schoolClass->id)
            ->where('academic_year_id', $activeYear->id)
            ->where('semester_id', $activeSemester->id)
            ->where('journal_type', $journalType)
            ->where('student_id', $student->id)
            ->orderByDesc('journal_date')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.journals.student', compact(
            'journals', 'student', 'schoolClass', 'activeYear', 'activeSemester',
            'journalType', 'journalTypes',
        ));
    }

    public function show(TeacherJournal $teacherJournal)
    {
        $journal = $teacherJournal->loadMissing([
            'teacher', 'teachingAssignment.subject', 'schoolClass.level',
            'academicYear', 'semester', 'student',
        ]);

        $journalTypes = [
            'hafalan' => 'Hafalan',
            'legalisir_kitab' => 'Legalisir Kitab',
            'nilai_harian' => 'Nilai Harian',
            'tamrinan' => 'Tamrinan',
            'catatan' => 'Catatan',
        ];

        return view('admin.journals.show', compact('journal', 'journalTypes'));
    }
}
