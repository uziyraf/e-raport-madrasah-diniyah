<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class GradeMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $academicYears = AcademicYear::orderByDesc('start_date')->pluck('name', 'id');
        $semesters = Semester::orderByDesc('start_date')->pluck('name', 'id');
        $classes = SchoolClass::active()->with('level')->orderBy('sort_order')->get()
            ->mapWithKeys(fn($c) => [$c->id => $c->level->name . ' - ' . $c->name]);
        $subjects = Subject::orderBy('name')->pluck('name', 'id');
        $teachers = Teacher::active()->orderBy('name')->pluck('name', 'id');

        $grades = Grade::with([
            'student', 'teachingAssignment.teacher', 'teachingAssignment.subject',
            'teachingAssignment.schoolClass.level', 'teachingAssignment.academicYear',
            'teachingAssignment.semester', 'enteredBy',
        ])
            ->when($request->filled('academic_year_id'), fn($q) => $q->whereHas('teachingAssignment', fn($sq) => $sq->where('academic_year_id', $request->academic_year_id)))
            ->when($request->filled('semester_id'), fn($q) => $q->whereHas('teachingAssignment', fn($sq) => $sq->where('semester_id', $request->semester_id)))
            ->when($request->filled('school_class_id'), fn($q) => $q->whereHas('teachingAssignment', fn($sq) => $sq->where('school_class_id', $request->school_class_id)))
            ->when($request->filled('subject_id'), fn($q) => $q->whereHas('teachingAssignment', fn($sq) => $sq->where('subject_id', $request->subject_id)))
            ->when($request->filled('teacher_id'), fn($q) => $q->whereHas('teachingAssignment', fn($sq) => $sq->where('teacher_id', $request->teacher_id)))
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.grades.index', compact(
            'grades', 'academicYears', 'semesters', 'classes', 'subjects', 'teachers'
        ));
    }
}
