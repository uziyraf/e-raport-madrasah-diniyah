<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Student;
use App\Models\SchoolClass;

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

        $teacherCount = Teacher::count();
        $studentCount = Student::count();
        $classCount = SchoolClass::count();

        return view('dashboard', compact(
            'greeting',
            'currentDate',
            'teacherCount',
            'studentCount',
            'classCount',
        ));
    }
}
