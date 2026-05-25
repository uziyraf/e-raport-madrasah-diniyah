# Plan: Master Data CRUD Base

## Goal

Create the first CRUD foundation for SIRAFAH master data.

## Scope

Implement CRUD for:

- levels
- school_classes
- subjects

## Excluded

Do not implement yet:

- teachers CRUD
- students CRUD
- academic years CRUD
- semesters CRUD
- grading
- report cards
- journals
- attendance
- guardian features

## Rules

- Use Blade and Tailwind CSS.
- Follow docs/05-ui-guidelines.md.
- Use Form Requests for validation.
- Use pagination.
- Do not use Model::all() for index pages.
- Use route prefix admin for now.
- Protect routes with auth.
- Do not create new migrations.
- Do not change authentication logic.
- Use guru_fan, not guru_mapel.

## CRUD Modules

### Levels

Routes:

- admin.levels.index
- admin.levels.create
- admin.levels.store
- admin.levels.edit
- admin.levels.update
- admin.levels.destroy

Fields:

- name
- description
- sort_order
- status

### School Classes

Routes:

- admin.school-classes.index
- admin.school-classes.create
- admin.school-classes.store
- admin.school-classes.edit
- admin.school-classes.update
- admin.school-classes.destroy

Fields:

- level_id
- name
- code
- sort_order
- status

### Subjects

Routes:

- admin.subjects.index
- admin.subjects.create
- admin.subjects.store
- admin.subjects.edit
- admin.subjects.update
- admin.subjects.destroy

Fields:

- name
- arabic_name
- code
- sort_order
- status

## Expected Files

Possible files:

- app/Http/Controllers/Admin/LevelController.php
- app/Http/Controllers/Admin/SchoolClassController.php
- app/Http/Controllers/Admin/SubjectController.php

- app/Http/Requests/Admin/StoreLevelRequest.php
- app/Http/Requests/Admin/UpdateLevelRequest.php
- app/Http/Requests/Admin/StoreSchoolClassRequest.php
- app/Http/Requests/Admin/UpdateSchoolClassRequest.php
- app/Http/Requests/Admin/StoreSubjectRequest.php
- app/Http/Requests/Admin/UpdateSubjectRequest.php

- resources/views/admin/levels/*
- resources/views/admin/school-classes/*
- resources/views/admin/subjects/*

- routes/web.php

## Acceptance Criteria

- Levels CRUD works.
- School Classes CRUD works.
- Subjects CRUD works.
- Index pages use pagination.
- Forms use validation.
- No academic grading/report/journal features are created.
- Sidebar menu can later link to these routes.
- migrate:fresh --seed still works.