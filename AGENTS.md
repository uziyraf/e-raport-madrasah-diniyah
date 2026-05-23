# AGENTS.md

## Project

SIMADU - Sistem Informasi Madrasah Diniyah / E-Raport.

Project ini adalah sistem e-raport dan akademik Madrasah Diniyah Pondok Pesantren berbasis Laravel.

## Role Internal

Gunakan role internal berikut:

- super_admin
- kepala_sekolah
- wali_kelas
- guru_fan
- wali_santri

Jangan gunakan `guru_mapel`.

Label UI untuk `guru_fan` adalah "Guru Fan/Mapel".

## Tech Stack

- Laravel
- Blade
- Tailwind CSS
- MySQL
- Spatie Laravel Permission
- Laravel Excel
- Laravel DomPDF
- Database Queue

## Rules

- Jangan tambahkan `class_id` langsung ke tabel `students`.
- Riwayat kelas santri wajib lewat `student_class_enrollments`.
- Data santri lama jangan dihapus, cukup ubah status.
- Raport final harus disimpan sebagai snapshot.
- Controller harus tipis.
- Logic utama taruh di Service, Action, atau Job.
- Data besar wajib pakai pagination.
- Jangan pakai `Model::all()` untuk tabel besar.
- Sidebar harus dinamis berdasarkan role.
- Teks Arab pakai field khusus seperti `arabic_name`.

## AI Agent Rules

Sebelum edit file:

1. Baca AGENTS.md.
2. Baca file docs yang relevan.
3. Jelaskan rencana.
4. Sebutkan file yang akan diubah.
5. Jangan edit file di luar scope.

Setelah edit:

1. Jelaskan perubahan.
2. Sebutkan file yang berubah.
3. Beri command yang perlu dijalankan.