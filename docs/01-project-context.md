

```md
# Project Context

## Nama Sistem

Sistem E-Raport dan Akademik Madrasah Diniyah Pondok Pesantren.

## Tujuan Sistem

Sistem ini digunakan untuk membantu pengelolaan data akademik madrasah diniyah, termasuk data santri, guru, kelas, fan/mapel, tahun ajaran, semester, nilai, absensi, jurnal guru, raport, dan penempatan santri.

## Pengguna Sistem

Sistem memiliki 5 role utama:

1. Super Admin
2. Kepala Sekolah
3. Wali Kelas
4. Guru Fan/Mapel
5. Wali Santri

## Prinsip Data Jangka Panjang

Data santri tidak dihapus ketika santri lulus, keluar, atau nonaktif. Data santri tetap disimpan sebagai arsip akademik.

Kelas santri tidak disimpan langsung pada tabel `students`. Riwayat kelas santri disimpan pada tabel `student_class_enrollments`.

## Output Utama

- Raport PDF
- Rekap Excel
- Monitoring nilai
- Monitoring absensi
- Monitoring jurnal guru
- Data akademik historis