<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AttendanceDetail;
use App\Models\Guardian;
use App\Models\Semester;

class DashboardController extends Controller
{
    public function index()
    {
        $hour = now()->hour;

        if ($hour < 12) {
            $greeting = 'Pagi';
        } elseif ($hour < 15) {
            $greeting = 'Siang';
        } elseif ($hour < 18) {
            $greeting = 'Sore';
        } else {
            $greeting = 'Malam';
        }

        $currentDate = now()->translatedFormat('l, d F Y');

        $guardian = Guardian::where('user_id', auth()->id())->first();

        $students = collect();
        $totalStudents = 0;
        $attendanceSummary = [];
        $todayStatus = [];

        if ($guardian) {
            $students = $guardian->students()
                ->with('activeEnrollment.schoolClass.level')
                ->orderBy('name')
                ->get();

            $totalStudents = $students->count();

            $activeYear = AcademicYear::where('is_active', true)->first();
            $activeSemester = Semester::whereHas('academicYear', fn($q) => $q->where('is_active', true))
                ->where('is_active', true)->first();

            if ($activeYear && $activeSemester && $students->isNotEmpty()) {
                $studentIds = $students->pluck('id');

                $counts = AttendanceDetail::selectRaw('student_id, status, COUNT(*) as total')
                    ->whereIn('student_id', $studentIds)
                    ->whereHas('session', fn($q) => $q
                        ->where('academic_year_id', $activeYear->id)
                        ->where('semester_id', $activeSemester->id)
                    )
                    ->groupBy('student_id', 'status')
                    ->get()
                    ->groupBy('student_id');

                foreach ($studentIds as $id) {
                    $studentCounts = $counts->get($id, collect());
                    $attendanceSummary[$id] = [
                        'present' => (int) ($studentCounts->where('status', 'present')->first()?->total ?? 0),
                        'sick' => (int) ($studentCounts->where('status', 'sick')->first()?->total ?? 0),
                        'permission' => (int) ($studentCounts->where('status', 'permission')->first()?->total ?? 0),
                        'absent' => (int) ($studentCounts->where('status', 'absent')->first()?->total ?? 0),
                    ];
                }

                $todayDetails = AttendanceDetail::whereIn('student_id', $studentIds)
                    ->whereHas('session', fn($q) => $q
                        ->where('academic_year_id', $activeYear->id)
                        ->where('semester_id', $activeSemester->id)
                        ->whereDate('attendance_date', now())
                    )
                    ->get()
                    ->keyBy('student_id');

                foreach ($studentIds as $id) {
                    $todayStatus[$id] = $todayDetails->get($id)?->status;
                }
            }
        }

        return view('guardian.dashboard', compact(
            'greeting', 'currentDate', 'guardian', 'students',
            'totalStudents', 'attendanceSummary', 'todayStatus',
        ));
    }
}
