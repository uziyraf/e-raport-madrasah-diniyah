# Plan: Academic Assignment Management

## Goal

Create management pages for core academic assignments in SIRAFAH.

## Scope

Implement management features for:

- student_class_enrollments
- homeroom_assignments
- teaching_assignments

## Rules

- Use Blade and Tailwind CSS.
- Follow docs/05-ui-guidelines.md.
- Use Form Requests for validation.
- Use pagination.
- Use admin route prefix.
- Do not create grading input yet.
- Do not create report card features.
- Do not create journal features.
- Do not create attendance features.
- Do not add class_id to students.
- Use guru_fan, not guru_mapel.
- Keep controllers thin.

## Data Rules

Student class placement must use student_class_enrollments.

Do not store active class directly in students.

Homeroom assignment must use homeroom_assignments.

Teaching assignment must use teaching_assignments.

## Module 1: Student Class Enrollment

Routes:

- admin.student-enrollments.index
- admin.student-enrollments.create
- admin.student-enrollments.store
- admin.student-enrollments.edit
- admin.student-enrollments.update
- admin.student-enrollments.destroy

Fields:

- student_id
- school_class_id
- academic_year_id
- semester_id
- enrollment_status
- is_active

Rules:

- A student can only have one enrollment per academic year and semester.
- If is_active is true, deactivate previous active enrollment for that student.
- Index should show student name, NIS, class, level, academic year, semester, and status.

## Module 2: Homeroom Assignment

Routes:

- admin.homeroom-assignments.index
- admin.homeroom-assignments.create
- admin.homeroom-assignments.store
- admin.homeroom-assignments.edit
- admin.homeroom-assignments.update
- admin.homeroom-assignments.destroy

Fields:

- teacher_id
- school_class_id
- academic_year_id
- semester_id

Rules:

- A class can only have one homeroom teacher per academic year and semester.
- Index should show teacher name, class, academic year, and semester.

## Module 3: Teaching Assignment

Routes:

- admin.teaching-assignments.index
- admin.teaching-assignments.create
- admin.teaching-assignments.store
- admin.teaching-assignments.edit
- admin.teaching-assignments.update
- admin.teaching-assignments.destroy

Fields:

- teacher_id
- subject_id
- school_class_id
- academic_year_id
- semester_id

Rules:

- Prevent duplicate teacher, subject, class, academic year, and semester combination.
- Index should show teacher name, subject/fan, class, academic year, and semester.

## Expected Files

Possible files:

- app/Http/Controllers/Admin/StudentEnrollmentController.php
- app/Http/Controllers/Admin/HomeroomAssignmentController.php
- app/Http/Controllers/Admin/TeachingAssignmentController.php

- app/Http/Requests/Admin/StoreStudentEnrollmentRequest.php
- app/Http/Requests/Admin/UpdateStudentEnrollmentRequest.php
- app/Http/Requests/Admin/StoreHomeroomAssignmentRequest.php
- app/Http/Requests/Admin/UpdateHomeroomAssignmentRequest.php
- app/Http/Requests/Admin/StoreTeachingAssignmentRequest.php
- app/Http/Requests/Admin/UpdateTeachingAssignmentRequest.php

- resources/views/admin/student-enrollments/*
- resources/views/admin/homeroom-assignments/*
- resources/views/admin/teaching-assignments/*

- routes/web.php
- config/navigation.php if route names need alignment

## Acceptance Criteria

- Student enrollment management works.
- Homeroom assignment management works.
- Teaching assignment management works.
- No class_id is added to students.
- Duplicate academic assignments are prevented.
- Index pages use pagination.
- Form validation works.
- Sidebar routes are connected where available.
- migrate:fresh --seed still works.