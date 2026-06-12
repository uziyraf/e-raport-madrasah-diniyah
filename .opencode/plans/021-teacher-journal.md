# Plan: Teacher Journal

## Goal

Create teacher journal feature for Wali Kelas and Guru Fan/Mapel.

## Scope

Implement journal records for:

- hafalan
- legalisir kitab
- nilai harian
- nilai ujian / tamrinan

## Rules

- Use Blade and Tailwind CSS.
- Follow docs/05-ui-guidelines.md.
- Use Form Requests for validation.
- Do not create report card generation yet.
- Do not create attendance features.
- Do not change authentication logic.
- Do not add class_id to students.
- Use guru_fan, not guru_mapel.
- Keep controllers thin.

## Concept

Teacher journal is used for learning progress records.

It is different from final subject grades.

Final subject grades are stored in grades.

Teacher journal may contain:
- memorization progress
- kitab legalization progress
- daily score
- exam/tamrinan score
- teacher notes

## Access Rules

Guru Fan/Mapel:
- can create journal records for teaching assignments they own.

Wali Kelas:
- can create journal records for their homeroom class.
- can also create journal records for teaching assignments they own if they also teach.

Admin:
- can monitor all journal records if simple.

## Database

If teacher_journals table does not exist, create migration.

teacher_journals fields:

- id
- journal_date date
- teacher_id foreign key teachers
- teaching_assignment_id nullable foreign key teaching_assignments
- school_class_id foreign key school_classes
- academic_year_id foreign key academic_years
- semester_id foreign key semesters
- journal_type string
- student_id nullable foreign key students
- memorization_type nullable string
- memorization_target nullable string
- memorization_result nullable string
- kitab_name nullable string
- kitab_page nullable string
- legalization_status nullable string
- daily_score nullable decimal
- exam_score nullable decimal
- predicate nullable string
- note nullable text
- status default draft
- created_by nullable foreign key users
- timestamps

journal_type values:
- hafalan
- legalisir_kitab
- nilai_harian
- tamrinan
- catatan

status values:
- draft
- submitted

## Routes

Teacher/Guru input:

- teacher.journals.index
- teacher.journals.create
- teacher.journals.store
- teacher.journals.show
- teacher.journals.edit
- teacher.journals.update
- teacher.journals.destroy

Homeroom/Wali Kelas input:

- homeroom.journals.index
- homeroom.journals.create
- homeroom.journals.store
- homeroom.journals.show
- homeroom.journals.edit
- homeroom.journals.update
- homeroom.journals.destroy

Admin monitoring optional:

- admin.journals.index
- admin.journals.show

## Form Fields

Basic section:

- Tanggal jurnal
- Jenis jurnal
- Kelas
- Fan/Mapel optional
- Santri optional
- Status

Hafalan section:

- Jenis hafalan
- Target hafalan
- Capaian hafalan
- Nilai / predikat
- Keterangan

Legalisir Kitab section:

- Nama kitab
- Halaman / bagian kitab
- Status legalisir
- Keterangan

Nilai Harian / Tamrinan section:

- Nilai harian
- Nilai ujian / tamrinan
- Predikat
- Keterangan

## Page Design

Index page:
- show filters:
  - journal_type
  - class
  - date
  - status
- use pagination
- show cards or table with:
  - date
  - type
  - class
  - subject if exists
  - student if exists
  - status
  - action

Create/Edit page:
- use sectioned form
- show/hide relevant fields based on journal_type if simple
- if JS is too much, show all optional fields but group them clearly

Detail page:
- show identity card:
  - teacher
  - class
  - subject
  - date
  - type
  - status
- show journal content card.

## Validation

journal_date:
- required
- date

journal_type:
- required
- in hafalan,legalisir_kitab,nilai_harian,tamrinan,catatan

school_class_id:
- required
- exists school_classes id

academic_year_id:
- required
- exists academic_years id

semester_id:
- required
- exists semesters id

teaching_assignment_id:
- nullable
- exists teaching_assignments id

student_id:
- nullable
- exists students id

memorization_type:
- nullable
- string
- max 100

memorization_target:
- nullable
- string
- max 255

memorization_result:
- nullable
- string
- max 255

kitab_name:
- nullable
- string
- max 255

kitab_page:
- nullable
- string
- max 100

legalization_status:
- nullable
- string
- max 100

daily_score:
- nullable
- numeric
- min 0
- max 100

exam_score:
- nullable
- numeric
- min 0
- max 100

predicate:
- nullable
- string
- max 50

note:
- nullable
- string

status:
- required
- in draft,submitted

## Acceptance Criteria

- Guru Fan can create journal records for their teaching assignments.
- Wali Kelas can create journal records for their homeroom class.
- Journal supports hafalan, legalisir kitab, nilai harian, tamrinan, and catatan.
- Journal does not affect final grades.
- Index uses pagination.
- Forms use validation.
- No report card generation is created.
- No class_id is added to students.
- migrate:fresh --seed works.