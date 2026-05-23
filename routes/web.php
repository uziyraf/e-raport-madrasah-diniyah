<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::view('/admin/dashboard', 'dashboards.admin')
        ->name('admin.dashboard');
});

Route::middleware(['auth', 'role:kepala_sekolah'])->group(function () {
    Route::view('/kepala-sekolah/dashboard', 'dashboards.principal')
        ->name('principal.dashboard');
});

Route::middleware(['auth', 'role:wali_kelas'])->group(function () {
    Route::view('/wali-kelas/dashboard', 'dashboards.homeroom')
        ->name('homeroom.dashboard');
});

Route::middleware(['auth', 'role:guru_fan'])->group(function () {
    Route::view('/guru-fan/dashboard', 'dashboards.teacher')
        ->name('teacher.dashboard');
});

Route::middleware(['auth', 'role:wali_santri'])->group(function () {
    Route::view('/wali-santri/dashboard', 'dashboards.guardian')
        ->name('guardian.dashboard');
});

require __DIR__ . '/auth.php';