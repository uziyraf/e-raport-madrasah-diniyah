# Plan: Student Promotion and Placement

## Goal

Create flexible student promotion and placement feature for new academic year/class placement.

## Scope

Implement student promotion/placement with 3 selection methods:

1. Select students from source class.
2. Paste/search NIS or student names in bulk.
3. Import Excel/CSV file with downloadable template.

All methods must show validation preview before execution.

## Rules

- Super Admin only for write actions.
- Kepala Sekolah may view monitoring if simple.
- Do not create report card generation.
- Do not create PDF export.
- Do not change grades, attendance, journal, attitude, guardian, export, or auth logic.
- Do not add class_id to students.
- Use student_class_enrollments for class placement.
- Use guru_fan, not guru_mapel.
- Use Blade and Tailwind CSS.
- Use Form Request validation.
- Keep controllers thin.
- Do not execute placement directly without preview validation.

## Core Concept

Students are not stored directly in a class.

Students are placed into classes using:

- student_class_enrollments

Promotion means creating a new active enrollment for selected students in a selected academic year, semester, and class.

Old active enrollment should be deactivated when new placement is confirmed.

Old enrollments must remain as history.

## Placement Status

Supported placement statuses:

- naik
- tetap
- pindah
- lulus
- keluar

Meaning:

- naik: student moves to a higher/target class.
- tetap: student stays in the same or selected class.
- pindah: student moves to another class.
- lulus: student graduates; deactivate active enrollment and update student status if needed.
- keluar: student leaves/nonactive; deactivate active enrollment and update student status if needed.

Target class is required for:

- naik
- tetap
- pindah

Target class is optional or not required for:

- lulus
- keluar

## Main Page Flow

Page: Kenaikan / Penempatan Santri

### Step 1 — Select Period

Super Admin selects:

- source academic year
- source semester
- target academic year
- target semester

### Step 2 — Select Target Placement

Super Admin selects:

- target class
- placement status

Status options:

- Naik
- Tetap
- Pindah
- Lulus
- Keluar

### Step 3 — Choose Selection Method

Super Admin chooses one method:

1. Dari Kelas Asal
2. Paste / Cari NIS atau Nama Massal
3. Import Excel/CSV

### Step 4 — Preview Validation

System shows validation preview table.

Columns:

- Status
- NIS
- Nama Santri
- Kelas Lama
- Kelas Tujuan
- Status Penempatan
- Keterangan

Status display:

- Valid
- Perlu Cek
- Error

Color meaning:

- Green = Valid / can be processed
- Yellow = Warning / needs review
- Red = Error / cannot be processed

### Step 5 — Execute

Super Admin clicks:

- Proses Penempatan Santri

System processes only valid records.

Records with error must not be processed.

## Mode 1 — Select From Source Class

Flow:

1. Admin selects source academic year, source semester, and source class.
2. System shows students from source class.
3. Admin checks selected students.
4. Admin clicks Preview.
5. System validates and shows preview.
6. Admin confirms placement.

Student table:

- checkbox
- NIS
- Nama Santri
- Kelas Aktif
- Status Santri
- Conflict Status

## Mode 2 — Paste / Search NIS or Name Bulk

Flow:

1. Admin selects period and target placement.
2. Admin enters/pastes multiple NIS or names.

Input examples:

001
002
003
999

Or:

Ahmad
Farhan
Aisyah

3. System searches matching students.
4. System shows preview validation.
5. Admin confirms placement.

Rules:

- Support newline-separated input.
- Support comma-separated input if simple.
- Prefer exact NIS match first.
- Then match by student name.
- If name search returns multiple students, mark as warning/perlu cek.
- If no student found, mark as error.

Preview examples:

- Valid: student found and can be placed.
- Warning: name matches multiple students.
- Error: NIS/name not found.

## Mode 3 — Import Excel/CSV

Flow:

1. Admin downloads template.
2. Admin fills the template.
3. Admin uploads the file.
4. System parses the file.
5. System shows validation preview.
6. Admin confirms placement.

Important:

- Do not execute immediately after upload.
- Always show preview first.

## Import Template

Provide downloadable template.

Preferred for MVP:

- CSV template that can be opened in Excel.

If XLSX export/import package is already installed, XLSX is allowed.
Do not add new heavy dependency unless necessary.

Template columns:

- NIS
- Nama Santri
- Kelas Tujuan
- Status Penempatan

Recommended optional columns:

- Kode Kelas Tujuan
- Catatan

Example:

NIS | Nama Santri | Kelas Tujuan | Status Penempatan
001 | Ahmad Zainuddin | Ibtida’iyah 2 A | Naik
002 | Muhammad Farhan | Ibtida’iyah 1 A | Tetap
003 | Siti Aisyah | Tsanawiyah 1 A | Naik

Better matching priority:

1. NIS
2. Kode Kelas Tujuan if available
3. Kelas Tujuan name

## Import Validation

System must detect:

- NIS not found.
- Student name does not match NIS.
- Target class not found.
- Student already has enrollment in target academic year and semester.
- Student already exists in selected target class/year/semester.
- Duplicate student/NIS inside uploaded file.
- Placement status is empty.
- Placement status invalid.
- Target academic year not selected.
- Target semester not selected.
- Target class required but missing.
- Source/target class same as current class.

Validation response:

- Error for invalid rows that cannot be processed.
- Warning for rows that need review but may still be processed if safe.
- Valid for rows ready to process.

## Conflict Detection

Before saving, detect:

- student already has active enrollment in target academic year and semester
- student already exists in selected target class/year/semester
- student has no current active enrollment
- target class/year/semester same as current enrollment
- duplicate student in selected/imported data
- student is inactive/left/graduated

Show conflict indicators:

- Aman
- Sudah ditempatkan
- Sudah di kelas target
- Tidak ada kelas aktif
- Sama dengan kelas saat ini
- NIS tidak ditemukan
- Nama tidak cocok
- Duplikat
- Santri tidak aktif

## Execution Rules

When confirming placement:

For status naik/tetap/pindah:

- deactivate old active enrollment for selected students
- create or update target enrollment
- set target enrollment:
  - is_active = true
  - enrollment_status = active
  - created_by = current user id

For status lulus:

- deactivate old active enrollment
- update student status to graduated if status column supports it
- do not create new target enrollment unless target class is provided and business rule requires it

For status keluar:

- deactivate old active enrollment
- update student status to left/inactive if status column supports it
- do not create new target enrollment

Do not delete old enrollments.

Do not duplicate enrollment for the same student, academic year, and semester.

## Routes

Admin:

- admin.promotions.index
- admin.promotions.template
- admin.promotions.preview
- admin.promotions.import-preview
- admin.promotions.store
- admin.promotions.history optional

## Page Design

Index page:

- period selection card
- target placement card
- method selection tabs/cards:
  - Dari Kelas Asal
  - Paste / Cari NIS Massal
  - Import Excel/CSV
- selection form based on selected method
- preview button

Template download:

- show button:
  Download Template

Preview page:

- selected source/target context
- validation result table
- summary count:
  - total rows
  - valid
  - warning
  - error
- confirm button disabled if all records are invalid
- confirm button should process valid rows only or require admin to remove error rows

## Validation

source_academic_year_id:
- nullable
- exists academic_years id

source_semester_id:
- nullable
- exists semesters id

source_school_class_id:
- nullable
- exists school_classes id

target_academic_year_id:
- required
- exists academic_years id

target_semester_id:
- required
- exists semesters id

target_school_class_id:
- nullable
- exists school_classes id

placement_status:
- required
- in naik,tetap,pindah,lulus,keluar

students:
- nullable array

students.*:
- exists students id

bulk_input:
- nullable string

import_file:
- nullable file
- mimes csv,txt,xlsx,xls if supported
- max 2048

Target class required if placement_status is:

- naik
- tetap
- pindah

Target class not required if placement_status is:

- lulus
- keluar

## Access Rules

- Only Super Admin can create placement/promotion.
- Kepala Sekolah can view history/monitoring if simple.
- Other roles cannot access.

## Data Rules

- Do not add class_id to students.
- Do not delete old enrollments.
- Keep old enrollment history.
- Only one active enrollment per student should exist at a time.
- A student should not have duplicate enrollment for same academic year and semester.

## Expected Files

Possible files:

- app/Http/Controllers/Admin/StudentPromotionController.php
- app/Http/Requests/Admin/PreviewStudentPromotionRequest.php
- app/Http/Requests/Admin/StoreStudentPromotionRequest.php
- resources/views/admin/promotions/index.blade.php
- resources/views/admin/promotions/preview.blade.php
- routes/web.php
- config/navigation.php if needed

## Acceptance Criteria

- Super Admin can use Mode 1: select students from source class.
- Super Admin can use Mode 2: paste/search NIS or name in bulk.
- Super Admin can use Mode 3: import Excel/CSV.
- Super Admin can download import template.
- System shows validation preview before execution.
- Valid rows can be processed.
- Error rows are not processed.
- Old active enrollment is deactivated.
- Target enrollment is active for naik/tetap/pindah.
- Lulus/Keluar status handled safely.
- No duplicate enrollment for same student/year/semester.
- No class_id is added to students.
- migrate:fresh --seed works.