<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHomeroomAssignmentRequest;
use App\Http\Requests\Admin\UpdateHomeroomAssignmentRequest;
use App\Models\AcademicYear;
use App\Models\HomeroomAssignment;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Teacher;

class HomeroomAssignmentController extends Controller
{
    public function index()
    {
        $assignments = HomeroomAssignment::with([
            'teacher', 'schoolClass.level', 'academicYear', 'semester',
        ])->orderByDesc('created_at')->paginate(10);

        return view('admin.homeroom-assignments.index', compact('assignments'));
    }

    public function create()
    {
        $teachers = Teacher::active()->orderBy('name')->pluck('name', 'id');
        $schoolClasses = SchoolClass::active()->with('level')->orderBy('sort_order')->get()
            ->mapWithKeys(fn($c) => [$c->id => $c->level->name . ' - ' . $c->name]);
        $academicYears = AcademicYear::orderByDesc('start_date')->pluck('name', 'id');
        $semesters = Semester::orderByDesc('start_date')->pluck('name', 'id');

        return view('admin.homeroom-assignments.create', compact('teachers', 'schoolClasses', 'academicYears', 'semesters'));
    }

    public function store(StoreHomeroomAssignmentRequest $request)
    {
        HomeroomAssignment::create($request->validated());

        return redirect()->route('admin.homeroom-assignments.index')
            ->with('success', 'Wali kelas berhasil ditambahkan.');
    }

    public function edit(HomeroomAssignment $homeroomAssignment)
    {
        $assignment = $homeroomAssignment->loadMissing(['teacher', 'schoolClass.level', 'academicYear', 'semester']);
        $teachers = Teacher::active()->orderBy('name')->pluck('name', 'id');
        $schoolClasses = SchoolClass::active()->with('level')->orderBy('sort_order')->get()
            ->mapWithKeys(fn($c) => [$c->id => $c->level->name . ' - ' . $c->name]);
        $academicYears = AcademicYear::orderByDesc('start_date')->pluck('name', 'id');
        $semesters = Semester::orderByDesc('start_date')->pluck('name', 'id');

        return view('admin.homeroom-assignments.edit', compact('assignment', 'teachers', 'schoolClasses', 'academicYears', 'semesters'));
    }

    public function update(UpdateHomeroomAssignmentRequest $request, HomeroomAssignment $homeroomAssignment)
    {
        $homeroomAssignment->update($request->validated());

        return redirect()->route('admin.homeroom-assignments.index')
            ->with('success', 'Wali kelas berhasil diperbarui.');
    }

    public function destroy(HomeroomAssignment $homeroomAssignment)
    {
        $homeroomAssignment->delete();

        return redirect()->route('admin.homeroom-assignments.index')
            ->with('success', 'Wali kelas berhasil dihapus.');
    }
}
