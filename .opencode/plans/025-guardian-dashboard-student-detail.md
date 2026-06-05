# Plan: Guardian Dashboard and Student Detail

## Goal

Improve Wali Santri portal by adding dashboard and student detail pages.

## Scope

Implement:

- Wali Santri dashboard
- linked student cards
- student detail page
- attendance summary shortcut
- access protection for linked students only

## Rules

- Do not create report card feature yet.
- Do not create PDF export.
- Do not change grades, journals, attitudes, or attendance input.
- Do not change authentication logic.
- Do not add class_id to students.
- Use guru_fan, not guru_mapel.
- Use Blade and Tailwind CSS.
- Follow docs/05-ui-guidelines.md.
- Keep controllers thin.

## Data Rules

Wali Santri access must use:

- guardians
- guardian_students
- students

Do not use students.guardian_id.

A guardian can have multiple students.

A student can have multiple guardians.

## Routes

Use or create:

- guardian.dashboard
- guardian.students.index
- guardian.students.show
- guardian.attendances.index

## Dashboard

Route:

- guardian.dashboard

Dashboard should show:

- total linked students
- latest attendance summary if simple
- linked student cards
- shortcut buttons:
  - Data Santri
  - Absensi Santri
  - Raport Santri disabled or placeholder if report card skipped

Each student card should show:

- student photo if available
- student name
- NIS
- active class
- status
- button: Lihat Detail
- button: Lihat Absensi

## Data Santri Index

Route:

- guardian.students.index

Show all linked students as cards.

Each card should show:

- photo
- NIS
- name
- active class
- academic year
- semester
- student status
- action: Detail

If guardian has no linked students, show friendly empty state.

## Student Detail

Route:

- guardian.students.show

Show student identity detail:

- photo
- NIS
- name
- gender
- birth place/date
- address
- father name
- mother name
- guardian name
- guardian phone
- active class
- level
- academic year
- semester
- student status

Also show summary cards:

- attendance summary
- latest attendance records if simple
- report card placeholder if report card skipped

## Attendance Shortcut

From student card/detail, link to attendance page for selected student.

If guardian.attendances.index already supports selected student, use it.

If needed, support query parameter:

- guardian.attendances.index?student_id={student}

## Access Rules

- Detect guardian profile from logged-in user.
- Only show linked students.
- If a student is not linked to guardian, return 403 or redirect back.
- Do not allow access to unrelated students by URL manipulation.

## Navigation

- Wali Santri Dashboard points to guardian.dashboard.
- Data Santri points to guardian.students.index.
- Absensi Santri points to guardian.attendances.index.
- Raport Santri remains disabled/placeholder if report card feature is skipped.

## Acceptance Criteria

- Wali Santri dashboard works.
- Wali Santri sees all linked students.
- Wali Santri can view detail of linked students.
- Wali Santri cannot access unrelated students.
- Student card shows active class.
- Attendance shortcut works.
- Report card is not implemented yet.
- No class_id is added to students.
- migrate:fresh --seed works.