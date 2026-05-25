<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubjectRequest;
use App\Http\Requests\Admin\UpdateSubjectRequest;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('sort_order')->paginate(10);
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.subjects.create');
    }

    public function store(StoreSubjectRequest $request)
    {
        Subject::create($request->validated());
        return redirect()->route('admin.subjects.index')->with('success', 'Fan/Mapel berhasil ditambahkan.');
    }

    public function edit(Subject $subject)
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $subject->update($request->validated());
        return redirect()->route('admin.subjects.index')->with('success', 'Fan/Mapel berhasil diperbarui.');
    }

    public function destroy(Subject $subject)
    {
        $subject->update(['status' => 'inactive']);
        return redirect()->route('admin.subjects.index')->with('success', 'Fan/Mapel berhasil dinonaktifkan.');
    }
}
