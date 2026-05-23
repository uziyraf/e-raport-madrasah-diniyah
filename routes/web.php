<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::view('/admin/dashboard', 'dashboards.admin')
        ->name('admin.dashboard');

    Route::view('/kepala-sekolah/dashboard', 'dashboards.principal')
        ->name('principal.dashboard');

    Route::view('/wali-kelas/dashboard', 'dashboards.homeroom')
        ->name('homeroom.dashboard');

    Route::view('/guru-fan/dashboard', 'dashboards.teacher')
        ->name('teacher.dashboard');

    Route::view('/wali-santri/dashboard', 'dashboards.guardian')
        ->name('guardian.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';