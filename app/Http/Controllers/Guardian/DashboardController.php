<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Controllers\Controller;
use App\Models\Guardian;

class DashboardController extends Controller
{
    public function index()
    {
        $guardian = Guardian::where('user_id', auth()->id())->first();

        $students = collect();
        $totalStudents = 0;

        if ($guardian) {
            $students = $guardian->students()
                ->with('activeEnrollment.schoolClass.level')
                ->orderBy('name')
                ->get();

            $totalStudents = $students->count();
        }

        return view('guardian.dashboard', compact('guardian', 'students', 'totalStudents'));
    }
}
