# Plan: Academic Year and Semester CRUD

## Goal

Create CRUD features for academic years and semesters.

## Scope

Implement CRUD for:

- academic_years
- semesters

## Rules

- Use Blade and Tailwind CSS.
- Follow docs/05-ui-guidelines.md.
- Use Form Requests for validation.
- Use pagination for index pages.
- Use admin route prefix.
- Do not create migrations.
- Do not change authentication logic.
- Do not create grading, journal, report card, attendance, teacher, or student features.
- Use guru_fan, not guru_mapel.
- Keep controllers thin.

## Academic Years

Routes:

- admin.academic-years.index
- admin.academic-years.create
- admin.academic-years.store
- admin.academic-years.edit
- admin.academic-years.update
- admin.academic-years.destroy

Fields:

- name
- start_date
- end_date
- is_active

Rules:

- Only one academic year should be active at a time.
- If a new academic year is set active, deactivate the others.
- If an existing academic year is updated to active, deactivate the others.

## Semesters

Routes:

- admin.semesters.index
- admin.semesters.create
- admin.semesters.store
- admin.semesters.edit
- admin.semesters.update
- admin.semesters.destroy

Fields:

- academic_year_id
- name
- start_date
- end_date
- is_active

Rules:

- Semester belongs to academic year.
- Only one semester should be active at a time.
- If a new semester is set active, deactivate the others.
- If an existing semester is updated to active, deactivate the others.
- Index should show academic year name.
- Use pagination.

## Expected Files

Possible files:

- app/Http/Controllers/Admin/AcademicYearController.php
- app/Http/Controllers/Admin/SemesterController.php
- app/Http/Requests/Admin/StoreAcademicYearRequest.php
- app/Http/Requests/Admin/UpdateAcademicYearRequest.php
- app/Http/Requests/Admin/StoreSemesterRequest.php
- app/Http/Requests/Admin/UpdateSemesterRequest.php
- resources/views/admin/academic-years/*
- resources/views/admin/semesters/*
- routes/web.php

## Acceptance Criteria

- Academic Year CRUD works.
- Semester CRUD works.
- Only one academic year can be active.
- Only one semester can be active.
- Index pages use pagination.
- Forms use validation.
- migrate:fresh --seed still works.