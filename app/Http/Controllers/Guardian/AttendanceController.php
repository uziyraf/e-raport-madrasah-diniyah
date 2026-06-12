<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AttendanceDetail;
use App\Models\Guardian;
use App\Models\Semester;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $guardian = Guardian::where('user_id', auth()->id())->first();

        $studentsSimple = collect();
        if ($guardian) {
            $studentsSimple = $guardian->students()->orderBy('name')->get();
        }

        if (!$guardian || $studentsSimple->isEmpty()) {
            return view('guardian.attendances.index', [
                'guardian' => $guardian,
                'students' => collect(),
                'studentsSimple' => collect(),
                'viewMode' => 'summary',
            ]);
        }

        $viewMode = $request->view ?? 'summary';

        if ($viewMode === 'calendar') {
            $studentId = $request->student_id ?? $studentsSimple->first()->id;

            $guardianStudentIds = $studentsSimple->pluck('id')->toArray();
            if (!in_array((int) $studentId, $guardianStudentIds)) {
                abort(403);
            }

            $selectedStudent = Student::with('activeEnrollment.schoolClass.level')
                ->findOrFail($studentId);

            $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
            $activeSemester = Semester::whereHas('academicYear', fn($q) => $q->where('is_active', true))
                ->where('is_active', true)->firstOrFail();

            $month = (int) ($request->month ?? now()->month);
            $year = (int) ($request->year ?? now()->year);

            $attendanceDetails = AttendanceDetail::with([
                'session.teachingAssignment.subject',
                'session.teacher',
            ])
                ->where('student_id', $selectedStudent->id)
                ->whereHas('session', fn($q) => $q
                    ->where('academic_year_id', $activeYear->id)
                    ->where('semester_id', $activeSemester->id)
                    ->whereMonth('attendance_date', $month)
                    ->whereYear('attendance_date', $year)
                )
                ->get()
                ->groupBy(fn($d) => $d->session->attendance_date->format('Y-m-d'));

            $attendanceDetails = $attendanceDetails->sortKeys();

            return view('guardian.attendances.index', compact(
                'guardian', 'studentsSimple', 'viewMode', 'selectedStudent',
                'month', 'year', 'attendanceDetails',
            ));
        }

        $students = $guardian->students()
            ->with([
                'activeEnrollment.schoolClass.level',
                'attendanceDetails' => fn($q) => $q->with('session.academicYear', 'session.semester')
                    ->whereHas('session', fn($sq) => $sq->whereHas('academicYear', fn($ay) => $ay->where('is_active', true)))
                    ->whereHas('session', fn($sq) => $sq->whereHas('semester', fn($s) => $s->where('is_active', true)))
                    ->latest(),
            ])
            ->orderBy('name')
            ->paginate(10);

        return view('guardian.attendances.index', compact(
            'students', 'guardian', 'studentsSimple', 'viewMode',
        ));
    }
}
