# Plan: Academic Relation Seeders

## Goal

Create dummy seeders for SIMADU academic relation tables.

## Scope

Create dummy data for:

- student_class_enrollments
- homeroom_assignments
- teaching_assignments
- grade_components

## Rules

- Use dummy data only.
- Do not use real santri data.
- Do not create controllers.
- Do not create views.
- Do not create CRUD.
- Do not create migrations.
- Do not create report card features.
- Do not create journal features.
- Do not add class_id to students.
- Use guru_fan, not guru_mapel.
- Keep existing auth and master data seeders working.

## Expected Dummy Data

- Enroll existing dummy students into active classes.
- Assign several teachers as wali kelas.
- Assign several teachers to teach fan/mapel in classes.
- Create grade components for teaching assignments.

## Grade Component Examples

- Nilai Harian
- Tamrinan
- Ujian Akhir

## Acceptance Criteria

- migrate:fresh --seed works.
- StudentClassEnrollment has records.
- HomeroomAssignment has records.
- TeachingAssignment has records.
- GradeComponent has records.
- No duplicate records violate unique constraints.