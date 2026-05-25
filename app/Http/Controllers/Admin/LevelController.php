<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLevelRequest;
use App\Http\Requests\Admin\UpdateLevelRequest;
use App\Models\Level;

class LevelController extends Controller
{
    public function index()
    {
        $levels = Level::orderBy('sort_order')->paginate(10);
        return view('admin.levels.index', compact('levels'));
    }

    public function create()
    {
        return view('admin.levels.create');
    }

    public function store(StoreLevelRequest $request)
    {
        Level::create($request->validated());
        return redirect()->route('admin.levels.index')->with('success', 'Jenjang berhasil ditambahkan.');
    }

    public function edit(Level $level)
    {
        return view('admin.levels.edit', compact('level'));
    }

    public function update(UpdateLevelRequest $request, Level $level)
    {
        $level->update($request->validated());
        return redirect()->route('admin.levels.index')->with('success', 'Jenjang berhasil diperbarui.');
    }

    public function destroy(Level $level)
    {
        $level->update(['status' => 'inactive']);
        return redirect()->route('admin.levels.index')->with('success', 'Jenjang berhasil dinonaktifkan.');
    }
}
