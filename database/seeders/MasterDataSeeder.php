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
        $parallels = ['A', 'B'];
        foreach ($levelIds as $levelName => $levelId) {
            $grades = match ($levelName) {
                'Ibtida\'iyah' => range(1, 6),
                'Tsanawiyah' => range(1, 3),
                'Aliyah' => range(1, 3),
                default => [],
            };
            $sort = 0;
            foreach ($grades as $grade) {
                foreach ($parallels as $parallel) {
                    $sort++;
                    SchoolClass::create([
                        'level_id' => $levelId,
                        'grade_level' => $grade,
                        'parallel_name' => $parallel,
                        'name' => "$levelName $grade $parallel",
                        'code' => strtoupper(substr($levelName, 0, 3)) . $grade . $parallel,
                        'sort_order' => $sort,
                    ]);
                }
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
            ['name' => 'Ahmad Fauzi', 'gender' => 'male', 'phone' => '081234567890', 'email' => 'ahmad.fauzi@simadu.test', 'user_id' => User::where('email', 'wali.kelas@simadu.test')->first()?->id],
            ['name' => 'Siti Nurhaliza', 'gender' => 'female', 'phone' => '081234567891', 'email' => 'siti.nurhaliza@simadu.test', 'user_id' => User::where('email', 'guru.fan@simadu.test')->first()?->id],
            ['name' => 'Muhammad Rizki', 'gender' => 'male', 'phone' => '081234567892', 'birth_place' => 'Jakarta', 'birth_date' => '1990-05-15'],
            ['name' => 'Fatimah Azzahra', 'gender' => 'female', 'phone' => '081234567893', 'birth_place' => 'Bandung', 'birth_date' => '1992-08-20'],
        ];

        foreach ($teachers as $data) {
            Teacher::create($data);
        }

        // Students
        $genders = ['male', 'female'];
        $firstNames = ['Ahmad', 'Muhammad', 'Abdullah', 'Siti', 'Aisyah', 'Fatimah', 'Khadijah', 'Maryam', 'Zainab', 'Hafsah'];
        $lastNames = ['Hidayat', 'Nurrahman', 'Fauzi', 'Pratama', 'Wijaya', 'Ramadhan', 'Hakim', 'Syafii', 'Rahman', 'Anwar'];
        $arabicNames = ['أحمد', 'محمد', 'عبد الله', 'سيتي', 'عائشة', 'فاطمة', 'خديجة', 'مريم', 'زينب', 'حفصة'];

        for ($i = 1; $i <= 20; $i++) {
            $gender = $genders[$i % 2];
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            Student::create([
                'nis' => '2025' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'name' => $firstName . ' ' . $lastName,
                'arabic_name' => $arabicNames[array_rand($arabicNames)] . ' ' . $arabicNames[array_rand($arabicNames)],
                'gender' => $gender,
                'birth_place' => 'Jakarta',
                'birth_date' => now()->subYears(10 + ($i % 8))->subDays($i * 10),
                'address' => "Jl. Contoh No. $i",
                'father_name' => 'Ayah ' . $lastName,
                'mother_name' => 'Ibu ' . $lastName,
                'guardian_name' => 'Wali ' . $lastName,
                'guardian_phone' => '0812345600' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
            ]);
        }
    }
}
