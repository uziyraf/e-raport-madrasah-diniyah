# Plan: Teaching Grade Input

## Goal

Create final subject/fan grade input based on teaching assignments.

## Scope

Implement:

- grade input by assigned teacher
- grade monitoring for homeroom teacher
- grade monitoring for admin

## Rules

- Do not create report card generation yet.
- Do not create teacher journal features yet.
- Do not create attitude input yet.
- Do not create attendance features yet.
- Do not use grade_components for this phase.
- Do not add class_id to students.
- Use guru_fan, not guru_mapel.
- Keep controllers thin.
- Use Blade and Tailwind CSS.
- Use Form Request validation.

## Important Concept

Grade input is not limited to role guru_fan.

Any teacher who has a teaching_assignment can input grades for that teaching assignment.

This means:

- guru_fan can input grades for assigned subjects/classes.
- wali_kelas can input grades if the wali kelas also teaches a subject.
- wali_kelas can monitor all subject grades for their homeroom class.
- wali_kelas cannot edit grades from other teachers.

## Data Rules

Subject/fan grade is one final grade per student per teaching assignment.

Teaching assignment determines:

- teacher
- subject/fan
- class
- academic year
- semester

Student list must come from student_class_enrollments.

Do not input grades for students outside the selected class enrollment.

## Database Adjustment

If grades table still uses grade_component_id, create a new migration to adjust grades table.

Final grades table should use:

- student_id
- teaching_assignment_id
- entered_by
- score
- predicate
- note
- status
- submitted_at

Unique:

- unique student_id and teaching_assignment_id

Status values:

- draft
- submitted

Do not edit old migrations.

## Grade Input Feature

Routes:

- teacher.grades.index
- teacher.grades.create
- teacher.grades.store
- teacher.grades.edit
- teacher.grades.update

Important:

The route name may stay teacher.grades.*, but access should be based on teaching assignments, not only role guru_fan.

Flow:

1. User selects one teaching assignment owned by their teacher profile.
2. System shows enrolled students from that class, academic year, and semester.
3. User inputs:
   - nilai angka
   - predikat
   - keterangan
   - status input
4. User saves grades as draft or submitted.

Fields:

- score
- predicate
- note
- status

Validation:

score:
- nullable
- numeric
- min 0
- max 100

predicate:
- nullable
- string
- max 50

note:
- nullable
- string

status:
- required
- in draft,submitted

## Homeroom Grade Monitoring

Routes:

- homeroom.grades.index
- homeroom.grades.show optional

Wali kelas can view grade recap for their homeroom class.

Displayed columns:

- Nama santri
- Mata pelajaran
- Guru pengampu
- Nilai
- Predikat
- Keterangan
- Status nilai

Status display:

- Belum diisi
- Draft
- Sudah dikirim

Wali kelas cannot edit grades from other teachers.

If the wali kelas also has teaching assignments, grade input is done through the teaching grade input feature.

## Admin Monitoring

Route:

- admin.grades.index

Admin can view all grade records.

Admin does not need to edit grades in this phase.

## Expected Files

Possible files:

- database/migrations/*_adjust_grades_table_for_teaching_grades.php if needed
- app/Models/Grade.php
- app/Http/Controllers/Teacher/GradeController.php
- app/Http/Controllers/Homeroom/GradeMonitoringController.php
- app/Http/Controllers/Admin/GradeMonitoringController.php
- app/Http/Requests/Teacher/StoreGradeRequest.php
- app/Http/Requests/Teacher/UpdateGradeRequest.php
- resources/views/teacher/grades/*
- resources/views/homeroom/grades/*
- resources/views/admin/grades/*
- routes/web.php
- config/navigation.php if needed

## Acceptance Criteria

- A teacher can input grades only for their own teaching assignments.
- Wali kelas can input grades if they also teach a subject.
- Wali kelas can monitor grades for their homeroom class.
- Wali kelas cannot edit grades from other teachers.
- Grade input contains score, predicate, note, and status.
- Grades can be saved as draft.
- Grades can be submitted.
- Student list comes from student_class_enrollments.
- No report card generation is created yet.
- No teacher journal feature is created yet.
- No class_id is added to students.
- migrate:fresh --seed works.