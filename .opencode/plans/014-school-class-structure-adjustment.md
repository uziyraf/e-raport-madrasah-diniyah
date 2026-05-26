# Plan: School Class Structure Adjustment

## Goal

Improve school_classes structure by adding academic grade level and parallel/class group fields.

## Reason

Current school_classes only has level_id, name, code, sort_order, and status.

This is not enough because the system needs to know:
- the academic class level, such as 1, 2, 3
- the parallel/group name, such as A, B, C
- the display order separately from academic level

Important distinction:
- grade_level = academic level
- parallel_name = class group / rombel / parallel
- sort_order = display order only

## Scope

Update:

- school_classes table using a new migration
- SchoolClass model
- master data seeder for school classes
- School Classes CRUD form and validation
- School Classes index display

## Rules

- Do not edit old migrations.
- Create a new migration.
- Do not create academic year CRUD.
- Do not create semester CRUD.
- Do not create student CRUD.
- Do not create teacher CRUD.
- Do not change authentication logic.
- Do not add class_id to students.
- Use guru_fan, not guru_mapel.

## Database Changes

Add fields to school_classes:

- grade_level unsigned tiny integer nullable after level_id
- parallel_name string nullable after grade_level

Add index:

- index level_id and grade_level

## Model Changes

Update SchoolClass fillable:

- level_id
- grade_level
- parallel_name
- name
- code
- sort_order
- status

Add casts:

- grade_level integer
- sort_order integer

## CRUD Changes

Update school class form fields:

- Jenjang
- Tingkat Kelas
- Paralel
- Nama Kelas
- Kode
- Urutan
- Status

Update validation:

- level_id required exists levels id
- grade_level nullable integer min 1 max 20
- parallel_name nullable string max 50
- name required string max 255
- code nullable string max 50
- sort_order nullable integer min 0
- status required in active,inactive

## Acceptance Criteria

- New migration exists.
- school_classes table has grade_level and parallel_name.
- SchoolClass model has new fields.
- School class create/edit form has grade_level and parallel_name.
- School class index displays grade_level and parallel_name.
- Master data seeder creates school classes with grade_level and parallel_name.
- migrate:fresh --seed works.
- No students.class_id is created.