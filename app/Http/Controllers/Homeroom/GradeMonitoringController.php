<?php

namespace App\Http\Controllers\Homeroom;

use App\Http\Controllers\Controller;
use App\Http\Requests\Homeroom\UpdateGradeRequest;
use App\Models\Grade;
use App\Models\HomeroomAssignment;
use App\Models\StudentClassEnrollment;
use App\Models\TeachingAssignment;
use App\Services\TeachingAssignmentSyncService;
use Illuminate\Support\Facades\Auth;

class GradeMonitoringController extends Controller
{
    public function __construct(private TeachingAssignmentSyncService $assignmentsSync) {}

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
            return view('homeroom.grades.index', [
                'homeroom' => null,
                'assignments' => collect(),
            ]);
        }

        $assignmentIds = $this->assignmentsSync->idsForClass(
            $homeroom->school_class_id,
            $homeroom->academic_year_id,
            $homeroom->semester_id,
        );

        $assignments = TeachingAssignment::with([
            'teacher', 'subject', 'schoolClass.level', 'academicYear', 'semester',
        ])
            ->withCount([
                'grades',
                'grades as draft_grades_count' => fn ($q) => $q->where('status', 'draft'),
                'grades as submitted_grades_count' => fn ($q) => $q->where('status', 'submitted'),
            ])
            ->addSelect([
                'enrolled_students_count' => StudentClassEnrollment::selectRaw('COUNT(*)')
                    ->whereColumn('school_class_id', 'teaching_assignments.school_class_id')
                    ->whereColumn('academic_year_id', 'teaching_assignments.academic_year_id')
                    ->whereColumn('semester_id', 'teaching_assignments.semester_id')
                    ->where('is_active', true),
            ])
            ->whereIn('id', $assignmentIds)
            ->orderBy('created_at')
            ->get();

        return view('homeroom.grades.index', compact('homeroom', 'assignments'));
    }

    public function show(TeachingAssignment $teachingAssignment)
    {
        $homeroom = $this->authorizeHomeroom($teachingAssignment);

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

    public function edit(TeachingAssignment $teachingAssignment)
    {
        $homeroom = $this->authorizeHomeroom($teachingAssignment);

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

        return view('homeroom.grades.form', compact(
            'assignment', 'students', 'existingGrades', 'homeroom'
        ));
    }

    public function update(UpdateGradeRequest $request, TeachingAssignment $teachingAssignment)
    {
        $this->authorizeHomeroom($teachingAssignment);

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

        return redirect()->route('homeroom.grades.show', $teachingAssignment)
            ->with('success', 'Nilai santri berhasil diperbarui oleh Wali Kelas.');
    }

    private function authorizeHomeroom(TeachingAssignment $teachingAssignment): HomeroomAssignment
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

        if (! $this->assignmentsSync->isFromJadwal($teachingAssignment)) {
            abort(403, 'Penugasan ini tidak terdaftar di jadwal pelajaran.');
        }

        if ($teachingAssignment->school_class_id !== $homeroom->school_class_id
            || $teachingAssignment->academic_year_id !== $homeroom->academic_year_id
            || $teachingAssignment->semester_id !== $homeroom->semester_id) {
            abort(403, 'Penugasan ini tidak dalam kelas wali anda.');
        }

        return $homeroom;
    }
}
