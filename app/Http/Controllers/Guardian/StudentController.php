<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AttendanceDetail;
use App\Models\Guardian;
use App\Models\Semester;
use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        $guardian = Guardian::where('user_id', auth()->id())->first();

        if (!$guardian || $guardian->students()->count() === 0) {
            return view('guardian.students.index', [
                'students' => collect(),
                'guardian' => $guardian,
            ]);
        }

        $students = $guardian->students()
            ->with('activeEnrollment.schoolClass.level', 'activeEnrollment.academicYear', 'activeEnrollment.semester')
            ->orderBy('name')
            ->paginate(10);

        return view('guardian.students.index', compact('students', 'guardian'));
    }

    public function show(Student $student)
    {
        $guardian = Guardian::where('user_id', auth()->id())->first();

        abort_if(!$guardian || !$guardian->students()->where('student_id', $student->id)->exists(), 403);

        $student->loadMissing([
            'activeEnrollment.schoolClass.level',
            'activeEnrollment.academicYear',
            'activeEnrollment.semester',
            'guardians' => fn($q) => $q->where('guardian_students.guardian_id', $guardian->id),
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->where('is_active', true)->first();

        $attendanceSummary = [];
        if ($activeYear && $activeSemester) {
            $counts = AttendanceDetail::selectRaw('status, COUNT(*) as total')
                ->where('student_id', $student->id)
                ->whereHas('session', fn($q) => $q
                    ->where('academic_year_id', $activeYear->id)
                    ->where('semester_id', $activeSemester->id)
                )
                ->groupBy('status')
                ->pluck('total', 'status');

            $attendanceSummary = [
                'present' => $counts->get('present', 0),
                'sick' => $counts->get('sick', 0),
                'permission' => $counts->get('permission', 0),
                'absent' => $counts->get('absent', 0),
            ];
        }

        return view('guardian.students.show', compact(
            'student', 'guardian', 'activeYear', 'activeSemester', 'attendanceSummary',
        ));
    }
}
