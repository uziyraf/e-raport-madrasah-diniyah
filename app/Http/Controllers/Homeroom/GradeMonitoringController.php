<?php

namespace App\Http\Controllers\Homeroom;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\HomeroomAssignment;
use App\Models\StudentClassEnrollment;
use App\Models\TeachingAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeMonitoringController extends Controller
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
            return view('homeroom.grades.index', [
                'homeroom' => null,
                'assignments' => collect(),
            ]);
        }

        $assignments = TeachingAssignment::with([
            'teacher', 'subject', 'schoolClass.level', 'academicYear', 'semester',
        ])
            ->withCount([
                'grades',
                'grades as draft_grades_count' => fn($q) => $q->where('status', 'draft'),
                'grades as submitted_grades_count' => fn($q) => $q->where('status', 'submitted'),
            ])
            ->addSelect([
                'enrolled_students_count' => StudentClassEnrollment::selectRaw('COUNT(*)')
                    ->whereColumn('school_class_id', 'teaching_assignments.school_class_id')
                    ->whereColumn('academic_year_id', 'teaching_assignments.academic_year_id')
                    ->whereColumn('semester_id', 'teaching_assignments.semester_id')
                    ->where('is_active', true),
            ])
            ->where('school_class_id', $homeroom->school_class_id)
            ->where('academic_year_id', $homeroom->academic_year_id)
            ->where('semester_id', $homeroom->semester_id)
            ->orderBy('created_at')
            ->get();

        return view('homeroom.grades.index', compact('homeroom', 'assignments'));
    }

    public function show(TeachingAssignment $teachingAssignment)
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

        if ($teachingAssignment->school_class_id !== $homeroom->school_class_id
            || $teachingAssignment->academic_year_id !== $homeroom->academic_year_id
            || $teachingAssignment->semester_id !== $homeroom->semester_id) {
            abort(403, 'Penugasan ini tidak dalam kelas wali anda.');
        }

        $assignment = $teachingAssignment->loadMissing([
            'teacher', 'subject', 'schoolClass.level', 'academicYear', 'semester',
        ]);

        $students = StudentClassEnrollment::with('student')
            ->where('school_class_id', $assignment->school_class_id)
            ->where('academic_year_id', $assignment->academic_year_id)
            ->where('semester_id', $assignment->semester_id)
            ->where('is_active', true)
            ->orderBy('created_at')
            ->get();

        $existingGrades = Grade::where('teaching_assignment_id', $assignment->id)
            ->get()
            ->keyBy('student_id');

        return view('homeroom.grades.show', compact(
            'assignment', 'students', 'existingGrades', 'homeroom'
        ));
    }
}
