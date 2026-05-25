# Plan: Academic Relation Migrations

## Goal

Create academic relation migrations for SIMADU.

## Scope

Create migrations for:

- student_class_enrollments
- homeroom_assignments
- teaching_assignments
- grade_components

## Rules

- Do not create controllers.
- Do not create views.
- Do not create CRUD.
- Do not create report card tables yet.
- Do not create journal tables yet.
- Do not add class_id to students.
- Use guru_fan, not guru_mapel.
- Use foreign keys and indexes.
- Use unique constraints to prevent duplicate academic records.

## Tables

### student_class_enrollments

Fields:
- id
- student_id foreign key students
- school_class_id foreign key school_classes
- academic_year_id foreign key academic_years
- semester_id foreign key semesters
- enrollment_status default active
- is_active default true
- created_by nullable foreign key users
- timestamps

Unique:
- unique student_id, academic_year_id, semester_id

### homeroom_assignments

Fields:
- id
- teacher_id foreign key teachers
- school_class_id foreign key school_classes
- academic_year_id foreign key academic_years
- semester_id foreign key semesters
- timestamps

Unique:
- unique school_class_id, academic_year_id, semester_id

### teaching_assignments

Fields:
- id
- teacher_id foreign key teachers
- subject_id foreign key subjects
- school_class_id foreign key school_classes
- academic_year_id foreign key academic_years
- semester_id foreign key semesters
- timestamps

Unique:
- unique teacher_id, subject_id, school_class_id, academic_year_id, semester_id

### grade_components

Fields:
- id
- teaching_assignment_id foreign key teaching_assignments
- name
- type nullable
- weight decimal default 0
- sort_order default 0
- timestamps

Unique:
- unique teaching_assignment_id, name

## Acceptance Criteria

- Migrations run successfully.
- Existing auth and master data migrations still work.
- students table still does not have class_id.
- migrate:fresh --seed works.