# Plan: Grouped Accordion Sidebar Navigation

## Goal

Refactor the authenticated dashboard sidebar into grouped accordion navigation so the sidebar is cleaner and does not become too long.

The sidebar must still be dynamic based on user role, but menu items should be grouped into parent menus with collapsible children.

## Context

The current sidebar displays all menu items directly in one long vertical list. This makes the Super Admin sidebar too long, and the same issue may happen for Wali Kelas and Guru Fan as their features grow.

The project already uses one sidebar component and role-based navigation from `config/navigation.php`. Keep this approach.

## Scope

Implement:

* grouped sidebar navigation
* collapsible/accordion parent menu
* nested children menu from `config/navigation.php`
* active submenu state
* active parent menu state
* auto-open parent group when current route belongs to its children
* grouped navigation for:

  * Super Admin
  * Wali Kelas
  * Guru Fan
* keep one sidebar partial only

## Rules

* Use Blade and Tailwind CSS.
* Follow `docs/05-ui-guidelines.md`.
* Use one sidebar component only.
* Sidebar content must come from `config/navigation.php`.
* Do not create separate sidebar files per role.
* Use `guru_fan`, not `guru_mapel`.
* Do not create new academic CRUD features.
* Do not create database migrations.
* Do not change authentication logic.
* Do not change role redirect logic.
* Do not change existing routes.
* Do not change existing permissions.
* Do not break existing features:

  * Kenaikan/Penempatan Santri
  * Jadwal Pelajaran
  * Template Jadwal Pelajaran
  * Jurnal Guru
  * Absensi Santri
  * Monitoring
  * Raport

## Role Navigation

Use these internal roles:

* super_admin
* kepala_sekolah
* wali_kelas
* guru_fan
* wali_santri

## Sidebar Structure

### Super Admin

Dashboard

Master Data

* Data User
* Data Guru
* Data Santri
* Data Wali Santri
* Jenjang
* Kelas
* Fan/Mapel

Akademik

* Tahun Ajaran
* Semester
* Wali Kelas
* Guru Fan/Mapel
* Jadwal Pelajaran
* Kenaikan/Penempatan Santri

Monitoring

* Monitoring Nilai
* Monitoring Jurnal
* Monitoring Absensi

Raport

* Preview Raport    
* Export Raport if route exists

### Wali Kelas

Dashboard

Kelas Saya

* Data Santri Kelas
* Jadwal Kelas
* Absensi Santri

Akademik Kelas

* Jurnal Guru
* Nilai Sikap
* Raport Santri

Laporan

* Export Rekap if route exists

Profil

* Profil

### Guru Fan

Dashboard

Mengajar

* Jadwal Mengajar
* Kelas & Fan/Mapel
* Daftar Santri

Akademik

* Jurnal Guru
* Submit Nilai
* Rekap Nilai

Laporan

* Export Nilai if route exists

Profil

* Profil

### Kepala Sekolah

If the Kepala Sekolah sidebar is also long, group it like this:

Dashboard

Data Akademik

* Data Guru
* Data Santri
* Data Wali Santri
* Jenjang
* Kelas
* Fan/Mapel
* Tahun Ajaran
* Semester

Monitoring

* Jadwal Pelajaran
* Monitoring Nilai
* Monitoring Jurnal
* Monitoring Absensi

Raport

* Preview Raport Arab
* Preview Raport Latin if route exists
* Export Data if route exists

### Wali Santri

Keep simple unless the menu becomes long:

Dashboard

* Absensi Santri
* Raport Santri
* Profil

## Config Navigation Requirement

Update `config/navigation.php` so it supports parent-child menu structure.

Example structure:

* label
* route or url
* icon optional
* roles
* active_patterns
* children optional

A parent menu can have children.
A child menu should keep the existing route names.
Do not invent routes that do not exist.

## Sidebar Component Requirement

Update the existing sidebar partial, likely:

* `resources/views/partials/sidebar.blade.php`
  or
* `resources/views/layouts/partials/sidebar.blade.php`

The sidebar must:

1. Render normal single menu items.
2. Render parent menu with children.
3. Hide child menu by default.
4. Open child menu when parent is clicked.
5. Automatically open parent menu if one of its children is active.
6. Show active style on the current submenu.
7. Show active/open style on the parent menu.
8. Show chevron icon that rotates or changes when menu opens.
9. Keep sidebar scrollable.
10. Keep mobile sidebar behavior safe.

## Technical Suggestion

Use Alpine.js if it already exists in the project.

If Alpine.js is not available, use simple vanilla JavaScript.

Do not add heavy frontend dependencies.

## Active State Logic

Parent menu should be open when:

* the current route matches the parent route, or
* the current route matches one of the child active patterns.

Examples:

* If current route is Data Santri, open Master Data.
* If current route is Jadwal Pelajaran, open Akademik.
* If current route is Monitoring Jurnal, open Monitoring.
* If current route is Raport, open Raport.
* If current route is Jurnal Guru for Wali Kelas, open Akademik Kelas.
* If current route is Jadwal Mengajar for Guru Fan, open Mengajar.

## Expected Files

Possible files:

* `config/navigation.php`
* `resources/views/layouts/app.blade.php`
* `resources/views/partials/sidebar.blade.php`
* `resources/views/partials/topbar.blade.php`

Only update dashboard placeholder files if needed, but this task should focus on sidebar/navigation.

## Acceptance Criteria

* Super Admin sidebar is grouped and no longer appears as one long flat list.
* Wali Kelas sidebar also uses grouped accordion menu.
* Guru Fan sidebar also uses grouped accordion menu.
* Kepala Sekolah sidebar remains working and may also use grouped menu if needed.
* Wali Santri sidebar remains working.
* Each role still sees different sidebar menu according to access.
* Only one sidebar partial is used.
* Sidebar menu still comes from `config/navigation.php`.
* Parent menu can be clicked to show/hide submenu.
* Parent menu automatically opens when a submenu is active.
* Active submenu has active styling.
* Login still works.
* Role redirect still works.
* No `guru_mapel` naming appears.
* No route is broken.
* No existing feature is changed.
