<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\TeacherJournal;
use Illuminate\Http\Request;

class JournalMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $academicYears = AcademicYear::orderByDesc('start_date')->pluck('name', 'id');
        $semesters = Semester::orderByDesc('start_date')->pluck('name', 'id');

        $journalTypes = [
            'hafalan' => 'Hafalan',
            'legalisir_kitab' => 'Legalisir Kitab',
            'nilai_harian' => 'Nilai Harian',
            'tamrinan' => 'Tamrinan',
            'catatan' => 'Catatan',
        ];

        $query = TeacherJournal::with([
            'teacher', 'teachingAssignment.subject', 'schoolClass.level', 'academicYear', 'semester', 'student',
        ]);

        if ($request->filled('journal_type')) {
            $query->where('journal_type', $request->journal_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('journal_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('journal_date', '<=', $request->date_to);
        }

        $journals = $query->orderByDesc('journal_date')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.journals.index', compact(
            'journals', 'journalTypes', 'academicYears', 'semesters'
        ));
    }

    public function show(TeacherJournal $teacherJournal)
    {
        $journal = $teacherJournal->loadMissing([
            'teacher', 'teachingAssignment.subject', 'schoolClass.level',
            'academicYear', 'semester', 'student',
        ]);

        $journalTypes = [
            'hafalan' => 'Hafalan',
            'legalisir_kitab' => 'Legalisir Kitab',
            'nilai_harian' => 'Nilai Harian',
            'tamrinan' => 'Tamrinan',
            'catatan' => 'Catatan',
        ];

        return view('admin.journals.show', compact('journal', 'journalTypes'));
    }
}
