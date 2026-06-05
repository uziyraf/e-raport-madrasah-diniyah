# Plan: Export and Recap Data

## Goal

Create export features for academic and master data recap.

## Scope

Implement CSV export for:

- students
- teachers
- guardians
- attendance recap
- grade recap
- attitude recap
- teacher journal recap

## Rules

- Use CSV export first.
- Do not create PDF report card.
- Do not create XLSX package dependency unless already installed.
- Do not create report card snapshot.
- Do not publish report cards.
- Do not change grades, attendance, journal, attitude, guardian, or auth logic.
- Do not add class_id to students.
- Use guru_fan, not guru_mapel.
- Keep controllers thin.
- Use StreamedResponse for CSV export.

## Access Rules

Super Admin:

- can export all data.

Kepala Sekolah:

- can export monitoring/academic recap if route access already exists.

Wali Kelas:

- can export only data related to their homeroom class.

Guru Fan:

- can export only data related to teaching assignments they own.

Wali Santri:

- no export for this phase.

## Admin Exports

Routes:

- admin.exports.index
- admin.exports.students
- admin.exports.teachers
- admin.exports.guardians
- admin.exports.attendances
- admin.exports.grades
- admin.exports.attitudes
- admin.exports.journals

Admin export index page should show export cards:

- Data Santri
- Data Guru
- Data Wali Santri
- Rekap Absensi
- Rekap Nilai
- Rekap Sikap
- Rekap Jurnal

Filters if simple:

- academic_year_id
- semester_id
- school_class_id
- date range for attendance/journal
- status

## Wali Kelas Exports

Routes:

- homeroom.exports.index
- homeroom.exports.students
- homeroom.exports.attendances
- homeroom.exports.grades
- homeroom.exports.attitudes
- homeroom.exports.journals

Wali Kelas can export only their homeroom class.

Export cards:

- Data Santri Kelas
- Rekap Absensi Kelas
- Rekap Nilai Kelas
- Rekap Sikap
- Rekap Jurnal Kelas

## Guru Fan Exports

Routes:

- teacher.exports.index
- teacher.exports.attendances
- teacher.exports.grades
- teacher.exports.journals

Guru Fan can export only teaching assignments they own.

Export cards:

- Rekap Absensi Mengajar
- Rekap Nilai Mengajar
- Rekap Jurnal Guru

## CSV Requirements

CSV should include UTF-8 BOM so Indonesian/Arabic text opens correctly in Excel.

Use semicolon or comma consistently.

Preferred:

- comma delimiter
- UTF-8 BOM

Each export should return response download with clear filename.

Example filename:

- data-santri-2025-2026-ganjil.csv
- rekap-nilai-ibtidaiyah-1a-2025-2026-ganjil.csv

## Data Santri Export Columns

- NIS
- Nama Santri
- Nama Arab
- Jenis Kelamin
- Tempat Lahir
- Tanggal Lahir
- Kelas Aktif
- Jenjang
- Tahun Ajaran
- Semester
- Nama Ayah
- Nama Ibu
- Nama Wali
- No HP Wali
- Status

## Data Guru Export Columns

- Kode Guru
- Nama Guru
- Nama Arab
- Jenis Kelamin
- No HP
- Email
- Status
- Akun Login
- Role

## Data Wali Santri Export Columns

- Nama Wali
- No HP
- Email
- Username
- Status
- Jumlah Santri Terhubung
- Santri Terhubung

## Attendance Export Columns

- Tanggal
- Jenis Absensi
- Kelas
- Fan/Mapel
- Guru
- NIS
- Nama Santri
- Status Absensi
- Keterangan

Status display:

- present = Hadir
- permission = Izin
- sick = Sakit
- absent = Alfa

## Grade Export Columns

- Kelas
- Tahun Ajaran
- Semester
- Fan/Mapel
- Guru Pengampu
- NIS
- Nama Santri
- Nilai
- Predikat
- Keterangan
- Status

## Attitude Export Columns

- Kelas
- Tahun Ajaran
- Semester
- NIS
- Nama Santri
- Akhlak
- Kedisiplinan
- Kebersihan
- Catatan Sikap

## Journal Export Columns

- Tanggal
- Jenis Jurnal
- Kelas
- Fan/Mapel
- Guru
- NIS
- Nama Santri
- Isi Ringkas
- Predikat
- Status

For journal content:

- Hafalan: jenis, target, capaian
- Legalisir Kitab: nama kitab, halaman, status legalisir
- Nilai Harian: daily_score
- Tamrinan: exam_score
- Catatan: note

## Page Design

Export index pages should use SIMADU card style.

Each export card:

- title
- short description
- filter form if needed
- export button

## Expected Files

Possible files:

- app/Http/Controllers/Admin/ExportController.php
- app/Http/Controllers/Homeroom/ExportController.php
- app/Http/Controllers/Teacher/ExportController.php
- app/Support/CsvExporter.php optional
- resources/views/admin/exports/index.blade.php
- resources/views/homeroom/exports/index.blade.php
- resources/views/teacher/exports/index.blade.php
- routes/web.php
- config/navigation.php if needed

## Acceptance Criteria

- Admin can export master and recap data.
- Wali Kelas can export only homeroom class data.
- Guru Fan can export only owned teaching assignment data.
- CSV downloads work.
- CSV opens properly in Excel with UTF-8 text.
- Access restrictions are enforced server-side.
- No PDF report card is created.
- No class_id is added to students.
- migrate:fresh --seed works.