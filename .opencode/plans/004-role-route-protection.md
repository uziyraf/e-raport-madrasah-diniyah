# Plan: Role Route Protection

## Goal

Protect role-based dashboard routes so users cannot access other role dashboards by typing URLs manually.

## Scope

Implement route protection for these roles:

- super_admin
- kepala_sekolah
- wali_kelas
- guru_fan
- wali_santri

## Rules

- Use Spatie Laravel Permission role middleware.
- Use guru_fan, not guru_mapel.
- Do not create academic tables.
- Do not create CRUD features.
- Do not modify login UI.
- Do not implement sidebar yet.
- Do not implement guardian registration.

## Routes to Protect

- /admin/dashboard -> super_admin
- /kepala-sekolah/dashboard -> kepala_sekolah
- /wali-kelas/dashboard -> wali_kelas
- /guru-fan/dashboard -> guru_fan
- /wali-santri/dashboard -> wali_santri

## Acceptance Criteria

- Super Admin can access /admin/dashboard.
- Kepala Sekolah can access /kepala-sekolah/dashboard.
- Wali Kelas can access /wali-kelas/dashboard.
- Guru Fan can access /guru-fan/dashboard.
- Wali Santri can access /wali-santri/dashboard.
- A user cannot access another role dashboard.
- No guru_mapel naming appears.