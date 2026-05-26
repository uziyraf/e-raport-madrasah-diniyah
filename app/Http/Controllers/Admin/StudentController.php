<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentRequest;
use App\Http\Requests\Admin\UpdateStudentRequest;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentClassEnrollment;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('activeEnrollment.schoolClass.level')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $academicYears = AcademicYear::orderByDesc('start_date')->pluck('name', 'id');
        $semesters = Semester::orderByDesc('start_date')->pluck('name', 'id');
        $schoolClasses = SchoolClass::active()->with('level')->orderBy('sort_order')->get()
            ->mapWithKeys(fn($c) => [$c->id => $c->level->name . ' - ' . $c->name]);

        return view('admin.students.create', compact('academicYears', 'semesters', 'schoolClasses'));
    }

    public function store(StoreStudentRequest $request)
    {
        $data = $request->validated();
        $placementData = null;

        if ($request->filled('school_class_id')) {
            $placementData = [
                'school_class_id' => $request->school_class_id,
                'academic_year_id' => $request->academic_year_id,
                'semester_id' => $request->semester_id,
            ];
        }

        unset($data['school_class_id'], $data['academic_year_id'], $data['semester_id']);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('student-photos', 'public');
        }

        $student = Student::create($data);

        if ($placementData) {
            StudentClassEnrollment::create([
                'student_id' => $student->id,
                'school_class_id' => $placementData['school_class_id'],
                'academic_year_id' => $placementData['academic_year_id'],
                'semester_id' => $placementData['semester_id'],
                'enrollment_status' => 'active',
                'is_active' => true,
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->route('admin.students.index')->with('success', 'Santri berhasil ditambahkan.');
    }

    public function show(Student $student)
    {
        $student->loadMissing(['activeEnrollment.schoolClass.level', 'activeEnrollment.academicYear', 'activeEnrollment.semester']);

        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $student->loadMissing('activeEnrollment');
        $academicYears = AcademicYear::orderByDesc('start_date')->pluck('name', 'id');
        $semesters = Semester::orderByDesc('start_date')->pluck('name', 'id');
        $schoolClasses = SchoolClass::active()->with('level')->orderBy('sort_order')->get()
            ->mapWithKeys(fn($c) => [$c->id => $c->level->name . ' - ' . $c->name]);

        return view('admin.students.edit', compact('student', 'academicYears', 'semesters', 'schoolClasses'));
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $data = $request->validated();
        $placementData = null;

        if ($request->filled('school_class_id')) {
            $placementData = [
                'school_class_id' => $request->school_class_id,
                'academic_year_id' => $request->academic_year_id,
                'semester_id' => $request->semester_id,
            ];
        }

        unset($data['school_class_id'], $data['academic_year_id'], $data['semester_id']);

        if ($request->hasFile('photo')) {
            if ($student->photo_path) {
                Storage::disk('public')->delete($student->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('student-photos', 'public');
        }

        $student->update($data);

        if ($placementData) {
            $student->classEnrollments()->where('is_active', true)->update(['is_active' => false]);

            $existing = $student->classEnrollments()
                ->where('school_class_id', $placementData['school_class_id'])
                ->where('academic_year_id', $placementData['academic_year_id'])
                ->where('semester_id', $placementData['semester_id'])
                ->first();

            if ($existing) {
                $existing->update(['is_active' => true, 'enrollment_status' => 'active']);
            } else {
                StudentClassEnrollment::create([
                    'student_id' => $student->id,
                    'school_class_id' => $placementData['school_class_id'],
                    'academic_year_id' => $placementData['academic_year_id'],
                    'semester_id' => $placementData['semester_id'],
                    'enrollment_status' => 'active',
                    'is_active' => true,
                    'created_by' => auth()->id(),
                ]);
            }
        }

        return redirect()->route('admin.students.index')->with('success', 'Santri berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        $student->update(['status' => 'inactive']);

        return redirect()->route('admin.students.index')->with('success', 'Santri berhasil dinonaktifkan.');
    }
}
