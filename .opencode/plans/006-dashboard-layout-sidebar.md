# Plan: Dashboard Layout and Dynamic Sidebar

## Goal

Create the main authenticated dashboard layout with dynamic sidebar navigation based on user role.

## Scope

Implement:

- main app layout
- sidebar partial
- topbar partial
- dynamic role-based navigation config
- placeholder dashboard pages for each role

## Rules

- Use Blade and Tailwind CSS.
- Follow docs/05-ui-guidelines.md.
- Use one sidebar component only.
- Sidebar content must come from config/navigation.php.
- Do not create separate sidebar files per role.
- Use guru_fan, not guru_mapel.
- Do not create academic CRUD features yet.
- Do not create database migrations.
- Do not change authentication logic.

## Role Navigation

Use these internal roles:

- super_admin
- kepala_sekolah
- wali_kelas
- guru_fan
- wali_santri

## Expected Files

Possible files:

- config/navigation.php
- resources/views/layouts/app.blade.php
- resources/views/partials/sidebar.blade.php
- resources/views/partials/topbar.blade.php
- resources/views/dashboards/admin.blade.php
- resources/views/dashboards/principal.blade.php
- resources/views/dashboards/homeroom.blade.php
- resources/views/dashboards/teacher.blade.php
- resources/views/dashboards/guardian.blade.php

## Acceptance Criteria

- Each role sees different sidebar menu.
- Only one sidebar partial is used.
- Dashboard layout follows SIMADU visual direction.
- Login still works.
- Role redirect still works.
- No guru_mapel naming appears.