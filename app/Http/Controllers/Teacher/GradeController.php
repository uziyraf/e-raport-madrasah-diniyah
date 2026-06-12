<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreGradeRequest;
use App\Models\Grade;
use App\Models\StudentClassEnrollment;
use App\Models\TeachingAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $assignments = TeachingAssignment::with([
            'subject', 'schoolClass.level', 'academicYear', 'semester',
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
            ->where('teacher_id', $teacher->id)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('teacher.grades.index', compact('assignments'));
    }

    public function create(Request $request)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $assignments = TeachingAssignment::with([
            'subject', 'schoolClass.level', 'academicYear', 'semester',
        ])
            ->where('teacher_id', $teacher->id)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->orderByDesc('created_at')
            ->get();

        $selectedAssignment = null;
        $students = collect();
        $existingGrades = collect();

        if ($request->filled('teaching_assignment_id')) {
            $selectedAssignment = $assignments->firstWhere('id', $request->teaching_assignment_id);

            if ($selectedAssignment) {
                $students = $selectedAssignment->schoolClass->classEnrollments()
                    ->where('academic_year_id', $selectedAssignment->academic_year_id)
                    ->where('semester_id', $selectedAssignment->semester_id)
                    ->where('is_active', true)
                    ->with('student')
                    ->orderBy('created_at')
                    ->get();

                $existingGrades = Grade::where('teaching_assignment_id', $selectedAssignment->id)
                    ->get()
                    ->keyBy('student_id');
            }
        }

        return view('teacher.grades.form', compact(
            'assignments', 'selectedAssignment', 'students', 'existingGrades'
        ));
    }

    public function store(StoreGradeRequest $request)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $assignment = TeachingAssignment::findOrFail($request->teaching_assignment_id);

        if ($assignment->teacher_id !== $teacher->id) {
            abort(403, 'Anda tidak memiliki akses ke penugasan ini.');
        }

        $userId = Auth::id();

        foreach ($request->grades as $gradeData) {
            $data = [
                'student_id' => $gradeData['student_id'],
                'teaching_assignment_id' => $assignment->id,
                'entered_by' => $userId,
                'score' => $gradeData['score'] ?? null,
                'predicate' => $gradeData['predicate'] ?? null,
                'note' => $gradeData['note'] ?? null,
                'status' => $gradeData['status'],
                'submitted_at' => $gradeData['status'] === 'submitted' ? now() : null,
            ];

            Grade::updateOrCreate(
                [
                    'student_id' => $gradeData['student_id'],
                    'teaching_assignment_id' => $assignment->id,
                ],
                $data
            );
        }

        return redirect()->route('teacher.grades.index')
            ->with('success', 'Nilai berhasil disimpan.');
    }

    public function edit(TeachingAssignment $teachingAssignment)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        if ($teachingAssignment->teacher_id !== $teacher->id) {
            abort(403, 'Anda tidak memiliki akses ke penugasan ini.');
        }

        $assignment = $teachingAssignment->loadMissing([
            'subject', 'schoolClass.level', 'academicYear', 'semester',
        ]);

        $students = $assignment->schoolClass->classEnrollments()
            ->where('academic_year_id', $assignment->academic_year_id)
            ->where('semester_id', $assignment->semester_id)
            ->where('is_active', true)
            ->with('student')
            ->orderBy('created_at')
            ->get();

        $existingGrades = Grade::where('teaching_assignment_id', $assignment->id)
            ->get()
            ->keyBy('student_id');

        return view('teacher.grades.form', compact(
            'assignment', 'students', 'existingGrades'
        ));
    }

    public function update(StoreGradeRequest $request, TeachingAssignment $teachingAssignment)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        if ($teachingAssignment->teacher_id !== $teacher->id) {
            abort(403, 'Anda tidak memiliki akses ke penugasan ini.');
        }

        $userId = Auth::id();

        foreach ($request->grades as $gradeData) {
            $data = [
                'student_id' => $gradeData['student_id'],
                'teaching_assignment_id' => $teachingAssignment->id,
                'entered_by' => $userId,
                'score' => $gradeData['score'] ?? null,
                'predicate' => $gradeData['predicate'] ?? null,
                'note' => $gradeData['note'] ?? null,
                'status' => $gradeData['status'],
                'submitted_at' => $gradeData['status'] === 'submitted' ? now() : null,
            ];

            Grade::updateOrCreate(
                [
                    'student_id' => $gradeData['student_id'],
                    'teaching_assignment_id' => $teachingAssignment->id,
                ],
                $data
            );
        }

        return redirect()->route('teacher.grades.index')
            ->with('success', 'Nilai berhasil diperbarui.');
    }
}
