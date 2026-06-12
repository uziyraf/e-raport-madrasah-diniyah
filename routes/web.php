<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get('/admin/dashboard', function () {
        return redirect()->route('dashboard');
    })->name('admin.dashboard');

    Route::get('/kepala-sekolah/dashboard', function () {
        return redirect()->route('dashboard');
    })->name('principal.dashboard');

    Route::get('/wali-kelas/dashboard', function () {
        return redirect()->route('dashboard');
    })->name('homeroom.dashboard');

    Route::get('/guru-fan/dashboard', function () {
        return redirect()->route('dashboard');
    })->name('teacher.dashboard');

    Route::get('/wali-santri/dashboard', function () {
        return redirect()->route('dashboard');
    })->name('guardian.dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('levels', App\Http\Controllers\Admin\LevelController::class);
        Route::resource('school-classes', App\Http\Controllers\Admin\SchoolClassController::class);
        Route::resource('subjects', App\Http\Controllers\Admin\SubjectController::class);
        Route::resource('academic-years', App\Http\Controllers\Admin\AcademicYearController::class);
        Route::resource('semesters', App\Http\Controllers\Admin\SemesterController::class);
        Route::resource('teachers', App\Http\Controllers\Admin\TeacherController::class);
        Route::resource('students', App\Http\Controllers\Admin\StudentController::class);
        Route::resource('student-enrollments', App\Http\Controllers\Admin\StudentEnrollmentController::class);
        Route::resource('homeroom-assignments', App\Http\Controllers\Admin\HomeroomAssignmentController::class);
        Route::resource('teaching-assignments', App\Http\Controllers\Admin\TeachingAssignmentController::class);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';