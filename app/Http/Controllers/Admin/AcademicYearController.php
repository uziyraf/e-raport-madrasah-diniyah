<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAcademicYearRequest;
use App\Http\Requests\Admin\UpdateAcademicYearRequest;
use App\Models\AcademicYear;

class AcademicYearController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->paginate(10);
        return view('admin.academic-years.index', compact('academicYears'));
    }

    public function create()
    {
        return view('admin.academic-years.create');
    }

    public function store(StoreAcademicYearRequest $request)
    {
        $data = $request->validated();

        if ($data['is_active'] ?? false) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }

        $academicYear = AcademicYear::create($data);

        $startDate = isset($data['start_date']) ? \Carbon\Carbon::parse($data['start_date']) : null;
        $endDate = isset($data['end_date']) ? \Carbon\Carbon::parse($data['end_date']) : null;

        // Semester Ganjil sampai 31 Desember dari tahun mulai
        $ganjilEnd = $startDate ? $startDate->copy()->month(12)->endOfMonth() : null;
        // Semester Genap mulai 1 Januari (sehari setelah Ganjil berakhir)
        $genapStart = $ganjilEnd ? $ganjilEnd->copy()->addDay() : null;

        // Otomatis membuat 2 semester (Ganjil & Genap)
        $academicYear->semesters()->createMany([
            [
                'name' => 'Ganjil', 
                'start_date' => $startDate?->format('Y-m-d'),
                'end_date' => $ganjilEnd?->format('Y-m-d'),
                'is_active' => $data['active_semester'] === 'Ganjil' ? ($data['is_active'] ?? false) : false
            ],
            [
                'name' => 'Genap', 
                'start_date' => $genapStart?->format('Y-m-d'),
                'end_date' => $endDate?->format('Y-m-d'),
                'is_active' => $data['active_semester'] === 'Genap' ? ($data['is_active'] ?? false) : false
            ],
        ]);

        return redirect()->route('admin.academic-years.index')->with('success', 'Tahun ajaran berhasil ditambahkan (Semester Ganjil & Genap juga dibuat otomatis).');
    }

    public function edit(AcademicYear $academicYear)
    {
        return view('admin.academic-years.edit', compact('academicYear'));
    }

    public function update(UpdateAcademicYearRequest $request, AcademicYear $academicYear)
    {
        $data = $request->validated();

        if (($data['is_active'] ?? false) && !$academicYear->is_active) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }

        $academicYear->update($data);

        return redirect()->route('admin.academic-years.index')->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(AcademicYear $academicYear)
    {
        $academicYear->update(['is_active' => false]);
        return redirect()->route('admin.academic-years.index')->with('success', 'Tahun ajaran berhasil dinonaktifkan.');
    }
}
