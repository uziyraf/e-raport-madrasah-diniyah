<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Level;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Levels
        $levels = [
            ['name' => 'Ibtida\'iyah', 'description' => 'Tingkat Dasar', 'sort_order' => 1],
            ['name' => 'Tsanawiyah', 'description' => 'Tingkat Menengah', 'sort_order' => 2],
            ['name' => 'Aliyah', 'description' => 'Tingkat Atas', 'sort_order' => 3],
        ];

        foreach ($levels as $data) {
            Level::create($data);
        }

        // School Classes
        $levelIds = Level::pluck('id', 'name');
        $classNames = ['1', '2', '3', '4', '5', '6'];
        foreach ($levelIds as $levelName => $levelId) {
            foreach ($classNames as $i => $name) {
                if ($levelName === 'Ibtida\'iyah' && $i > 5) break;
                if ($levelName === 'Tsanawiyah' && $i > 2) break;
                if ($levelName === 'Aliyah' && $i > 2) break;
                SchoolClass::create([
                    'level_id' => $levelId,
                    'name' => "$levelName $name",
                    'code' => strtoupper(substr($levelName, 0, 3)) . $name,
                    'sort_order' => $i + 1,
                ]);
            }
        }

        // Academic Year
        $academicYear = AcademicYear::create([
            'name' => '2025/2026',
            'start_date' => '2025-07-01',
            'end_date' => '2026-06-30',
            'is_active' => true,
        ]);

        // Semesters
        Semester::create([
            'academic_year_id' => $academicYear->id,
            'name' => 'Ganjil',
            'start_date' => '2025-07-01',
            'end_date' => '2025-12-31',
            'is_active' => true,
        ]);
        Semester::create([
            'academic_year_id' => $academicYear->id,
            'name' => 'Genap',
            'start_date' => '2026-01-01',
            'end_date' => '2026-06-30',
            'is_active' => false,
        ]);

        // Subjects
        $subjects = [
            ['name' => 'Al-Qur\'an', 'arabic_name' => 'القرآن', 'code' => 'QUR', 'sort_order' => 1],
            ['name' => 'Hadits', 'arabic_name' => 'الحديث', 'code' => 'HDS', 'sort_order' => 2],
            ['name' => 'Fiqih', 'arabic_name' => 'الفقه', 'code' => 'FQH', 'sort_order' => 3],
            ['name' => 'Akidah Akhlak', 'arabic_name' => 'العقيدة والأخلاق', 'code' => 'AAK', 'sort_order' => 4],
            ['name' => 'Bahasa Arab', 'arabic_name' => 'اللغة العربية', 'code' => 'BAR', 'sort_order' => 5],
            ['name' => 'Sejarah Kebudayaan Islam', 'arabic_name' => 'التاريخ الإسلامي', 'code' => 'SKI', 'sort_order' => 6],
        ];

        foreach ($subjects as $data) {
            Subject::create($data);
        }

        // Teachers (link to existing users with teacher roles)
        $teachers = [
            ['name' => 'Ahmad Fauzi', 'gender' => 'Laki-laki', 'phone' => '081234567890', 'user_id' => User::where('email', 'wali.kelas@simadu.test')->first()?->id],
            ['name' => 'Siti Nurhaliza', 'gender' => 'Perempuan', 'phone' => '081234567891', 'user_id' => User::where('email', 'guru.fan@simadu.test')->first()?->id],
            ['name' => 'Muhammad Rizki', 'gender' => 'Laki-laki', 'phone' => '081234567892'],
            ['name' => 'Fatimah Azzahra', 'gender' => 'Perempuan', 'phone' => '081234567893'],
        ];

        foreach ($teachers as $data) {
            Teacher::create($data);
        }

        // Students
        $genders = ['Laki-laki', 'Perempuan'];
        $firstNames = ['Ahmad', 'Muhammad', 'Abdullah', 'Siti', 'Aisyah', 'Fatimah', 'Khadijah', 'Maryam', 'Zainab', 'Hafsah'];
        $lastNames = ['Hidayat', 'Nurrahman', 'Fauzi', 'Pratama', 'Wijaya', 'Ramadhan', 'Hakim', 'Syafii', 'Rahman', 'Anwar'];

        for ($i = 1; $i <= 20; $i++) {
            $gender = $genders[$i % 2];
            Student::create([
                'nis' => '2025' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'name' => $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)],
                'gender' => $gender,
                'birth_place' => 'Jakarta',
                'birth_date' => now()->subYears(10 + ($i % 8))->subDays($i * 10),
                'address' => "Jl. Contoh No. $i",
                'guardian_phone' => '0812345600' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
            ]);
        }
    }
}
