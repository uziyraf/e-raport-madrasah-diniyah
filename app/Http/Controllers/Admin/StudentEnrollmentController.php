<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentEnrollmentRequest;
use App\Http\Requests\Admin\UpdateStudentEnrollmentRequest;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentClassEnrollment;

class StudentEnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = StudentClassEnrollment::with([
            'student', 'schoolClass.level', 'academicYear', 'semester', 'creator',
        ])->orderByDesc('created_at')->paginate(10);

        return view('admin.student-enrollments.index', compact('enrollments'));
    }

    public function create()
    {
        $students = Student::orderBy('name')->pluck('name', 'id');
        $schoolClasses = SchoolClass::active()->with('level')->orderBy('sort_order')->get()
            ->mapWithKeys(fn($c) => [$c->id => $c->level->name . ' - ' . $c->name]);
        $academicYears = AcademicYear::orderByDesc('start_date')->pluck('name', 'id');
        $semesters = Semester::orderByDesc('start_date')->pluck('name', 'id');

        return view('admin.student-enrollments.create', compact('students', 'schoolClasses', 'academicYears', 'semesters'));
    }

    public function store(StoreStudentEnrollmentRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        $data['created_by'] = auth()->id();

        if ($data['is_active']) {
            StudentClassEnrollment::where('student_id', $data['student_id'])
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        StudentClassEnrollment::create($data);

        return redirect()->route('admin.student-enrollments.index')
            ->with('success', 'Penempatan santri berhasil ditambahkan.');
    }

    public function edit(StudentClassEnrollment $studentEnrollment)
    {
        $enrollment = $studentEnrollment->loadMissing(['student', 'schoolClass.level', 'academicYear', 'semester']);
        $students = Student::orderBy('name')->pluck('name', 'id');
        $schoolClasses = SchoolClass::active()->with('level')->orderBy('sort_order')->get()
            ->mapWithKeys(fn($c) => [$c->id => $c->level->name . ' - ' . $c->name]);
        $academicYears = AcademicYear::orderByDesc('start_date')->pluck('name', 'id');
        $semesters = Semester::orderByDesc('start_date')->pluck('name', 'id');

        return view('admin.student-enrollments.edit', compact('enrollment', 'students', 'schoolClasses', 'academicYears', 'semesters'));
    }

    public function update(UpdateStudentEnrollmentRequest $request, StudentClassEnrollment $studentEnrollment)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        if ($data['is_active']) {
            StudentClassEnrollment::where('student_id', $data['student_id'])
                ->where('id', '!=', $studentEnrollment->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $studentEnrollment->update($data);

        return redirect()->route('admin.student-enrollments.index')
            ->with('success', 'Penempatan santri berhasil diperbarui.');
    }

    public function destroy(StudentClassEnrollment $studentEnrollment)
    {
        $studentEnrollment->delete();

        return redirect()->route('admin.student-enrollments.index')
            ->with('success', 'Penempatan santri berhasil dihapus.');
    }
}
