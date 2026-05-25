# Plan: Master Data Migrations

## Goal

Create core master data migrations for SIMADU.

## Scope

Create migrations for:

- teachers
- students
- levels
- school_classes
- academic_years
- semesters
- subjects

## Rules

- Do not create academic relation tables yet.
- Do not create CRUD features.
- Do not create controllers.
- Do not create views.
- Do not add class_id to students.
- Use guru_fan, not guru_mapel.
- Use arabic_name for subjects.
- Use status fields for long-term data.

## Tables

### teachers

Fields:
- id
- user_id nullable foreign key users
- teacher_code unique nullable
- name
- gender nullable
- phone nullable
- address nullable
- signature_path nullable
- status default active
- timestamps

### students

Fields:
- id
- nis unique
- name
- gender nullable
- birth_place nullable
- birth_date nullable
- address nullable
- guardian_phone nullable
- status default active
- timestamps

Important:
- Do not add class_id to students.

### levels

Fields:
- id
- name
- description nullable
- sort_order default 0
- status default active
- timestamps

### school_classes

Fields:
- id
- level_id foreign key levels
- name
- code nullable
- sort_order default 0
- status default active
- timestamps

### academic_years

Fields:
- id
- name
- start_date nullable
- end_date nullable
- is_active default false
- timestamps

### semesters

Fields:
- id
- academic_year_id foreign key academic_years
- name
- start_date nullable
- end_date nullable
- is_active default false
- timestamps

### subjects

Fields:
- id
- name
- arabic_name nullable
- code nullable
- sort_order default 0
- status default active
- timestamps

## Acceptance Criteria

- All migrations run successfully.
- No academic relation tables are created.
- students table does not have class_id.
- subjects table has arabic_name.
- migrate:fresh --seed works.