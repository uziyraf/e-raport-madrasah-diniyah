<?php

namespace App\Http\Controllers\Homeroom;

use App\Http\Controllers\Controller;
use App\Models\HomeroomAssignment;
use App\Models\Student;
use App\Models\StudentClassEnrollment;
use App\Services\ReportCardService;
use Illuminate\Support\Facades\Auth;

class ReportCardController extends Controller
{
    public function __construct(private ReportCardService $reportCardService) {}

    public function index()
    {
        $teacher = Auth::user()->teacher;

        if (! $teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $homeroom = HomeroomAssignment::with(['schoolClass.level', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->whereHas('academicYear', fn ($q) => $q->where('is_active', true))
            ->whereHas('semester', fn ($q) => $q->where('is_active', true))
            ->first();

        if (! $homeroom) {
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

        if (! $teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $homeroom = HomeroomAssignment::with(['schoolClass.level', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->whereHas('academicYear', fn ($q) => $q->where('is_active', true))
            ->whereHas('semester', fn ($q) => $q->where('is_active', true))
            ->first();

        if (! $homeroom) {
            abort(403, 'Anda tidak memiliki akses wali kelas.');
        }

        $enrollment = StudentClassEnrollment::with('schoolClass.level', 'academicYear', 'semester')
            ->where('student_id', $student->id)
            ->where('school_class_id', $homeroom->school_class_id)
            ->where('academic_year_id', $homeroom->academic_year_id)
            ->where('semester_id', $homeroom->semester_id)
            ->where('is_active', true)
            ->first();

        if (! $enrollment) {
            abort(403, 'Santri tidak berada di kelas wali anda.');
        }

        $reportData = $this->reportCardService->build($student, $enrollment);

        return view('homeroom.report-cards.show', compact('reportData'));
    }
}
