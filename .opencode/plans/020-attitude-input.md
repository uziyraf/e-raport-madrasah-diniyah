# Plan: Attitude Input

## Goal

Create attitude input feature for Wali Kelas.

## Scope

Implement:

- attitude input per student for homeroom class
- attitude recap for homeroom class
- admin monitoring if simple

## Rules

- Use Blade and Tailwind CSS.
- Follow docs/05-ui-guidelines.md.
- Use Form Requests for validation.
- Do not create report card generation yet.
- Do not create teacher journal features.
- Do not create attendance features yet.
- Do not change authentication logic.
- Do not add class_id to students.
- Use guru_fan, not guru_mapel.
- Keep controllers thin.

## Data Rules

Attitude data belongs to:

- student
- school_class
- academic_year
- semester
- homeroom_teacher

Wali kelas can input attitudes only for their assigned homeroom class.

Student list must come from student_class_enrollments.

Do not input attitudes for students outside the wali kelas homeroom class.

## Table

Use existing attitudes table if available.

Expected fields:

- student_id
- school_class_id
- academic_year_id
- semester_id
- homeroom_teacher_id
- akhlak
- discipline
- cleanliness
- attitude_note

If attitudes table does not exist, create migration.

Unique:

- unique student_id, academic_year_id, semester_id

## Routes

Homeroom:

- homeroom.attitudes.index
- homeroom.attitudes.edit
- homeroom.attitudes.update

Admin monitoring optional:

- admin.attitudes.index

## Wali Kelas Flow

1. Wali kelas opens Nilai Sikap menu.
2. System detects active homeroom assignment for logged-in teacher.
3. System shows students from that class, academic year, and semester.
4. Wali kelas inputs:
   - Akhlak
   - Kedisiplinan
   - Kebersihan
   - Catatan sikap
5. Save attitudes using updateOrCreate.

## Input Fields

akhlak:
- nullable
- string
- max 50

discipline:
- nullable
- string
- max 50

cleanliness:
- nullable
- string
- max 50

attitude_note:
- nullable
- string

Recommended values for akhlak, discipline, cleanliness:

- Sangat Baik
- Baik
- Cukup
- Perlu Bimbingan

## Page Design

Index page:

- Show card summary:
  - class name
  - academic year
  - semester
  - total students
  - filled attitude count
  - unfilled attitude count

- Show table:
  - No
  - Nama Santri
  - NIS
  - Akhlak
  - Kedisiplinan
  - Kebersihan
  - Status
  - Aksi

Edit/Input page:

- Show student identity card
- Show attitude form
- Save button

## Access Rules

- Wali kelas can only access attitude input for their own homeroom class.
- If user has no homeroom assignment, show friendly empty state.
- Admin can optionally view all attitudes but not required to edit in this phase.

## Expected Files

Possible files:

- database/migrations/*_create_attitudes_table.php if needed
- app/Models/Attitude.php
- app/Http/Controllers/Homeroom/AttitudeController.php
- app/Http/Controllers/Admin/AttitudeMonitoringController.php optional
- app/Http/Requests/Homeroom/UpdateAttitudeRequest.php
- resources/views/homeroom/attitudes/index.blade.php
- resources/views/homeroom/attitudes/edit.blade.php
- resources/views/admin/attitudes/index.blade.php optional
- routes/web.php
- config/navigation.php if needed

## Acceptance Criteria

- Wali kelas can view attitude list for their class.
- Wali kelas can input/edit attitude per student.
- Student list comes from student_class_enrollments.
- Wali kelas cannot input attitude outside their class.
- Empty state appears if wali kelas has no homeroom assignment.
- No report card generation is created.
- No class_id is added to students.
- migrate:fresh --seed works.