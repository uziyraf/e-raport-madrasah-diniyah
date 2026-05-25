# Plan: Master Data Models

## Goal

Create Eloquent models and relationships for SIMADU master data tables.

## Scope

Create models for:

- Teacher
- Student
- Level
- SchoolClass
- AcademicYear
- Semester
- Subject

## Rules

- Do not create controllers.
- Do not create views.
- Do not create CRUD.
- Do not create new migrations.
- Do not create academic relation models yet.
- Do not add class_id to Student.
- Use guru_fan, not guru_mapel.

## Expected Relationships

Teacher:
- belongsTo User

Student:
- no class_id relationship yet

Level:
- hasMany SchoolClass

SchoolClass:
- belongsTo Level

AcademicYear:
- hasMany Semester

Semester:
- belongsTo AcademicYear

Subject:
- no relation yet

## Model Requirements

Each model should include:

- fillable fields
- casts where needed
- relationship methods
- simple active scope if useful

## Acceptance Criteria

- Models exist.
- Relationships are defined correctly.
- Student model does not include class_id.
- php artisan test does not fail because of syntax errors.
- migrate:fresh --seed still works.