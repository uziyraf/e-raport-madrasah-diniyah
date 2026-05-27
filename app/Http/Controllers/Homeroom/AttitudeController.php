<?php

namespace App\Http\Controllers\Homeroom;

use App\Http\Controllers\Controller;
use App\Http\Requests\Homeroom\UpdateAttitudeRequest;
use App\Models\Attitude;
use App\Models\HomeroomAssignment;
use App\Models\Student;
use App\Models\StudentClassEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttitudeController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            return view('homeroom.attitudes.index', [
                'homeroom' => null,
                'students' => collect(),
                'existingAttitudes' => collect(),
                'filledCount' => 0,
            ]);
        }

        $homeroom = HomeroomAssignment::with(['schoolClass.level', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->first();

        if (!$homeroom) {
            return view('homeroom.attitudes.index', [
                'homeroom' => null,
                'students' => collect(),
                'existingAttitudes' => collect(),
                'filledCount' => 0,
            ]);
        }

        $students = StudentClassEnrollment::with('student')
            ->where('school_class_id', $homeroom->school_class_id)
            ->where('academic_year_id', $homeroom->academic_year_id)
            ->where('semester_id', $homeroom->semester_id)
            ->where('is_active', true)
            ->orderBy('created_at')
            ->get();

        $existingAttitudes = Attitude::where('school_class_id', $homeroom->school_class_id)
            ->where('academic_year_id', $homeroom->academic_year_id)
            ->where('semester_id', $homeroom->semester_id)
            ->get()
            ->keyBy('student_id');

        $filledCount = $existingAttitudes->count();

        return view('homeroom.attitudes.index', compact(
            'homeroom', 'students', 'existingAttitudes', 'filledCount'
        ));
    }

    public function edit(Student $student)
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

        $enrollment = StudentClassEnrollment::where('student_id', $student->id)
            ->where('school_class_id', $homeroom->school_class_id)
            ->where('academic_year_id', $homeroom->academic_year_id)
            ->where('semester_id', $homeroom->semester_id)
            ->where('is_active', true)
            ->first();

        if (!$enrollment) {
            abort(403, 'Santri tidak terdaftar di kelas wali anda.');
        }

        $attitude = Attitude::where('student_id', $student->id)
            ->where('academic_year_id', $homeroom->academic_year_id)
            ->where('semester_id', $homeroom->semester_id)
            ->first();

        return view('homeroom.attitudes.edit', compact(
            'student', 'homeroom', 'attitude'
        ));
    }

    public function update(UpdateAttitudeRequest $request, Student $student)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $homeroom = HomeroomAssignment::where('teacher_id', $teacher->id)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->first();

        if (!$homeroom) {
            abort(403, 'Anda tidak memiliki akses wali kelas.');
        }

        $enrollment = StudentClassEnrollment::where('student_id', $student->id)
            ->where('school_class_id', $homeroom->school_class_id)
            ->where('academic_year_id', $homeroom->academic_year_id)
            ->where('semester_id', $homeroom->semester_id)
            ->where('is_active', true)
            ->exists();

        if (!$enrollment) {
            abort(403, 'Santri tidak terdaftar di kelas wali anda.');
        }

        Attitude::updateOrCreate(
            [
                'student_id' => $student->id,
                'academic_year_id' => $homeroom->academic_year_id,
                'semester_id' => $homeroom->semester_id,
            ],
            [
                'school_class_id' => $homeroom->school_class_id,
                'homeroom_teacher_id' => $teacher->id,
                'akhlak' => $request->akhlak,
                'discipline' => $request->discipline,
                'cleanliness' => $request->cleanliness,
                'attitude_note' => $request->attitude_note,
            ]
        );

        return redirect()->route('homeroom.attitudes.index')
            ->with('success', 'Nilai sikap berhasil disimpan.');
    }
}
