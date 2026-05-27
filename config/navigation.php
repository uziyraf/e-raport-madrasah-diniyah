<?php

// Removed unused import: use Illuminate\Support\Facades\Route;

return [
    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    |
    | Here is where you can register all of the navigation links for the
    | application. These links are used to display the navigation menus.
    |
    */

    // Navigation mapping based on user roles.
    // The key should match the role obtained from auth()->user()->getRoleNames()->first().

    // Super Admin
    'super_admin' => [
        'dashboard' => ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'bx bx-home-circle'],
        'data_user' => ['label' => 'Data User', 'route' => 'super_admin.users.index', 'icon' => 'bx bx-user'],
        'data_guru' => ['label' => 'Data Guru', 'route' => 'super_admin.teachers.index', 'icon' => 'bx bx-user'],
        'data_santri' => ['label' => 'Data Santri', 'route' => 'super_admin.students.index', 'icon' => 'bx bxs-user-rectangle'],
        'data_wali_santri' => ['label' => 'Data Wali Santri', 'route' => 'super_admin.guardians.index', 'icon' => 'bx bx-user-voice'],
        'jenjang_kelas' => ['label' => 'Jenjang dan Kelas', 'route' => 'super_admin.grades.index', 'icon' => 'bx bx-building'],
        'fan_mapel' => ['label' => 'Fan/Mapel', 'route' => 'super_admin.subjects.index', 'icon' => 'bx bx-book-open'],
        'tahun_ajaran_semester' => ['label' => 'Tahun Ajaran dan Semester', 'route' => 'admin.academic-years.index', 'icon' => 'bx bx-calendar'], // Consolidated
        'wali_kelas' => ['label' => 'Wali Kelas', 'route' => 'super_admin.homerooms.index', 'icon' => 'bx bx-chalkboard'],
        'guru_fan_mapel' => ['label' => 'Guru Fan/Mapel', 'route' => 'super_admin.teacher_subjects.index', 'icon' => 'bx bx-chalkboard'], // Renamed label and key
        'jadwal_pelajaran' => ['label' => 'Jadwal Pelajaran', 'route' => 'super_admin.schedules.index', 'icon' => 'bx bx-time'],
        'kenaikan_penempatan_santri' => ['label' => 'Kenaikan/Penempatan Santri', 'route' => 'super_admin.student_placements.index', 'icon' => 'bx bx-transfer'],
        'monitoring' => ['label' => 'Monitoring', 'route' => 'super_admin.monitoring.index', 'icon' => 'bx bx-desktop'],
        'export_data' => ['label' => 'Export Data', 'route' => 'super_admin.exports.index', 'icon' => 'bx bx-download'],
    ],

    // Principal
    'kepala_sekolah' => [
        'dashboard' => ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'bx bx-home-circle'],
        'data_user' => ['label' => 'Data User', 'route' => 'kepala_sekolah.users.index', 'icon' => 'bx bx-user'], // Added User data for principal
        'data_guru' => ['label' => 'Data Guru', 'route' => 'kepala_sekolah.teachers.index', 'icon' => 'bx bx-user'],
        'data_santri' => ['label' => 'Data Santri', 'route' => 'kepala_sekolah.students.index', 'icon' => 'bx bxs-user-rectangle'],
        'data_wali_santri' => ['label' => 'Data Wali Santri', 'route' => 'kepala_sekolah.guardians.index', 'icon' => 'bx bx-user-voice'], // Added Wali Santri for principal
        'jenjang_kelas' => ['label' => 'Jenjang dan Kelas', 'route' => 'kepala_sekolah.grades.index', 'icon' => 'bx bx-building'], // Renamed label
        'fan_mapel' => ['label' => 'Fan/Mapel', 'route' => 'kepala_sekolah.subjects.index', 'icon' => 'bx bx-book-open'],
        'tahun_ajaran_semester' => ['label' => 'Tahun Ajaran dan Semester', 'route' => 'admin.academic-years.index', 'icon' => 'bx bx-calendar'], // Consolidated
        'wali_kelas' => ['label' => 'Wali Kelas', 'route' => 'kepala_sekolah.homerooms.index', 'icon' => 'bx bx-chalkboard'],
        'guru_fan_mapel' => ['label' => 'Guru Fan/Mapel', 'route' => 'kepala_sekolah.teacher_subjects.index', 'icon' => 'bx bx-chalkboard'], // Renamed label and key
        'jadwal_pelajaran' => ['label' => 'Jadwal Pelajaran', 'route' => 'kepala_sekolah.schedules.index', 'icon' => 'bx bx-time'],
        'kenaikan_penempatan_santri' => ['label' => 'Kenaikan/Penempatan Santri', 'route' => 'kepala_sekolah.student_placements.index', 'icon' => 'bx bx-transfer'], // Renamed label
        'monitoring' => ['label' => 'Monitoring', 'route' => 'kepala_sekolah.monitoring.index', 'icon' => 'bx bx-desktop'], // Simplified monitoring label
        'export_data' => ['label' => 'Export Data', 'route' => 'kepala_sekolah.exports.index', 'icon' => 'bx bx-download'],
    ],

    // Wali Kelas
    'wali_kelas' => [
        'dashboard' => ['label' => 'Dashboard Wali Kelas', 'route' => 'dashboard', 'icon' => 'bx bx-home-circle'],
        'data_santri_kelas' => ['label' => 'Data Santri Kelas', 'route' => 'wali_kelas.students.index', 'icon' => 'bx bxs-user-rectangle'],
        'jadwal_kelas' => ['label' => 'Jadwal Kelas', 'route' => 'wali_kelas.schedules.index', 'icon' => 'bx bx-time'],
        'jurnal_guru' => ['label' => 'Jurnal Guru', 'route' => 'wali_kelas.journals.index', 'icon' => 'bx bx-book-content'],
        'nilai_sikap' => ['label' => 'Nilai Sikap', 'route' => 'wali_kelas.attitudes.index', 'icon' => 'bx bx-notepad'],
        'raport_santri' => ['label' => 'Raport Santri', 'route' => 'wali_kelas.reports.index', 'icon' => 'bx bx-file'],
        'export_rekap' => ['label' => 'Export Rekap', 'route' => 'wali_kelas.exports.index', 'icon' => 'bx bx-download'],
        'profil' => ['label' => 'Profil', 'route' => 'profile.edit', 'icon' => 'bx bx-user-circle'],
    ],

    // Guru Fan
    'guru_fan' => [
        'dashboard' => ['label' => 'Dashboard Guru Fan', 'route' => 'dashboard', 'icon' => 'bx bx-home-circle'],
        'jadwal_mengajar' => ['label' => 'Jadwal Mengajar', 'route' => 'guru_fan.schedules.index', 'icon' => 'bx bx-time'],
        'kelas_fan' => ['label' => 'Kelas dan Fan/Mapel', 'route' => 'guru_fan.subjects.index', 'icon' => 'bx bx-book-open'], // Renamed label
        'daftar_santri' => ['label' => 'Daftar Santri', 'route' => 'guru_fan.students.index', 'icon' => 'bx bxs-user-rectangle'],
        'jurnal_guru' => ['label' => 'Jurnal Guru', 'route' => 'guru_fan.journals.index', 'icon' => 'bx bx-book-content'],
        'input_nilai' => ['label' => 'Submit Nilai', 'route' => 'guru_fan.grades.index', 'icon' => 'bx bx-edit'], // Renamed label
        'rekap_nilai' => ['label' => 'Rekap Nilai', 'route' => 'guru_fan.reports.index', 'icon' => 'bx bx-chart'],
        'export_nilai' => ['label' => 'Export Nilai', 'route' => 'guru_fan.exports.index', 'icon' => 'bx bx-download'],
        'profil' => ['label' => 'Profil', 'route' => 'profile.edit', 'icon' => 'bx bx-user-circle'],
    ],

    // Wali Santri
    'wali_santri' => [
        'dashboard' => ['label' => 'Dashboard Wali Santri', 'route' => 'dashboard', 'icon' => 'bx bx-home-circle'],
        'data_santri' => ['label' => 'Data Santri', 'route' => 'wali_santri.students.index', 'icon' => 'bx bxs-user-rectangle'],
        'absensi_santri' => ['label' => 'Absensi Santri', 'route' => 'wali_santri.attendances.index', 'icon' => 'bx bx-list-ul'],
        'raport_santri' => ['label' => 'Raport Santri', 'route' => 'wali_santri.reports.index', 'icon' => 'bx bx-file'],
        'download_raport' => ['label' => 'Download Raport', 'route' => 'wali_santri.downloads.index', 'icon' => 'bx bx-download'],
        'profil' => ['label' => 'Profil', 'route' => 'profile.edit', 'icon' => 'bx bx-user-circle'],
    ],
];
