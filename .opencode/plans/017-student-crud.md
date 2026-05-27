# Plan: Student CRUD

## Goal

Create CRUD feature for student master data, including identity, parent/guardian data, photo upload, and optional active class placement.

## Scope

Implement CRUD for:

- students

Also support optional initial active class placement using:

- student_class_enrollments

## Rules

- Use Blade and Tailwind CSS.
- Follow docs/05-ui-guidelines.md.
- Use Form Requests for validation.
- Use pagination for index page.
- Use admin route prefix.
- Do not create guardian account CRUD.
- Do not create grading, journal, report card, or attendance features.
- Do not add class_id to students.
- Do not change authentication logic.
- Use guru_fan, not guru_mapel.
- Keep controllers thin.

## Data Separation Rules

Student identity data is stored in `students`.

Student class placement is not stored directly in `students`.

Student class history must use:

- student_class_enrollments

Jenjang is obtained from:

- school_classes.level_id
- levels table

Kelas is obtained from:

- student_class_enrollments.school_class_id

Tahun ajaran and semester are obtained from:

- student_class_enrollments.academic_year_id
- student_class_enrollments.semester_id

## Required Student Fields

Student identity fields:

- nis
- name
- gender
- birth_place
- birth_date
- address
- father_name
- mother_name
- guardian_name
- guardian_phone
- photo_path
- status

## Database Adjustment

If these fields do not exist in students, create a new migration:

- father_name nullable string
- mother_name nullable string
- guardian_name nullable string
- photo_path nullable string

Do not edit old migrations.

## Routes

- admin.students.index
- admin.students.create
- admin.students.store
- admin.students.show
- admin.students.edit
- admin.students.update
- admin.students.destroy

## Form Sections

### Identitas Santri

- NIS / nomor induk santri
- Nama lengkap santri
- Jenis kelamin
- Tempat lahir
- Tanggal lahir
- Status santri
- Foto santri

### Data Orang Tua / Wali

- Nama ayah
- Nama ibu
- Nama wali
- Nomor HP / WhatsApp wali
- Alamat

### Penempatan Aktif

Optional section.

Fields:

- Tahun ajaran
- Semester
- Kelas

Important:

- Do not store class_id in students.
- Store placement in student_class_enrollments.
- If placement is filled, academic_year_id, semester_id, and school_class_id are required together.
- If placement is not filled, student can still be created without class.

## Detail Page

Student detail page should show cards:

### Card Identitas

- Foto santri
- NIS
- Nama santri
- Status
- Jenis kelamin
- Tempat tanggal lahir

### Card Orang Tua / Wali

- Nama ayah
- Nama ibu
- Nama wali
- Nomor HP / WhatsApp wali
- Alamat

### Card Kelas Aktif

Show active enrollment if exists:

- Jenjang
- Kelas
- Tahun ajaran
- Semester

If none, show:

- Kelas aktif belum diatur.

## Validation

nis:
- required
- string
- max 50
- unique students nis

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

father_name:
- nullable
- string
- max 255

mother_name:
- nullable
- string
- max 255

guardian_name:
- nullable
- string
- max 255

guardian_phone:
- nullable
- string
- max 30

address:
- nullable
- string

photo:
- nullable
- image
- mimes jpg,jpeg,png,webp
- max 2048

status:
- required
- in active,inactive,graduated,left,transferred

academic_year_id:
- nullable
- exists academic_years id

semester_id:
- nullable
- exists semesters id

school_class_id:
- nullable
- exists school_classes id

If one placement field is filled, all placement fields are required.

## Photo Upload Rules

- Replace photo_path text input with image upload.
- Use enctype="multipart/form-data" on create/edit form.
- Store uploaded photo in storage/app/public/student-photos.
- Save relative path into students.photo_path.
- On update, if a new photo is uploaded, replace the old one safely.
- Show current photo preview on edit/show if photo_path exists.
- Mention php artisan storage:link if needed.

## Placement Rules

On create:

- Create student first.
- If academic_year_id, semester_id, and school_class_id are filled, create student_class_enrollment.
- Set enrollment_status to active.
- Set is_active to true.
- Set created_by to current user id.

On update:

- Update student identity data.
- If placement fields are filled:
  - deactivate old active enrollment for that student.
  - create or update enrollment for selected academic year and semester.
  - set selected enrollment as active.
- Do not duplicate enrollment for the same student, academic_year_id, and semester_id.

## Expected Files

Possible files:

- app/Http/Controllers/Admin/StudentController.php
- app/Http/Requests/Admin/StoreStudentRequest.php
- app/Http/Requests/Admin/UpdateStudentRequest.php
- resources/views/admin/students/index.blade.php
- resources/views/admin/students/create.blade.php
- resources/views/admin/students/edit.blade.php
- resources/views/admin/students/show.blade.php
- resources/views/admin/students/_form.blade.php
- routes/web.php
- database/migrations/*_add_family_and_photo_fields_to_students_table.php if needed

## Acceptance Criteria

- Student index works.
- Student create works.
- Student detail page works.
- Student update works.
- Student delete works.
- Validation works.
- Index uses pagination.
- Search by name or NIS is supported.
- Photo upload works.
- Student can be created without class placement.
- Student can be created with active class placement.
- Active class is shown on detail page.
- No class_id is added to students.
- migrate:fresh --seed still works.