<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
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
            ->with('activeEnrollment.schoolClass.level')
            ->orderBy('name')
            ->paginate(10);

        return view('guardian.students.index', compact('students', 'guardian'));
    }

    public function show(Student $student)
    {
        $guardian = Guardian::where('user_id', auth()->id())->first();

        abort_if(!$guardian || !$guardian->students()->where('student_id', $student->id)->exists(), 403);

        $student->loadMissing(['activeEnrollment.schoolClass.level']);

        return view('guardian.students.show', compact('student', 'guardian'));
    }
}
