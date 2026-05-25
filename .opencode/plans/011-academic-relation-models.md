# Plan: Academic Relation Models

## Goal

Create Eloquent models and relationships for SIRAFAH academic relation tables.

## Scope

Create models for:

- StudentClassEnrollment
- HomeroomAssignment
- TeachingAssignment
- GradeComponent

## Rules

- Do not create controllers.
- Do not create views.
- Do not create CRUD.
- Do not create migrations.
- Do not create report card features.
- Do not create journal features.
- Do not add class_id to students.
- Use guru_fan, not guru_mapel.

## Expected Relationships

StudentClassEnrollment:
- belongsTo Student
- belongsTo SchoolClass
- belongsTo AcademicYear
- belongsTo Semester
- belongsTo User as creator

HomeroomAssignment:
- belongsTo Teacher
- belongsTo SchoolClass
- belongsTo AcademicYear
- belongsTo Semester

TeachingAssignment:
- belongsTo Teacher
- belongsTo Subject
- belongsTo SchoolClass
- belongsTo AcademicYear
- belongsTo Semester
- hasMany GradeComponent

GradeComponent:
- belongsTo TeachingAssignment

## Model Requirements

Each model should include:

- fillable fields
- casts where needed
- relationship methods
- simple active scope if useful

## Acceptance Criteria

- Models exist.
- Relationships are defined correctly.
- Existing master data models are linked where needed.
- Student model still does not have class_id.
- migrate:fresh --seed still works.