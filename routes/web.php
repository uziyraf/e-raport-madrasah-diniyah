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

    Route::prefix('guru/nilai')->name('teacher.grades.')->group(function () {
        Route::get('/', [App\Http\Controllers\Teacher\GradeController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Teacher\GradeController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Teacher\GradeController::class, 'store'])->name('store');
        Route::get('/{teachingAssignment}/edit', [App\Http\Controllers\Teacher\GradeController::class, 'edit'])->name('edit');
        Route::put('/{teachingAssignment}', [App\Http\Controllers\Teacher\GradeController::class, 'update'])->name('update');
    });

    Route::prefix('wali-kelas/nilai')->name('homeroom.grades.')->group(function () {
        Route::get('/', [App\Http\Controllers\Homeroom\GradeMonitoringController::class, 'index'])->name('index');
        Route::get('/{teachingAssignment}', [App\Http\Controllers\Homeroom\GradeMonitoringController::class, 'show'])->name('show');
    });

    Route::prefix('wali-kelas/absensi')->name('homeroom.attendances.')->group(function () {
        Route::get('/', [App\Http\Controllers\Homeroom\AttendanceController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Homeroom\AttendanceController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Homeroom\AttendanceController::class, 'store'])->name('store');
        Route::get('/student/{student}', [App\Http\Controllers\Homeroom\AttendanceController::class, 'studentHistory'])->name('student');
        Route::get('/{attendanceSession}', [App\Http\Controllers\Homeroom\AttendanceController::class, 'show'])->name('show');
        Route::get('/{attendanceSession}/edit', [App\Http\Controllers\Homeroom\AttendanceController::class, 'edit'])->name('edit');
        Route::put('/{attendanceSession}', [App\Http\Controllers\Homeroom\AttendanceController::class, 'update'])->name('update');
        Route::delete('/{attendanceSession}', [App\Http\Controllers\Homeroom\AttendanceController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('guru/absensi')->name('teacher.attendances.')->group(function () {
        Route::get('/', [App\Http\Controllers\Teacher\AttendanceController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Teacher\AttendanceController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Teacher\AttendanceController::class, 'store'])->name('store');
        Route::get('/student/{student}', [App\Http\Controllers\Teacher\AttendanceController::class, 'studentHistory'])->name('student');
        Route::get('/{attendanceSession}', [App\Http\Controllers\Teacher\AttendanceController::class, 'show'])->name('show');
        Route::get('/{attendanceSession}/edit', [App\Http\Controllers\Teacher\AttendanceController::class, 'edit'])->name('edit');
        Route::put('/{attendanceSession}', [App\Http\Controllers\Teacher\AttendanceController::class, 'update'])->name('update');
        Route::delete('/{attendanceSession}', [App\Http\Controllers\Teacher\AttendanceController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('wali-kelas/raport')->name('homeroom.report-cards.')->group(function () {
        Route::get('/', [App\Http\Controllers\Homeroom\ReportCardController::class, 'index'])->name('index');
        Route::get('/{student}', [App\Http\Controllers\Homeroom\ReportCardController::class, 'show'])->name('show');
    });

    Route::prefix('wali-kelas/sikap')->name('homeroom.attitudes.')->group(function () {
        Route::get('/', [App\Http\Controllers\Homeroom\AttitudeController::class, 'index'])->name('index');
        Route::get('/{student}/edit', [App\Http\Controllers\Homeroom\AttitudeController::class, 'edit'])->name('edit');
        Route::put('/{student}', [App\Http\Controllers\Homeroom\AttitudeController::class, 'update'])->name('update');
    });

    Route::prefix('guru/jurnal')->name('teacher.journals.')->group(function () {
        Route::get('/', [App\Http\Controllers\Teacher\JournalController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Teacher\JournalController::class, 'store'])->name('store');
        Route::get('/{journalType}/santri', [App\Http\Controllers\Teacher\JournalController::class, 'students'])->name('students');
        Route::get('/{journalType}/santri/{student}/create', [App\Http\Controllers\Teacher\JournalController::class, 'create'])->name('create');
        Route::get('/{journalType}/santri/{student}', [App\Http\Controllers\Teacher\JournalController::class, 'studentJournal'])->name('student');
        Route::get('/{teacherJournal}', [App\Http\Controllers\Teacher\JournalController::class, 'show'])->name('show');
        Route::get('/{teacherJournal}/edit', [App\Http\Controllers\Teacher\JournalController::class, 'edit'])->name('edit');
        Route::put('/{teacherJournal}', [App\Http\Controllers\Teacher\JournalController::class, 'update'])->name('update');
        Route::delete('/{teacherJournal}', [App\Http\Controllers\Teacher\JournalController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('wali-kelas/jurnal')->name('homeroom.journals.')->group(function () {
        Route::get('/', [App\Http\Controllers\Homeroom\JournalController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Homeroom\JournalController::class, 'store'])->name('store');
        Route::get('/{journalType}/santri', [App\Http\Controllers\Homeroom\JournalController::class, 'students'])->name('students');
        Route::get('/{journalType}/santri/{student}/create', [App\Http\Controllers\Homeroom\JournalController::class, 'create'])->name('create');
        Route::get('/{journalType}/santri/{student}', [App\Http\Controllers\Homeroom\JournalController::class, 'studentJournal'])->name('student');
        Route::get('/{teacherJournal}', [App\Http\Controllers\Homeroom\JournalController::class, 'show'])->name('show');
        Route::get('/{teacherJournal}/edit', [App\Http\Controllers\Homeroom\JournalController::class, 'edit'])->name('edit');
        Route::put('/{teacherJournal}', [App\Http\Controllers\Homeroom\JournalController::class, 'update'])->name('update');
        Route::delete('/{teacherJournal}', [App\Http\Controllers\Homeroom\JournalController::class, 'destroy'])->name('destroy');
    });

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
        Route::get('grades', [App\Http\Controllers\Admin\GradeMonitoringController::class, 'index'])->name('grades.index');
        Route::prefix('journals')->name('journals.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\JournalMonitoringController::class, 'index'])->name('index');
            Route::get('/kelas/{schoolClass}', [App\Http\Controllers\Admin\JournalMonitoringController::class, 'classDetail'])->name('class');
            Route::get('/kelas/{schoolClass}/{journalType}', [App\Http\Controllers\Admin\JournalMonitoringController::class, 'typeStudents'])->name('type');
            Route::get('/kelas/{schoolClass}/{journalType}/{student}', [App\Http\Controllers\Admin\JournalMonitoringController::class, 'studentHistory'])->name('student');
            Route::get('/{teacherJournal}', [App\Http\Controllers\Admin\JournalMonitoringController::class, 'show'])->name('show')->whereNumber('teacherJournal');
        });
        Route::prefix('raport')->name('report-cards.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ReportCardController::class, 'index'])->name('index');
            Route::get('/{student}', [App\Http\Controllers\Admin\ReportCardController::class, 'show'])->name('show');
        });
        Route::prefix('absensi')->name('attendances.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\AttendanceMonitoringController::class, 'index'])->name('index');
            Route::get('/kelas/{schoolClass}', [App\Http\Controllers\Admin\AttendanceMonitoringController::class, 'classDetail'])->name('class');
            Route::get('/kelas/{schoolClass}/homeroom', [App\Http\Controllers\Admin\AttendanceMonitoringController::class, 'homeroom'])->name('homeroom');
            Route::get('/kelas/{schoolClass}/teaching/{teachingAssignment}', [App\Http\Controllers\Admin\AttendanceMonitoringController::class, 'teaching'])->name('teaching');
            Route::get('/kelas/{schoolClass}/student/{student}', [App\Http\Controllers\Admin\AttendanceMonitoringController::class, 'student'])->name('student');
        });
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';