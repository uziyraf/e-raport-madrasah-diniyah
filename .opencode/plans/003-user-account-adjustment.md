# Plan: User Account Adjustment

## Goal

Adjust the users table and authentication-related user fields for SIMADU.

## Scope

Add these fields to users:

- username
- phone
- status
- last_login_at

Update:

- users migration using a new migration file
- User model fillable/casts
- RolePermissionSeeder default super admin data

## Rules

- Do not modify the original create_users_table migration.
- Create a new migration.
- Do not create academic tables.
- Do not create CRUD features.
- Do not change login UI yet.
- Do not implement guardian registration yet.
- Use guru_fan, not guru_mapel.

## Field Rules

username:
- nullable for now
- unique
- used later for staff login

phone:
- nullable

status:
- default active
- possible values: active, inactive, blocked

last_login_at:
- nullable timestamp

## Default Super Admin

Update default super admin seeder:

- name: Super Admin
- username: admin
- email: admin@simadu.test
- password: password
- status: active

## Acceptance Criteria

- New migration exists.
- users table has username, phone, status, last_login_at.
- User model includes new fields.
- RolePermissionSeeder sets username and status for default super admin.
- migrate:fresh --seed works.
- Login still works with admin@simadu.test and password.