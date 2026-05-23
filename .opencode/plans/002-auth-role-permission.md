# Plan: Auth and Role Permission

## Goal

Set up authentication and role permission foundation for SIMADU.

## Scope

Implement:

- Laravel authentication foundation
- login
- logout
- disable public staff registration
- Spatie Laravel Permission
- internal roles:
  - super_admin
  - kepala_sekolah
  - wali_kelas
  - guru_fan
  - wali_santri
- default super admin seeder
- role-based redirect after login
- basic dashboard route per role

## Excluded

Do not implement:

- master data CRUD
- student database migrations
- teacher database migrations
- report cards
- journals
- guardian self-registration
- WhatsApp OTP
- SMS OTP
- full custom Figma UI

## Important Rules

- Use `guru_fan`, not `guru_mapel`.
- Staff registration must be disabled.
- Staff accounts are created by Super Admin later.
- Wali Santri registration is not implemented in this phase.
- Do not create academic tables in this phase.
- Do not edit ERD unless explicitly requested.
- Do not change docs unless required by this plan.

## Expected Packages

- Laravel Breeze or equivalent Laravel auth foundation
- Spatie Laravel Permission

## Expected Roles

Create these roles:

- super_admin
- kepala_sekolah
- wali_kelas
- guru_fan
- wali_santri

## Expected Files / Areas

Possible files to create or modify:

- composer.json
- routes/auth.php
- routes/web.php
- app/Models/User.php
- database/seeders/RolePermissionSeeder.php
- database/seeders/DatabaseSeeder.php
- resources/views/auth/login.blade.php
- resources/views/dashboard.blade.php or role dashboard views
- app/Http/Controllers/Auth/AuthenticatedSessionController.php

## Acceptance Criteria

- Login works.
- Logout works.
- Public staff register is disabled.
- Spatie Permission is installed and migrated.
- User model uses `HasRoles`.
- Roles are seeded correctly.
- Default super admin exists.
- After login, user is redirected based on role.
- No `guru_mapel` naming appears.
- No academic feature is implemented yet.

## Validation Commands

```bash
composer install
php artisan migrate:fresh --seed
php artisan route:list
php artisan optimize:clear
npm install
npm run build