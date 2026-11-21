# Plan: Grouped Accordion Sidebar Navigation

## Perubahan

Sidebar diubah dari flat list menjadi grouped accordion menu untuk beberapa role.

## Struktur Akhir

### Super Admin
- Dashboard (link langsung)
- **Master Data** → Data User, Data Guru, Data Santri, Data Wali Santri, Jenjang, Kelas, Fan/Mapel
- **Akademik** → Tahun Ajaran, Semester, Wali Kelas, Guru Fan/Mapel, Jadwal Pelajaran (sub-accordion), Kenaikan/Penempatan Santri
- **Monitoring** → Monitoring Nilai, Monitoring Jurnal, Monitoring Absensi
- **Raport** → Preview Raport, Export Data

### Kepala Sekolah
- Dashboard (link langsung)
- **Data Akademik** → Data Guru, Data Santri, Data Wali Santri, Jenjang, Kelas, Fan/Mapel, Tahun Ajaran, Semester
- **Monitoring** → Jadwal Pelajaran (sub-accordion), Monitoring Nilai, Monitoring Jurnal, Monitoring Absensi
- **Raport** → Preview Raport Arab, Export Data

### Wali Kelas
- Dashboard (link langsung)
- **Kelas Saya** → Data Santri Kelas, Jadwal Kelas (sub-accordion), Absensi Santri
- **Akademik Kelas** → Jurnal Guru, Nilai Sikap, Raport Santri
- **Laporan** → Export Rekap
- Profil (link langsung)

### Guru Fan
- Dashboard (link langsung)
- **Mengajar** → Jadwal Mengajar (sub-accordion), Kelas & Fan/Mapel, Daftar Santri
- **Akademik** → Jurnal Guru, Submit Nilai, Rekap Nilai
- **Laporan** → Export Nilai
- Profil (link langsung)

### Wali Santri
Tetap flat list (Dashboard, Data Santri, Absensi Santri, Raport Santri, Download Raport, Profil).

## File yang Berubah

1. `config/navigation.php` — direstruktur dengan parent-child groups
2. `resources/views/partials/sidebar.blade.php` — recursive accordion dengan Alpine.js

## Detail Teknis

### Config format baru
```php
[
    'label' => 'Nama Group',
    'icon' => 'bx bx-icon',
    'active_patterns' => ['route.pattern.*'],
    'children' => [
        'key' => ['label' => 'Submenu', 'route' => 'route.name', 'icon' => 'bx bx-icon'],
    ],
]
```

- `children` → membuat parent accordion
- `active_patterns` → array wildcard untuk deteksi active state
- Route dicek dengan `Route::has()` — kalau belum ada, item disembunyikan

### Active state
- Parent terbuka otomatis jika route aktif cocok dengan `active_patterns` parent atau rute mana pun di children (rekursif)
- Fungsi `isMenuActive()` traverses seluruh tree

### Nested accordion
Mendukung 2 level (parent → child → grandchild) untuk kasus seperti Akademik > Jadwal Pelajaran > List Semua.

### Teknologi
- Alpine.js (`x-data`, `x-show`, `x-transition`) untuk toggle accordion
- Blade + Tailwind CSS untuk styling
- Tidak ada dependency baru

## Tidak Berubah
- Auth, role, permission, route — tidak disentuh
- Fitur akademik (kenaikan, jadwal, jurnal, absensi, monitoring, raport) — tidak diubah
- Naming `guru_fan` tetap (tidak pakai `guru_mapel`)
- Satu sidebar partial untuk semua role
