<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSemesterRequest;
use App\Http\Requests\Admin\UpdateSemesterRequest;
use App\Models\AcademicYear;
use App\Models\Semester;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::with('academicYear')->orderBy('start_date', 'desc')->paginate(10);
        return view('admin.semesters.index', compact('semesters'));
    }

    public function create()
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        return view('admin.semesters.create', compact('academicYears'));
    }

    public function store(StoreSemesterRequest $request)
    {
        $data = $request->validated();

        if ($data['is_active'] ?? false) {
            Semester::where('is_active', true)->update(['is_active' => false]);
        }

        Semester::create($data);

        return redirect()->route('admin.semesters.index')->with('success', 'Semester berhasil ditambahkan.');
    }

    public function edit(Semester $semester)
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        return view('admin.semesters.edit', compact('semester', 'academicYears'));
    }

    public function update(UpdateSemesterRequest $request, Semester $semester)
    {
        $data = $request->validated();

        if (($data['is_active'] ?? false) && !$semester->is_active) {
            Semester::where('is_active', true)->update(['is_active' => false]);
        }

        $semester->update($data);

        return redirect()->route('admin.semesters.index')->with('success', 'Semester berhasil diperbarui.');
    }

    public function destroy(Semester $semester)
    {
        $semester->update(['is_active' => false]);
        return redirect()->route('admin.semesters.index')->with('success', 'Semester berhasil dinonaktifkan.');
    }
}
