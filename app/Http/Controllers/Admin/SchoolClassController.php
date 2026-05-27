<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSchoolClassRequest;
use App\Http\Requests\Admin\UpdateSchoolClassRequest;
use App\Models\Level;
use App\Models\SchoolClass;

class SchoolClassController extends Controller
{
    public function index()
    {
        $schoolClasses = SchoolClass::with('level')->orderBy('sort_order')->paginate(10);
        return view('admin.school-classes.index', compact('schoolClasses'));
    }

    public function create()
    {
        $levels = Level::active()->orderBy('sort_order')->get();
        return view('admin.school-classes.create', compact('levels'));
    }

    public function store(StoreSchoolClassRequest $request)
    {
        SchoolClass::create($request->validated());
        return redirect()->route('admin.school-classes.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(SchoolClass $schoolClass)
    {
        $levels = Level::active()->orderBy('sort_order')->get();
        return view('admin.school-classes.edit', compact('schoolClass', 'levels'));
    }

    public function update(UpdateSchoolClassRequest $request, SchoolClass $schoolClass)
    {
        $schoolClass->update($request->validated());
        return redirect()->route('admin.school-classes.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(SchoolClass $schoolClass)
    {
        $schoolClass->update(['status' => 'inactive']);
        return redirect()->route('admin.school-classes.index')->with('success', 'Kelas berhasil dinonaktifkan.');
    }
}
