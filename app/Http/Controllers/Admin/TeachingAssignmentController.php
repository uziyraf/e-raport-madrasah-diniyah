<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTeachingAssignmentRequest;
use App\Http\Requests\Admin\UpdateTeachingAssignmentRequest;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeachingAssignment;

class TeachingAssignmentController extends Controller
{
    public function index()
    {
        $assignments = TeachingAssignment::with([
            'teacher', 'subject', 'schoolClass.level', 'academicYear', 'semester',
        ])->orderByDesc('created_at')->paginate(10);

        return view('admin.teaching-assignments.index', compact('assignments'));
    }

    public function create()
    {
        $teachers = Teacher::active()->orderBy('name')->pluck('name', 'id');
        $subjects = Subject::orderBy('name')->pluck('name', 'id');
        $schoolClasses = SchoolClass::active()->with('level')->orderBy('sort_order')->get()
            ->mapWithKeys(fn($c) => [$c->id => $c->level->name . ' - ' . $c->name]);
        $academicYears = AcademicYear::orderByDesc('start_date')->pluck('name', 'id');
        $semesters = Semester::orderByDesc('start_date')->pluck('name', 'id');

        return view('admin.teaching-assignments.create', compact('teachers', 'subjects', 'schoolClasses', 'academicYears', 'semesters'));
    }

    public function store(StoreTeachingAssignmentRequest $request)
    {
        TeachingAssignment::create($request->validated());

        return redirect()->route('admin.teaching-assignments.index')
            ->with('success', 'Penugasan guru fan berhasil ditambahkan.');
    }

    public function edit(TeachingAssignment $teachingAssignment)
    {
        $assignment = $teachingAssignment->loadMissing(['teacher', 'subject', 'schoolClass.level', 'academicYear', 'semester']);
        $teachers = Teacher::active()->orderBy('name')->pluck('name', 'id');
        $subjects = Subject::orderBy('name')->pluck('name', 'id');
        $schoolClasses = SchoolClass::active()->with('level')->orderBy('sort_order')->get()
            ->mapWithKeys(fn($c) => [$c->id => $c->level->name . ' - ' . $c->name]);
        $academicYears = AcademicYear::orderByDesc('start_date')->pluck('name', 'id');
        $semesters = Semester::orderByDesc('start_date')->pluck('name', 'id');

        return view('admin.teaching-assignments.edit', compact('assignment', 'teachers', 'subjects', 'schoolClasses', 'academicYears', 'semesters'));
    }

    public function update(UpdateTeachingAssignmentRequest $request, TeachingAssignment $teachingAssignment)
    {
        $teachingAssignment->update($request->validated());

        return redirect()->route('admin.teaching-assignments.index')
            ->with('success', 'Penugasan guru fan berhasil diperbarui.');
    }

    public function destroy(TeachingAssignment $teachingAssignment)
    {
        $teachingAssignment->delete();

        return redirect()->route('admin.teaching-assignments.index')
            ->with('success', 'Penugasan guru fan berhasil dihapus.');
    }
}
