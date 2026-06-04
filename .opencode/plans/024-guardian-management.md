# Plan: Guardian / Wali Santri Management

## Goal

Create guardian management feature for SIMADU.

## Scope

Implement:

- guardian master data
- optional guardian login account
- guardian-student relationship
- admin CRUD for wali santri
- basic wali santri dashboard/data access

## Rules

- Do not create report card features.
- Do not create PDF export.
- Do not change grades, attendance, journal, or attitude logic.
- Do not change authentication logic except guardian account creation/linking.
- Do not add class_id to students.
- Use guru_fan, not guru_mapel.
- Use Blade and Tailwind CSS.
- Follow docs/05-ui-guidelines.md.
- Use Form Request validation.
- Keep controllers thin.

## Data Concept

Student table may store guardian_name and guardian_phone as simple contact data.

But wali santri login access should use separate guardians table and guardian_students pivot.

This allows:
- one guardian linked to one student
- one guardian linked to multiple students
- student still keeps simple guardian contact info

## Database

If guardians table does not exist, create migration.

guardians fields:

- id
- user_id nullable foreign key users
- name
- phone nullable
- email nullable
- address nullable
- relationship nullable
- status default active
- timestamps

If guardian_students table does not exist, create migration.

guardian_students fields:

- id
- guardian_id foreign key guardians
- student_id foreign key students
- relationship nullable
- is_primary default false
- timestamps

Unique:

- guardian_id, student_id

## Model Requirements

Create/update models:

- Guardian
- GuardianStudent if needed

Relationships:

Guardian:
- belongsTo User
- belongsToMany Student

Student:
- belongsToMany Guardian

User:
- hasOne Guardian if useful

## Admin Routes

- admin.guardians.index
- admin.guardians.create
- admin.guardians.store
- admin.guardians.show
- admin.guardians.edit
- admin.guardians.update
- admin.guardians.destroy

## Admin Form Sections

### Data Wali

- Nama wali
- Nomor HP / WhatsApp
- Email
- Alamat
- Hubungan dengan santri
- Status

### Akun Login

- Buat akun login?
- Username
- Password

Role account:

- wali_santri

Rules:

- If create account is checked, username and password are required.
- Email can be used if available.
- Do not allow duplicate email/username.
- Do not assign roles other than wali_santri from this CRUD.

### Santri Terhubung

- Select one or more students.
- Can search by NIS or student name.
- Set primary guardian if simple.

## Admin Index

Show:

- Nama wali
- Nomor HP
- Email
- Jumlah santri terhubung
- Status
- Aksi

Support search:

- guardian name
- phone
- email
- student name
- NIS

Use pagination.

## Admin Detail

Show cards:

- Card Data Wali
- Card Akun Login
- Card Santri Terhubung

Student linked list should show:

- NIS
- Nama Santri
- Kelas aktif if available
- Status Santri

## Wali Santri Routes

Basic routes:

- guardian.students.index
- guardian.students.show
- guardian.attendances.index

## Wali Santri Behavior

When wali_santri logs in:

- detect guardian profile from logged-in user
- show linked students only
- do not show other students
- if no guardian profile or no linked students, show friendly empty state

For this phase:

Data Santri page:
- show linked student cards

Absensi Santri page:
- show attendance summary/history for linked students if simple

Do not create report card view yet if report card feature is skipped.

## Validation

Guardian:

name:
- required
- string
- max 255

phone:
- nullable
- string
- max 30

email:
- nullable
- email
- max 255

address:
- nullable
- string

relationship:
- nullable
- string
- max 100

status:
- required
- in active,inactive

username:
- nullable / required if create_account
- string
- max 100
- unique users username

password:
- nullable / required if create_account
- string
- min 8

students:
- nullable array

students.*:
- exists students id

## Acceptance Criteria

- Admin can create wali santri.
- Admin can optionally create login account for wali santri.
- Guardian account gets wali_santri role.
- Admin can link guardian to one or more students.
- Admin can view guardian detail and linked students.
- Wali santri can login and see only linked students.
- Wali santri cannot access unrelated students.
- No report card feature is created.
- No class_id is added to students.
- migrate:fresh --seed works.