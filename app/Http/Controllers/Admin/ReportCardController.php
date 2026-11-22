<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentClassEnrollment;
use App\Services\ReportCardService;
use Illuminate\Http\Request;

class ReportCardController extends Controller
{
    public function __construct(private ReportCardService $reportCardService) {}

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

        if (! $enrollment) {
            abort(404, 'Enrollment santri tidak ditemukan.');
        }

        $reportData = $this->reportCardService->build($student, $enrollment);

        return view('admin.report-cards.show', compact('reportData'));
    }
}
