# Plan: Teacher CRUD

## Goal

Create CRUD feature for teacher master data, including identity data, optional login account, and read-only assignment summary.

## Scope

Implement CRUD for:

- teachers

Also support optional user account linking for teacher login.

## Rules

- Use Blade and Tailwind CSS.
- Follow docs/05-ui-guidelines.md.
- Use Form Requests for validation.
- Use pagination for index page.
- Use admin route prefix.
- Do not create student CRUD.
- Do not create guardian CRUD.
- Do not create academic assignment CRUD.
- Do not create grading, journal, report card, or attendance features.
- Do not change authentication logic.
- Use guru_fan, not guru_mapel.
- Keep controllers thin.

## Data Separation Rules

Teacher identity data is stored in `teachers`.

Login account data is stored in `users`.

Teacher role is assigned to `users` using Spatie Laravel Permission.

Subjects/fan taught by the teacher are not stored directly in `teachers`.
They come from `teaching_assignments`.

Homeroom status is not stored directly in `teachers`.
It comes from `homeroom_assignments`.

## Required Teacher Fields

Teacher profile fields:

- teacher_code
- name
- gender
- birth_place
- birth_date
- phone
- email
- address
- signature_path
- status

User account fields, optional:

- username
- password
- role

## Database Adjustment

If these fields do not exist in teachers, create a new migration:

- birth_place nullable string
- birth_date nullable date
- email nullable string

Do not edit old migrations.

## Routes

- admin.teachers.index
- admin.teachers.create
- admin.teachers.store
- admin.teachers.show
- admin.teachers.edit
- admin.teachers.update
- admin.teachers.destroy

## Form Sections

Create and edit form should have these sections:

### Identitas Guru

- Nama lengkap guru
- Kode guru / nomor induk guru
- Jenis kelamin
- Tempat lahir
- Tanggal lahir
- Status guru

### Kontak

- Nomor HP / WhatsApp
- Email
- Alamat

### Akun Login

- Buat akun login?
- Username
- Password
- Role pengguna

Allowed roles for teacher account:

- kepala_sekolah
- wali_kelas
- guru_fan

Do not assign super_admin from teacher CRUD by default.

### Dokumen

- Tanda tangan digital

For this phase, signature upload can be optional.
If upload is too much, keep signature_path as text input or leave it unused.

## Detail Page

Teacher detail page should show cards:

### Card Identitas

- Nama guru
- Kode guru
- Status guru
- Jenis kelamin
- Tempat tanggal lahir
- Kontak

### Card Akun

- Username
- Email login
- Role pengguna
- Status akun

### Card Wali Kelas

Show current homeroom assignment if exists.
If none, show: Belum menjadi wali kelas.

### Card Guru Fan/Mapel

Show teaching assignment summary if exists.
Example:
- Fiqih - Ibtida'iyah 1A
- Nahwu - Tsanawiyah 2B

If none, show: Belum memiliki jadwal mengajar.

## Validation

teacher_code:
- nullable
- string
- max 50
- unique teachers teacher_code

name:
- required
- string
- max 255

gender:
- nullable
- in male,female

birth_place:
- nullable
- string
- max 100

birth_date:
- nullable
- date

phone:
- nullable
- string
- max 30

email:
- nullable
- email
- max 255

address:
- nullable
- string

status:
- required
- in active,inactive

username:
- nullable
- string
- max 100
- unique users username

password:
- nullable
- string
- min 8

role:
- nullable
- in kepala_sekolah,wali_kelas,guru_fan

## Expected Files

Possible files:

- app/Http/Controllers/Admin/TeacherController.php
- app/Http/Requests/Admin/StoreTeacherRequest.php
- app/Http/Requests/Admin/UpdateTeacherRequest.php
- resources/views/admin/teachers/index.blade.php
- resources/views/admin/teachers/create.blade.php
- resources/views/admin/teachers/edit.blade.php
- resources/views/admin/teachers/show.blade.php
- resources/views/admin/teachers/_form.blade.php
- routes/web.php
- database/migrations/*_add_profile_fields_to_teachers_table.php if needed

## Acceptance Criteria

- Teacher index works.
- Teacher create works.
- Teacher detail page works.
- Teacher update works.
- Teacher delete works.
- Validation works.
- Index uses pagination.
- Search by name or teacher_code is supported.
- Optional login account can be created.
- Role can be assigned to linked user.
- Detail page shows identity card, account card, wali kelas card, and guru fan/mapel card.
- No guru_mapel naming appears.
- migrate:fresh --seed still works.