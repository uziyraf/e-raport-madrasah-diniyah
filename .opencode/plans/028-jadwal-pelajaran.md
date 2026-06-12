# Plan Fitur Jadwal Pelajaran

## 1. Tujuan Fitur

Fitur Jadwal Pelajaran digunakan untuk mengatur dan menampilkan jadwal mata pelajaran/fan pada sistem E-Raport Madrasah Diniyah Pondok Pesantren.

Fitur ini memiliki dua menu utama:

1. **Kelola Jadwal Pelajaran**

   * Khusus untuk Super Admin.
   * Digunakan untuk membuat, mengubah, menghapus, dan melihat semua jadwal pelajaran.

2. **Lihat Jadwal Pelajaran**

   * Digunakan oleh Kepala Sekolah, Wali Kelas, dan Guru Fan.
   * Bersifat read-only.
   * Data jadwal yang tampil disesuaikan dengan hak akses masing-masing role.

---

## 2. Role dan Hak Akses

### 2.1 Super Admin

Super Admin memiliki akses penuh terhadap jadwal pelajaran.

Hak akses:

* Melihat semua jadwal pelajaran.
* Menambah jadwal pelajaran.
* Mengedit jadwal pelajaran.
* Menghapus jadwal pelajaran.
* Melakukan filter jadwal berdasarkan tahun ajaran, semester, kelas, guru, fan/mapel, dan hari.

Menu:

* Kelola Jadwal Pelajaran

---

### 2.2 Kepala Sekolah

Kepala Sekolah hanya dapat melihat seluruh jadwal pelajaran.

Hak akses:

* Melihat semua jadwal pelajaran.
* Melakukan filter jadwal.
* Tidak dapat menambah, mengedit, atau menghapus jadwal.

Menu:

* Lihat Jadwal Pelajaran

---

### 2.3 Wali Kelas

Wali Kelas hanya dapat melihat jadwal pelajaran dari kelas yang diampu.

Hak akses:

* Melihat jadwal kelasnya sendiri.
* Tidak dapat melihat jadwal kelas lain.
* Tidak dapat menambah, mengedit, atau menghapus jadwal.

Menu:

* Lihat Jadwal Pelajaran

---

### 2.4 Guru Fan

Guru Fan hanya dapat melihat jadwal mengajar sesuai guru tersebut.

Hak akses:

* Melihat jadwal mengajar berdasarkan fan/mapel dan kelas yang diajar.
* Tidak dapat melihat jadwal guru lain.
* Tidak dapat menambah, mengedit, atau menghapus jadwal.

Menu:

* Lihat Jadwal Pelajaran

---

### 2.5 Wali Santri

Wali Santri belum diberikan akses jadwal pelajaran pada tahap ini.

---

## 3. Scope Fitur

### Masuk Scope

Fitur yang dibuat pada tahap ini:

* Migration tabel jadwal pelajaran.
* Model JadwalPelajaran.
* Relasi JadwalPelajaran dengan tahun ajaran, semester, kelas, mapel/fan, guru, dan user pembuat.
* CRUD jadwal untuk Super Admin.
* Halaman read-only jadwal untuk Kepala Sekolah, Wali Kelas, dan Guru Fan.
* Filter jadwal.
* Validasi bentrok jadwal.
* Menu sidebar sesuai role.

### Tidak Masuk Scope

Fitur yang tidak dibuat pada tahap ini:

* Tampilan kalender mingguan.
* Drag and drop jadwal.
* Jadwal untuk Wali Santri.
* Import jadwal dari Excel.
* Export jadwal ke Excel/PDF.
* Integrasi langsung dengan jurnal guru.
* Notifikasi jadwal.

---

## 4. Struktur Database

### 4.1 Tabel `jadwal_pelajarans`

Buat tabel `jadwal_pelajarans` dengan field berikut:

* `id`
* `tahun_ajaran_id`
* `semester_id`
* `kelas_id`
* `mapel_id`
* `guru_id`
* `hari`
* `jam_mulai`
* `jam_selesai`
* `keterangan`
* `created_by`
* `created_at`
* `updated_at`

Catatan:

Jika struktur `guru_mapels` atau `guru_fans` sudah stabil dan sudah menyimpan relasi guru, mapel, kelas, tahun ajaran, dan semester, maka jadwal boleh menggunakan `guru_mapel_id`.

Namun untuk tahap awal agar development lebih mudah dan jelas, gunakan field langsung:

* `tahun_ajaran_id`
* `semester_id`
* `kelas_id`
* `mapel_id`
* `guru_id`

---

## 5. Relasi Model

### Model `JadwalPelajaran`

Relasi yang perlu dibuat:

* `tahunAjaran()`

  * belongsTo TahunAjaran

* `semester()`

  * belongsTo Semester

* `kelas()`

  * belongsTo Kelas

* `mapel()`

  * belongsTo Mapel

* `guru()`

  * belongsTo Guru

* `creator()`

  * belongsTo User melalui `created_by`

---

## 6. Menu Sidebar

### Super Admin

Tambahkan menu:

* Akademik

  * Kelola Jadwal Pelajaran

### Kepala Sekolah

Tambahkan menu:

* Akademik

  * Lihat Jadwal Pelajaran

### Wali Kelas

Tambahkan menu:

* Akademik

  * Lihat Jadwal Pelajaran

### Guru Fan

Tambahkan menu:

* Akademik

  * Lihat Jadwal Pelajaran

---

## 7. Route

### 7.1 Route Super Admin

Route untuk Super Admin:

* `GET /admin/jadwal-pelajaran`

  * Menampilkan daftar jadwal.

* `GET /admin/jadwal-pelajaran/create`

  * Menampilkan form tambah jadwal.

* `POST /admin/jadwal-pelajaran`

  * Menyimpan jadwal baru.

* `GET /admin/jadwal-pelajaran/{jadwal}/edit`

  * Menampilkan form edit jadwal.

* `PUT /admin/jadwal-pelajaran/{jadwal}`

  * Menyimpan perubahan jadwal.

* `DELETE /admin/jadwal-pelajaran/{jadwal}`

  * Menghapus jadwal.

Nama route disarankan:

* `admin.jadwal-pelajaran.index`
* `admin.jadwal-pelajaran.create`
* `admin.jadwal-pelajaran.store`
* `admin.jadwal-pelajaran.edit`
* `admin.jadwal-pelajaran.update`
* `admin.jadwal-pelajaran.destroy`

---

### 7.2 Route Lihat Jadwal

Route untuk Kepala Sekolah, Wali Kelas, dan Guru Fan:

* `GET /lihat-jadwal-pelajaran`

Nama route disarankan:

* `lihat-jadwal-pelajaran.index`

---

## 8. Controller

### 8.1 `Admin/JadwalPelajaranController`

Controller ini khusus Super Admin.

Method:

#### `index()`

Fungsi:

* Menampilkan semua jadwal pelajaran.
* Menyediakan filter:

  * Tahun ajaran
  * Semester
  * Kelas
  * Guru
  * Fan/Mapel
  * Hari

Data tabel:

* Hari
* Jam
* Kelas
* Fan/Mapel
* Guru
* Tahun Ajaran
* Semester
* Keterangan
* Aksi

Aksi:

* Tambah
* Edit
* Hapus

---

#### `create()`

Fungsi:

* Menampilkan form tambah jadwal.

Data dropdown:

* Tahun ajaran
* Semester
* Kelas
* Fan/Mapel
* Guru
* Hari

---

#### `store()`

Fungsi:

* Menyimpan jadwal baru.

Validasi:

* `tahun_ajaran_id` wajib.
* `semester_id` wajib.
* `kelas_id` wajib.
* `mapel_id` wajib.
* `guru_id` wajib.
* `hari` wajib.
* `jam_mulai` wajib.
* `jam_selesai` wajib.
* `jam_selesai` harus lebih besar dari `jam_mulai`.
* Guru tidak boleh bentrok jadwal di hari dan jam yang sama.
* Kelas tidak boleh bentrok jadwal di hari dan jam yang sama.

---

#### `edit()`

Fungsi:

* Menampilkan form edit jadwal.

---

#### `update()`

Fungsi:

* Menyimpan perubahan jadwal.

Validasi:

* Sama seperti `store()`.
* Saat validasi bentrok, abaikan jadwal yang sedang diedit.

---

#### `destroy()`

Fungsi:

* Menghapus jadwal pelajaran.
* Hanya Super Admin yang bisa menghapus.

---

### 8.2 `LihatJadwalPelajaranController`

Controller ini untuk halaman read-only.

Method:

#### `index()`

Fungsi:

* Menampilkan jadwal berdasarkan role user login.

Aturan data:

1. Jika role adalah Kepala Sekolah:

   * Tampilkan semua jadwal.

2. Jika role adalah Wali Kelas:

   * Cari data guru dari user login.
   * Cari kelas yang diampu pada tabel wali kelas.
   * Tampilkan jadwal berdasarkan kelas tersebut.

3. Jika role adalah Guru Fan:

   * Cari data guru dari user login.
   * Tampilkan jadwal berdasarkan `guru_id`.

4. Jika role adalah Super Admin:

   * Boleh tampilkan semua jadwal juga.

5. Jika role adalah Wali Santri:

   * Tidak perlu akses pada tahap ini.
   * Bisa redirect atau tampilkan 403.

---

## 9. Validasi Bentrok Jadwal

### 9.1 Bentrok Guru

Guru tidak boleh memiliki dua jadwal pada hari dan waktu yang bertabrakan.

Logika bentrok:

Jadwal dianggap bentrok jika:

* Hari sama.
* Guru sama.
* Jam mulai baru lebih kecil dari jam selesai jadwal lama.
* Jam selesai baru lebih besar dari jam mulai jadwal lama.

Rumus:

`jam_mulai_baru < jam_selesai_lama`
dan
`jam_selesai_baru > jam_mulai_lama`

---

### 9.2 Bentrok Kelas

Kelas tidak boleh memiliki dua jadwal pada hari dan waktu yang bertabrakan.

Logika bentrok:

* Hari sama.
* Kelas sama.
* Jam mulai dan jam selesai bertabrakan.

---

## 10. View Blade

### 10.1 View Super Admin

Folder:

`resources/views/admin/jadwal-pelajaran/`

File yang dibuat:

1. `index.blade.php`

   * List jadwal.
   * Filter jadwal.
   * Tombol tambah.
   * Tombol edit.
   * Tombol hapus.

2. `create.blade.php`

   * Form tambah jadwal.

3. `edit.blade.php`

   * Form edit jadwal.

---

### 10.2 View Lihat Jadwal

Folder:

`resources/views/jadwal-pelajaran/`

File:

1. `index.blade.php`

   * List jadwal read-only.
   * Filter jadwal sesuai kebutuhan.
   * Tidak ada tombol tambah/edit/hapus.

---

## 11. UI dan UX

Gunakan layout dashboard yang sudah ada.

Tabel jadwal menampilkan:

* Hari
* Jam
* Kelas
* Fan/Mapel
* Guru
* Tahun Ajaran
* Semester
* Keterangan
* Aksi khusus Super Admin

Gunakan style yang konsisten dengan fitur sebelumnya.

Tambahkan badge sederhana untuk:

* Hari
* Semester

Gunakan pesan validasi yang jelas, misalnya:

* “Jam selesai harus lebih besar dari jam mulai.”
* “Guru sudah memiliki jadwal pada hari dan jam tersebut.”
* “Kelas sudah memiliki jadwal pada hari dan jam tersebut.”

---

## 12. Testing Manual

### 12.1 Testing Super Admin

Checklist:

* Super Admin bisa membuka halaman Kelola Jadwal Pelajaran.
* Super Admin bisa menambah jadwal.
* Super Admin bisa mengedit jadwal.
* Super Admin bisa menghapus jadwal.
* Filter jadwal berjalan.
* Jadwal guru yang bentrok ditolak.
* Jadwal kelas yang bentrok ditolak.
* Jam selesai yang lebih kecil dari jam mulai ditolak.

---

### 12.2 Testing Kepala Sekolah

Checklist:

* Kepala Sekolah bisa membuka halaman Lihat Jadwal Pelajaran.
* Kepala Sekolah bisa melihat semua jadwal.
* Kepala Sekolah tidak melihat tombol tambah.
* Kepala Sekolah tidak melihat tombol edit.
* Kepala Sekolah tidak melihat tombol hapus.

---

### 12.3 Testing Wali Kelas

Checklist:

* Wali Kelas bisa membuka halaman Lihat Jadwal Pelajaran.
* Wali Kelas hanya melihat jadwal kelas yang diampu.
* Wali Kelas tidak bisa melihat jadwal kelas lain.
* Wali Kelas tidak bisa tambah/edit/hapus jadwal.

---

### 12.4 Testing Guru Fan

Checklist:

* Guru Fan bisa membuka halaman Lihat Jadwal Pelajaran.
* Guru Fan hanya melihat jadwal mengajarnya.
* Guru Fan tidak bisa melihat jadwal guru lain.
* Guru Fan tidak bisa tambah/edit/hapus jadwal.

---

### 12.5 Testing Wali Santri

Checklist:

* Wali Santri tidak memiliki menu jadwal pelajaran.
* Jika mengakses URL jadwal secara langsung, akses ditolak atau diarahkan sesuai aturan sistem.

---

## 13. Commit Plan

Commit dilakukan bertahap.

### Commit 1

`Add jadwal pelajaran migration and model`

Isi:

* Migration jadwal_pelajarans.
* Model JadwalPelajaran.
* Relasi model.

### Commit 2

`Add super admin jadwal pelajaran CRUD`

Isi:

* Route Super Admin.
* Controller CRUD.
* View index/create/edit.
* Validasi dasar.

### Commit 3

`Add jadwal pelajaran conflict validation`

Isi:

* Validasi bentrok guru.
* Validasi bentrok kelas.
* Validasi jam selesai.

### Commit 4

`Add read-only jadwal pelajaran by role`

Isi:

* Controller LihatJadwalPelajaran.
* View read-only.
* Filter akses Kepala Sekolah, Wali Kelas, Guru Fan.

### Commit 5

`Add jadwal pelajaran sidebar menu`

Isi:

* Menu Kelola Jadwal untuk Super Admin.
* Menu Lihat Jadwal untuk Kepala Sekolah, Wali Kelas, dan Guru Fan.

---

## 14. Catatan Penting

* Jangan mengubah fitur kenaikan/penempatan santri yang sudah selesai dan berjalan normal.
* Jangan langsung membuat fitur jurnal sebelum jadwal pelajaran stabil.
* Jangan membuat akses edit jadwal untuk role selain Super Admin.
* Jangan membuat tampilan kalender dulu. Gunakan tabel jadwal agar lebih mudah dikembangkan dan diuji.
* Pastikan validasi bentrok jadwal berjalan sebelum fitur ini dianggap selesai.
