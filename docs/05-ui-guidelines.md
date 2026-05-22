# UI Guidelines

## Project Name

SIMADU - Sistem Informasi Madrasah Diniyah / E-Raport

## Purpose

Dokumen ini menjadi acuan visual standar untuk pengembangan antarmuka sistem e-raport menggunakan Laravel Blade dan Tailwind CSS.

UI harus terlihat:

- formal
- bersih
- akademik
- mudah dibaca
- cocok untuk lingkungan pondok pesantren/madrasah diniyah
- nyaman digunakan untuk data besar seperti santri, nilai, absensi, jurnal, dan raport

---

# 1. Color Palette

Sistem menggunakan pendekatan warna bumi atau earthy tone yang dikombinasikan dengan warna formal institusional pesantren.

## 1.1 Brand Colors

### Primary / Accent

Tailwind utility:

```text
bg-teal-950
text-teal-950
border-teal-950
```

Penggunaan:

- judul utama
- teks aksen akademik
- tombol utama
- sidebar aktif
- progress selesai
- elemen identitas sistem

### Secondary / Active Brand

Tailwind utility:

```text
bg-emerald-900
text-emerald-900
```

Penggunaan:

- background menu aktif pada sidebar
- badge sukses
- aksen hijau formal

### Border Accent

Tailwind utility:

```text
border-orange-300
bg-orange-300
```

Penggunaan:

- indikator menu aktif pada sidebar
- border-left menu aktif
- progress level medium
- highlight ringan

---

## 1.2 Neutral Colors

### Main Background

```text
bg-slate-50
```

Penggunaan:

- background utama halaman aplikasi
- area konten dashboard
- background halaman form dan tabel

### Card Background

```text
bg-white
bg-slate-50
```

Penggunaan:

- card statistik
- kontainer tabel
- form container
- preview data
- preview raport

### Border / Outline

```text
border-stone-300
outline-stone-300
```

Penggunaan:

- border card
- divider tabel
- input border
- outline komponen
- pemisah antar section

---

## 1.3 Status Colors

### Success / Selesai

```text
bg-emerald-200
text-green-950
```

Penggunaan:

- status selesai
- raport final
- data valid
- progress 100%

### Warning / Proses

```text
bg-orange-300
text-orange-950
```

Penggunaan:

- status proses
- data perlu review
- progress sedang
- warning ringan

### Danger / Error

```text
bg-red-200
text-red-950
```

Penggunaan:

- validasi gagal
- data error
- proses gagal
- blocked status

### Muted / Belum Mulai

```text
bg-zinc-200
text-neutral-700
```

Penggunaan:

- status belum mulai
- data kosong
- counter ringan
- badge pasif

---

# 2. Typography

## 2.1 Font Family

Font utama:

```text
Liberation Serif
```

Fallback:

```text
Georgia
Times New Roman
serif
```

Font Arab:

```text
Noto Naskh Arabic
Amiri
serif
```

Catatan:

- Font Latin digunakan untuk UI utama.
- Font Arab digunakan khusus untuk teks Arab seperti nama fan/mapel Arab, label raport Arab, dan teks Arab lain.
- Jangan menulis `font-['Liberation_Serif']` berulang di Blade.
- Daftarkan font di `tailwind.config.js`.

---

## 2.2 Typography Scale

| Jenis Teks | Ukuran | Tailwind Class | Penggunaan |
|---|---:|---|---|
| Display Title | 36px / Bold | `text-4xl font-bold` | Judul halaman utama |
| Card Title | 20px / Bold | `text-xl font-bold` | Judul card / judul tabel |
| Section Title | 20px / Bold | `text-xl font-bold` | Header instansi di sidebar/topbar |
| Body Regular | 16px / Normal | `text-base font-normal` | Deskripsi, isi tabel |
| Button / Link | 14px / Medium | `text-sm font-medium` | Sidebar, tombol, header tabel |
| Caps Info | 12px / Semibold | `text-xs font-semibold uppercase` | Label info statis |
| Badge / Counter | 12px / Semibold | `text-xs font-semibold` | Badge status dan counter |

---

# 3. Layout Master

## 3.1 Screen Base

Desain Figma menggunakan basis desktop:

```text
1280px x 1024px
```

Namun implementasi Laravel harus tetap responsif.

## 3.2 Main Layout

Layout aplikasi setelah login terdiri dari:

- sidebar kiri
- topbar atas
- main content
- breadcrumb optional
- flash message area

## 3.3 Sidebar

Desktop:

```text
w-72
fixed
left-0
top-0
h-screen
border-r
border-stone-300
```

Tailwind base:

```text
fixed left-0 top-0 z-40 hidden h-screen w-72 border-r border-stone-300 bg-teal-950 lg:block
```

## 3.4 Topbar

Desktop:

```text
h-16
fixed
top-0
left-72
right-0
border-b
border-stone-300
bg-white
```

Tailwind base:

```text
fixed left-72 right-0 top-0 z-30 hidden h-16 border-b border-stone-300 bg-white lg:flex
```

## 3.5 Main Content

Desktop:

```text
lg:pl-72
pt-20
pr-6
pb-6
```

Tailwind base:

```text
min-h-screen bg-slate-50 px-4 pb-6 pt-20 lg:pl-72 lg:pr-6
```

Catatan:

- Hindari terlalu banyak absolute positioning.
- Gunakan `lg:pl-72` agar layout tetap fleksibel.
- Mobile sidebar bisa dibuat fase lanjutan.
- Jangan buat layout per role. Layout tetap satu, isi menu yang berubah.

---

# 4. Component Standard

## 4.1 Cards

Semua card informasi menggunakan base class:

```text
rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300
```

Alternatif card soft:

```text
rounded-lg bg-slate-50 p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300
```

Penggunaan:

- card statistik
- card form
- card tabel
- card preview raport
- card monitoring

---

## 4.2 Buttons

### Primary Button

```text
inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900
```

Penggunaan:

- tambah data
- simpan data
- finalisasi raport
- generate PDF
- proses kenaikan kelas

### Secondary Button

```text
inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100
```

Penggunaan:

- kembali
- kelola data
- filter
- lihat detail

### Danger Button

```text
inline-flex items-center justify-center rounded-sm bg-red-200 px-4 py-3 text-sm font-medium text-red-950 transition hover:bg-red-300
```

Penggunaan:

- hapus
- blokir akun
- batalkan proses

---

## 4.3 Forms

Input base:

```text
w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10
```

Label base:

```text
mb-2 block text-sm font-medium text-neutral-700
```

Error text:

```text
mt-1 text-sm text-red-700
```

Form container:

```text
space-y-5
```

Textarea base:

```text
w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10
```

Select base:

```text
w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10
```

---

## 4.4 Data Table

### Table Wrapper

```text
overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300
```

### Header

```text
border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700
```

### Row

```text
border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900
```

### Empty State

```text
px-6 py-10 text-center text-sm text-neutral-500
```

Catatan:

- Tabel data besar wajib memakai pagination.
- Halaman tabel santri, guru, nilai, absensi, dan raport wajib punya search/filter.
- Hindari mengambil semua data dengan `Model::all()`.

---

## 4.5 Badge

### Success

```text
inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950
```

### Warning

```text
inline-flex rounded-full bg-orange-300 px-3 py-1 text-xs font-semibold text-orange-950
```

### Danger

```text
inline-flex rounded-full bg-red-200 px-3 py-1 text-xs font-semibold text-red-950
```

### Muted

```text
inline-flex rounded-full bg-zinc-200 px-3 py-1 text-xs font-semibold text-neutral-700
```

---

## 4.6 Progress Indicator

Track:

```text
h-2 w-24 rounded-xl bg-zinc-200
```

Progress 100%:

```text
h-2 rounded-xl bg-teal-950
```

Progress medium:

```text
h-2 rounded-xl bg-orange-300
```

Progress low:

```text
h-2 rounded-xl bg-red-200
```

---

# 5. Sidebar Navigation by Role

Sidebar harus dinamis berdasarkan role user.

Jangan membuat 5 file sidebar berbeda.

Gunakan satu file:

```text
resources/views/partials/sidebar.blade.php
```

Isi menu berasal dari:

```text
config/navigation.php
```

Internal role names:

```text
super_admin
kepala_sekolah
wali_kelas
guru_fan
wali_santri
```

Label UI:

```text
Super Admin
Kepala Sekolah
Wali Kelas
Guru Fan/Mapel
Wali Santri
```

Catatan penting:

- Jangan gunakan `guru_mapel` sebagai nama role internal.
- Gunakan `guru_fan`.
- Menu yang tidak boleh diakses role tertentu jangan hanya disembunyikan di UI.
- Route tetap wajib dibatasi lewat middleware, policy, gate, atau permission.
- Sidebar hanya untuk navigasi, bukan sistem keamanan utama.

---

## 5.1 Sidebar Menu per Role

### Super Admin

Menu utama:

- Dashboard
- Data User
- Data Guru
- Data Santri
- Data Wali Santri
- Jenjang & Kelas
- Fan/Mapel
- Tahun Ajaran
- Semester
- Wali Kelas
- Guru Fan
- Jadwal Pelajaran
- Kenaikan/Penempatan
- Monitoring
- Export Data

### Kepala Sekolah

Menu utama:

- Dashboard
- Data Kelas
- Data Guru
- Data Santri
- Jadwal Pelajaran
- Monitoring Jurnal
- Monitoring Nilai
- Monitoring Absensi
- Preview Raport
- Laporan Finalisasi
- Export Laporan

### Wali Kelas

Menu utama:

- Dashboard
- Data Santri Kelas
- Jadwal Kelas
- Jurnal Guru
- Nilai Sikap
- Absensi
- Raport Santri
- Export Rekap

### Guru Fan

Menu utama:

- Dashboard
- Jadwal Mengajar
- Kelas & Fan
- Daftar Santri
- Jurnal Guru
- Input Nilai
- Rekap Nilai
- Export Nilai

### Wali Santri

Menu utama:

- Dashboard
- Data Santri
- Absensi Santri
- Raport Santri
- Download Raport
- Profil

---

# 6. Arabic Text Rules

Sistem mendukung teks Arab untuk kebutuhan raport atau nama fan/mapel Arab.

## 6.1 Database

Gunakan kolom khusus:

```text
arabic_name
arabic_label
arabic_description
```

Contoh:

```text
subjects.name
subjects.arabic_name
```

## 6.2 UI Rendering

Teks Arab harus menggunakan:

```text
font-arabic
text-right
leading-loose
```

Jika teks Arab berdiri sendiri, gunakan:

```html
<span lang="ar" dir="rtl" class="font-arabic text-right leading-loose">
    النص العربي
</span>
```

Jika satu halaman full Arab, wrapper boleh menggunakan:

```html
<div lang="ar" dir="rtl" class="font-arabic">
    ...
</div>
```

## 6.3 Mixed Latin and Arabic

Untuk halaman campuran Latin dan Arab, jangan set seluruh halaman `dir="rtl"`.

Gunakan `dir="rtl"` hanya pada teks Arab.

Contoh:

```html
<div>
    <p class="text-sm text-neutral-700">Nama Fan</p>
    <p lang="ar" dir="rtl" class="font-arabic text-lg leading-loose text-zinc-900">
        الفقه
    </p>
</div>
```

## 6.4 Blade Component Recommendation

Buat komponen:

```text
resources/views/components/arabic-text.blade.php
```

Isi komponen:

```blade
<span {{ $attributes->merge([
    'lang' => 'ar',
    'dir' => 'rtl',
    'class' => 'font-arabic leading-loose',
]) }}>
    {{ $slot }}
</span>
```

Contoh pemakaian:

```blade
<x-arabic-text class="block text-right text-xl">
    {{ $subject->arabic_name }}
</x-arabic-text>
```

---

# 7. Tailwind Configuration Recommendation

Tambahkan font dan warna brand ke `tailwind.config.js`.

Contoh:

```js
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                serif: ['"Liberation Serif"', 'Georgia', '"Times New Roman"', 'serif'],
                arabic: ['"Noto Naskh Arabic"', '"Amiri"', 'serif'],
            },
            colors: {
                brand: {
                    primary: '#042f2e',
                    active: '#064e3b',
                    accent: '#fdba74',
                },
            },
        },
    },
    plugins: [],
};
```

Catatan:

- Jangan share file font jika font tersebut bukan font bebas lisensi.
- Kalau font Liberation Serif tersedia di sistem, cukup gunakan fallback.
- Untuk font Arab, gunakan font yang aman digunakan dan tersedia di environment deployment.
- Jika memakai custom font, pastikan lisensi aman untuk project client.

---

# 8. PDF Report Notes

Tampilan Arab di browser dan PDF bisa berbeda.

Untuk UI browser:

- Tailwind + font Arab aman digunakan.
- Gunakan `lang="ar"` dan `dir="rtl"`.

Untuk PDF:

- Teks Arab harus diuji sejak awal.
- DomPDF bisa cukup untuk raport Latin.
- Jika raport Arab tidak rapi dengan DomPDF, pertimbangkan renderer berbasis browser seperti Browsershot/Chromium pada fase lanjutan.

Untuk MVP:

- simpan data Arab dengan benar
- tampilkan teks Arab di UI
- siapkan struktur database
- raport Latin menjadi prioritas
- raport Arab bisa masuk fase lanjutan jika layout PDF kompleks

---

# 9. Implementation Rules

- Gunakan Tailwind CSS.
- Hindari hardcode warna acak.
- Gunakan komponen Blade reusable.
- Jangan tulis class font custom berulang.
- Gunakan `tailwind.config.js` untuk font family dan warna.
- Gunakan satu sidebar dinamis berdasarkan role.
- Jaga konsistensi nama role internal: `guru_fan`.
- Jangan gunakan `guru_mapel` sebagai role internal.
- Data besar wajib menggunakan pagination.
- Tabel wajib punya filter/search.
- UI harus tetap nyaman untuk data ribuan santri.
- Route authorization tidak boleh hanya mengandalkan menu sidebar.
- Menu sidebar hanya tampilan, bukan security layer.