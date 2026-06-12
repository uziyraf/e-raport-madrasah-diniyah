# Plan: Attendance Input

## Goal

Create attendance input feature for Wali Kelas and Guru Fan/Mapel.

## Scope

Implement:

- attendance session input
- attendance detail per student
- attendance history per student
- homeroom attendance flow
- teaching attendance flow

## Rules

- Use Blade and Tailwind CSS.
- Follow docs/05-ui-guidelines.md.
- Use Form Requests for validation.
- Do not create report card generation yet.
- Do not change final grades feature.
- Do not change teacher journal feature.
- Do not change authentication logic.
- Do not add class_id to students.
- Use guru_fan, not guru_mapel.
- Keep controllers thin.
- Use pagination where needed.

## Concept

Attendance is student-based and session-based.

One attendance session belongs to one class context and one date.

Each attendance session has many student attendance details.

Wali Kelas and Guru Fan use similar flow.

Difference:

- Wali Kelas:
  class context comes automatically from homeroom_assignments.

- Guru Fan:
  class context comes from selected teaching_assignments owned by the logged-in teacher.

## Database

Create attendance_sessions table if not exists.

Fields:

- id
- attendance_date date
- attendance_type string
- teacher_id foreign key teachers
- teaching_assignment_id nullable foreign key teaching_assignments
- school_class_id foreign key school_classes
- academic_year_id foreign key academic_years
- semester_id foreign key semesters
- status string default draft
- created_by nullable foreign key users
- timestamps

attendance_type values:

- homeroom
- teaching

status values:

- draft
- submitted

Create attendance_details table if not exists.

Fields:

- id
- attendance_session_id foreign key attendance_sessions
- student_id foreign key students
- status string
- note nullable text
- timestamps

detail status values:

- present
- permission
- sick
- absent

Unique:

- unique attendance_session_id and student_id

Optional unique for sessions:

- For homeroom:
  prevent duplicate attendance session for same date, type, teacher, class, academic year, semester.
- For teaching:
  prevent duplicate attendance session for same date, teaching_assignment, class, academic year, semester.

## Homeroom Routes

- homeroom.attendances.index
- homeroom.attendances.create
- homeroom.attendances.store
- homeroom.attendances.show
- homeroom.attendances.edit
- homeroom.attendances.update
- homeroom.attendances.destroy

## Teacher Routes

- teacher.attendances.index
- teacher.attendances.create
- teacher.attendances.store
- teacher.attendances.show
- teacher.attendances.edit
- teacher.attendances.update
- teacher.attendances.destroy

## Homeroom Flow

1. Wali Kelas opens Absensi.
2. System detects logged-in user's teacher profile.
3. System finds active homeroom assignment.
4. System shows class context card:
   - class
   - academic year
   - semester
5. User chooses attendance_date.
6. System shows all students enrolled in that class/year/semester.
7. User selects attendance status for each student using radio buttons:
   - Hadir
   - Izin
   - Sakit
   - Alfa
8. User saves as draft or submitted.

## Teacher/Guru Fan Flow

1. Guru Fan opens Absensi Mengajar.
2. If teacher has multiple teaching assignments, user selects:
   - Kelas / Fan yang Diajar
3. User chooses attendance_date.
4. System shows all students enrolled in that assignment class/year/semester.
5. User selects attendance status for each student using radio buttons:
   - Hadir
   - Izin
   - Sakit
   - Alfa
6. User saves as draft or submitted.

## Input Form

Fields:

- attendance_date
- status draft/submitted
- student attendance list

For each student:

- student_id
- status radio:
  - present
  - permission
  - sick
  - absent
- note optional

Default selected status:

- present

## Index Page

Show attendance sessions.

Columns/cards:

- date
- type
- class
- subject/fan if teaching attendance
- teacher
- total students
- present count
- permission count
- sick count
- absent count
- status
- actions

Use filters:

- date range
- status
- class/context if needed

## Show Page

Show session detail:

- attendance date
- type
- class
- subject/fan if teaching
- teacher
- status

Show student list:

- No
- Nama Santri
- NIS
- Status
- Keterangan

## Student Attendance History

From show/detail page, student name can be clicked.

Student attendance history should show:

- date
- attendance type
- subject/fan if exists
- teacher
- status
- note

If too much for this phase, provide simple route/page for student attendance history.

## Validation

attendance_date:
- required
- date

status:
- required
- in draft,submitted

attendance details:
- required array

details.*.student_id:
- required
- exists students id

details.*.status:
- required
- in present,permission,sick,absent

details.*.note:
- nullable
- string

## Access Rules

- Wali Kelas can only create/view/edit attendance for their homeroom class.
- Guru Fan can only create/view/edit attendance for teaching assignments they own.
- Do not allow attendance for students outside the selected/resolved class.
- Validate access server-side.
- Do not trust school_class_id from request blindly.

## Navigation

- Wali Kelas menu "Absensi Santri" or "Jadwal Kelas" can point to homeroom.attendances.index if available.
- Guru Fan menu "Absensi Mengajar" should point to teacher.attendances.index.
- Do not create fake route names.

## Acceptance Criteria

- Wali Kelas can input attendance for their homeroom class.
- Guru Fan can input attendance for selected teaching assignment class.
- Attendance date can be selected manually.
- Form shows all enrolled students with radio buttons.
- Attendance can be saved as draft or submitted.
- Attendance history per student can be viewed.
- No class_id is added to students.
- migrate:fresh --seed works.