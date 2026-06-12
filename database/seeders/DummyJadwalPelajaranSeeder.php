<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\HomeroomAssignment;
use App\Models\JadwalPelajaran;
use App\Models\Level;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentClassEnrollment;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DummyJadwalPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $roleNames = ['super_admin', 'kepala_sekolah', 'wali_kelas', 'guru_fan', 'wali_santri'];
        foreach ($roleNames as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // ========== 1. LEVELS ==========
        $levelIbtidaiyah = Level::updateOrCreate(
            ['name' => 'Ibtida\'iyah'],
            ['description' => 'Tingkat Dasar', 'sort_order' => 1, 'status' => 'active'],
        );
        $levelTsanawiyah = Level::updateOrCreate(
            ['name' => 'Tsanawiyah'],
            ['description' => 'Tingkat Menengah', 'sort_order' => 2, 'status' => 'active'],
        );

        // ========== 2. SCHOOL CLASSES ==========
        $kelasIB1 = SchoolClass::updateOrCreate(
            ['code' => 'IBT1'],
            [
                'level_id' => $levelIbtidaiyah->id,
                'grade_level' => 1,
                'name' => 'Ibtida\'iyah 1',
                'sort_order' => 1,
                'status' => 'active',
            ],
        );
        $kelasIB2 = SchoolClass::updateOrCreate(
            ['code' => 'IBT2'],
            [
                'level_id' => $levelIbtidaiyah->id,
                'grade_level' => 1,
                'name' => 'Ibtida\'iyah 2',
                'sort_order' => 2,
                'status' => 'active',
            ],
        );
        $kelasTS1 = SchoolClass::updateOrCreate(
            ['code' => 'TSN1'],
            [
                'level_id' => $levelTsanawiyah->id,
                'grade_level' => 1,
                'name' => 'Tsanawiyah 1',
                'sort_order' => 3,
                'status' => 'active',
            ],
        );
        $kelasTS2 = SchoolClass::updateOrCreate(
            ['code' => 'TSN2'],
            [
                'level_id' => $levelTsanawiyah->id,
                'grade_level' => 1,
                'name' => 'Tsanawiyah 2',
                'sort_order' => 4,
                'status' => 'active',
            ],
        );

        // ========== 3. ACADEMIC YEAR & SEMESTER ==========
        $tahunAjaran = AcademicYear::updateOrCreate(
            ['name' => '2025/2026'],
            [
                'start_date' => '2025-07-01',
                'end_date' => '2026-06-30',
                'is_active' => true,
            ],
        );

        $semester = Semester::updateOrCreate(
            ['academic_year_id' => $tahunAjaran->id, 'name' => 'Ganjil'],
            [
                'start_date' => '2025-07-01',
                'end_date' => '2025-12-31',
                'is_active' => true,
            ],
        );

        // ========== 4. SUBJECTS ==========
        $subjectFiqih = Subject::updateOrCreate(
            ['name' => 'Fiqih'],
            ['arabic_name' => 'الفقه', 'code' => 'FQH', 'sort_order' => 1, 'status' => 'active'],
        );
        $subjectNahwu = Subject::updateOrCreate(
            ['name' => 'Nahwu'],
            ['arabic_name' => 'النحو', 'code' => 'NHW', 'sort_order' => 2, 'status' => 'active'],
        );
        $subjectShorof = Subject::updateOrCreate(
            ['name' => 'Shorof'],
            ['arabic_name' => 'الصرف', 'code' => 'SRF', 'sort_order' => 3, 'status' => 'active'],
        );
        $subjectTajwid = Subject::updateOrCreate(
            ['name' => 'Tajwid'],
            ['arabic_name' => 'التجويد', 'code' => 'TJW', 'sort_order' => 4, 'status' => 'active'],
        );
        $subjectHadits = Subject::updateOrCreate(
            ['name' => 'Hadits'],
            ['arabic_name' => 'الحديث', 'code' => 'HDS', 'sort_order' => 5, 'status' => 'active'],
        );

        // ========== 5. TEACHERS ==========
        $guruWaliIB1 = Teacher::updateOrCreate(
            ['name' => 'Ustadz Ahmad Wali IB1'],
            [
                'teacher_code' => 'GR-IB1',
                'gender' => 'male',
                'status' => 'active',
            ],
        );
        $guruWaliTS1 = Teacher::updateOrCreate(
            ['name' => 'Ustadz Hasan Wali TS1'],
            [
                'teacher_code' => 'GR-TS1',
                'gender' => 'male',
                'status' => 'active',
            ],
        );
        $guruFiqih = Teacher::updateOrCreate(
            ['name' => 'Ustadz Fiqih'],
            [
                'teacher_code' => 'GR-FQH',
                'gender' => 'male',
                'status' => 'active',
            ],
        );
        $guruNahwu = Teacher::updateOrCreate(
            ['name' => 'Ustadz Nahwu'],
            [
                'teacher_code' => 'GR-NHW',
                'gender' => 'male',
                'status' => 'active',
            ],
        );
        $guruTajwid = Teacher::updateOrCreate(
            ['name' => 'Ustadzah Tajwid'],
            [
                'teacher_code' => 'GR-TJW',
                'gender' => 'female',
                'status' => 'active',
            ],
        );

        // ========== 6. USERS ==========
        $superAdmin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
        );
        $superAdmin->syncRoles(['super_admin']);

        User::updateOrCreate(
            ['username' => 'kepala'],
            [
                'name' => 'Kepala Sekolah',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
        )->syncRoles(['kepala_sekolah']);

        $userWaliIB1 = User::updateOrCreate(
            ['username' => 'wali.ib1'],
            [
                'name' => 'Wali Ibtida\'iyah 1',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
        );
        $userWaliIB1->syncRoles(['wali_kelas']);
        $guruWaliIB1->update(['user_id' => $userWaliIB1->id]);

        $userWaliTS1 = User::updateOrCreate(
            ['username' => 'wali.ts1'],
            [
                'name' => 'Wali Tsanawiyah 1',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
        );
        $userWaliTS1->syncRoles(['wali_kelas']);
        $guruWaliTS1->update(['user_id' => $userWaliTS1->id]);

        $userGuruFiqih = User::updateOrCreate(
            ['username' => 'guru.fiqih'],
            [
                'name' => 'Guru Fiqih',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
        );
        $userGuruFiqih->syncRoles(['guru_fan']);
        $guruFiqih->update(['user_id' => $userGuruFiqih->id]);

        $userGuruNahwu = User::updateOrCreate(
            ['username' => 'guru.nahwu'],
            [
                'name' => 'Guru Nahwu',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
        );
        $userGuruNahwu->syncRoles(['guru_fan']);
        $guruNahwu->update(['user_id' => $userGuruNahwu->id]);

        $userGuruTajwid = User::updateOrCreate(
            ['username' => 'guru.tajwid'],
            [
                'name' => 'Guru Tajwid',
                'password' => Hash::make('password'),
                'status' => 'active',
            ],
        );
        $userGuruTajwid->syncRoles(['guru_fan']);
        $guruTajwid->update(['user_id' => $userGuruTajwid->id]);

        // ========== 7. WALI KELAS (HOMEROOM ASSIGNMENTS) ==========
        HomeroomAssignment::updateOrCreate(
            [
                'teacher_id' => $guruWaliIB1->id,
                'school_class_id' => $kelasIB1->id,
                'academic_year_id' => $tahunAjaran->id,
                'semester_id' => $semester->id,
            ],
        );

        HomeroomAssignment::updateOrCreate(
            [
                'teacher_id' => $guruWaliTS1->id,
                'school_class_id' => $kelasTS1->id,
                'academic_year_id' => $tahunAjaran->id,
                'semester_id' => $semester->id,
            ],
        );

        // ========== 8. STUDENTS + ENROLLMENTS ==========
        $students = [
            ['nis' => '250001', 'name' => 'Ahmad Zainuddin', 'gender' => 'male', 'kelas' => $kelasIB1],
            ['nis' => '250002', 'name' => 'Muhammad Farhan', 'gender' => 'male', 'kelas' => $kelasIB1],
            ['nis' => '250003', 'name' => 'Siti Aisyah', 'gender' => 'female', 'kelas' => $kelasIB1],
            ['nis' => '250004', 'name' => 'Nur Halimah', 'gender' => 'female', 'kelas' => $kelasIB2],
            ['nis' => '250005', 'name' => 'Abdul Karim', 'gender' => 'male', 'kelas' => $kelasIB2],
            ['nis' => '250006', 'name' => 'Fatimah Zahra', 'gender' => 'female', 'kelas' => $kelasIB2],
            ['nis' => '250007', 'name' => 'Hasan Basri', 'gender' => 'male', 'kelas' => $kelasTS1],
            ['nis' => '250008', 'name' => 'Aulia Rahmah', 'gender' => 'female', 'kelas' => $kelasTS1],
            ['nis' => '250009', 'name' => 'Zaki Mubarak', 'gender' => 'male', 'kelas' => $kelasTS1],
            ['nis' => '250010', 'name' => 'Laila Fitri', 'gender' => 'female', 'kelas' => $kelasTS2],
            ['nis' => '250011', 'name' => 'Rizki Maulana', 'gender' => 'male', 'kelas' => $kelasTS2],
            ['nis' => '250012', 'name' => 'Salma Nabila', 'gender' => 'female', 'kelas' => $kelasTS2],
        ];

        foreach ($students as $data) {
            $student = Student::updateOrCreate(
                ['nis' => $data['nis']],
                [
                    'name' => $data['name'],
                    'gender' => $data['gender'],
                    'status' => 'active',
                ],
            );

            StudentClassEnrollment::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'academic_year_id' => $tahunAjaran->id,
                    'semester_id' => $semester->id,
                ],
                [
                    'school_class_id' => $data['kelas']->id,
                    'enrollment_status' => 'active',
                    'is_active' => true,
                    'created_by' => $superAdmin->id,
                ],
            );
        }

        // ========== 9. JADWAL PELAJARAN ==========
        $jadwalList = [
            [
                'hari' => 'Senin', 'jam_mulai' => '18:00:00', 'jam_selesai' => '18:45:00',
                'kelas' => $kelasIB1, 'mapel' => $subjectFiqih, 'guru' => $guruFiqih,
            ],
            [
                'hari' => 'Senin', 'jam_mulai' => '18:45:00', 'jam_selesai' => '19:30:00',
                'kelas' => $kelasIB1, 'mapel' => $subjectTajwid, 'guru' => $guruTajwid,
            ],
            [
                'hari' => 'Selasa', 'jam_mulai' => '18:00:00', 'jam_selesai' => '18:45:00',
                'kelas' => $kelasIB2, 'mapel' => $subjectNahwu, 'guru' => $guruNahwu,
            ],
            [
                'hari' => 'Selasa', 'jam_mulai' => '18:45:00', 'jam_selesai' => '19:30:00',
                'kelas' => $kelasIB2, 'mapel' => $subjectFiqih, 'guru' => $guruFiqih,
            ],
            [
                'hari' => 'Rabu', 'jam_mulai' => '18:00:00', 'jam_selesai' => '18:45:00',
                'kelas' => $kelasTS1, 'mapel' => $subjectNahwu, 'guru' => $guruNahwu,
            ],
            [
                'hari' => 'Rabu', 'jam_mulai' => '18:45:00', 'jam_selesai' => '19:30:00',
                'kelas' => $kelasTS1, 'mapel' => $subjectHadits, 'guru' => $guruWaliIB1,
            ],
            [
                'hari' => 'Kamis', 'jam_mulai' => '18:00:00', 'jam_selesai' => '18:45:00',
                'kelas' => $kelasTS2, 'mapel' => $subjectShorof, 'guru' => $guruWaliTS1,
            ],
            [
                'hari' => 'Kamis', 'jam_mulai' => '18:45:00', 'jam_selesai' => '19:30:00',
                'kelas' => $kelasTS2, 'mapel' => $subjectTajwid, 'guru' => $guruTajwid,
            ],
        ];

        foreach ($jadwalList as $data) {
            JadwalPelajaran::updateOrCreate(
                [
                    'tahun_ajaran_id' => $tahunAjaran->id,
                    'semester_id' => $semester->id,
                    'kelas_id' => $data['kelas']->id,
                    'mapel_id' => $data['mapel']->id,
                    'guru_id' => $data['guru']->id,
                    'hari' => $data['hari'],
                    'jam_mulai' => $data['jam_mulai'],
                    'jam_selesai' => $data['jam_selesai'],
                ],
                [
                    'keterangan' => null,
                    'created_by' => $superAdmin->id,
                ],
            );
        }

        $this->command->info('========================================');
        $this->command->info('  Dummy Jadwal Pelajaran berhasil dibuat');
        $this->command->info('========================================');
        $this->command->info('Akun testing:');
        $this->command->info('  wali.ib1    / password (Wali Kelas Ibtida\'iyah 1)');
        $this->command->info('  wali.ts1    / password (Wali Kelas Tsanawiyah 1)');
        $this->command->info('  guru.fiqih  / password (Guru Fiqih)');
        $this->command->info('  guru.nahwu  / password (Guru Nahwu)');
        $this->command->info('  guru.tajwid / password (Guru Tajwid)');
        $this->command->info('  admin       / password (Super Admin)');
        $this->command->info('  kepala      / password (Kepala Sekolah)');
        $this->command->info('========================================');
    }
}
