<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\HomeroomAssignment;
use App\Models\Level;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentClassEnrollment;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (['super_admin', 'kepala_sekolah', 'wali_kelas', 'guru_fan', 'wali_santri'] as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        $levels = collect([
            ['name' => "Ibtida'iyah", 'description' => 'Tingkat Dasar', 'sort_order' => 1],
            ['name' => 'Tsanawiyah', 'description' => 'Tingkat Menengah', 'sort_order' => 2],
            ['name' => 'Aliyah', 'description' => 'Tingkat Atas', 'sort_order' => 3],
        ])->map(fn (array $data) => Level::updateOrCreate(
            ['name' => $data['name']],
            $data + ['status' => 'active'],
        ));

        $academicYear = AcademicYear::updateOrCreate(
            ['name' => '2026/2027'],
            ['start_date' => '2026-07-01', 'end_date' => '2027-06-30', 'is_active' => true],
        );
        AcademicYear::where('id', '!=', $academicYear->id)->update(['is_active' => false]);

        $ganjil = Semester::updateOrCreate(
            ['academic_year_id' => $academicYear->id, 'name' => 'Ganjil'],
            ['start_date' => '2026-07-01', 'end_date' => '2026-12-31', 'is_active' => true],
        );
        Semester::updateOrCreate(
            ['academic_year_id' => $academicYear->id, 'name' => 'Genap'],
            ['start_date' => '2027-01-01', 'end_date' => '2027-06-30', 'is_active' => false],
        );
        Semester::where('academic_year_id', $academicYear->id)
            ->where('id', '!=', $ganjil->id)
            ->update(['is_active' => false]);

        $subjects = collect([
            ['name' => "Al-Qur'an", 'arabic_name' => 'القرآن', 'code' => 'QUR', 'sort_order' => 1],
            ['name' => 'Hadits', 'arabic_name' => 'الحديث', 'code' => 'HDS', 'sort_order' => 2],
            ['name' => 'Fiqih', 'arabic_name' => 'الفقه', 'code' => 'FQH', 'sort_order' => 3],
            ['name' => 'Akidah Akhlak', 'arabic_name' => 'العقيدة والأخلاق', 'code' => 'AAK', 'sort_order' => 4],
            ['name' => 'Bahasa Arab', 'arabic_name' => 'اللغة العربية', 'code' => 'BAR', 'sort_order' => 5],
            ['name' => 'Sejarah Kebudayaan Islam', 'arabic_name' => 'التاريخ الإسلامي', 'code' => 'SKI', 'sort_order' => 6],
        ])->map(fn (array $data) => Subject::updateOrCreate(
            ['code' => $data['code']],
            $data + ['status' => 'active'],
        ));

        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            ['name' => 'Super Admin', 'email' => 'admin@simadu.test', 'password' => Hash::make('password'), 'status' => 'active'],
        );
        $admin->syncRoles(['super_admin']);

        $kepalaUser = User::updateOrCreate(
            ['username' => 'kepala'],
            ['name' => 'Kepala Madrasah', 'email' => 'kepala@simadu.test', 'password' => Hash::make('password'), 'status' => 'active'],
        );
        $kepalaUser->syncRoles(['kepala_sekolah']);

        Teacher::updateOrCreate(
            ['teacher_code' => 'GR-KEPALA'],
            ['user_id' => $kepalaUser->id, 'name' => 'Kepala Madrasah', 'arabic_name' => 'محمد رشيد', 'gender' => 'male', 'status' => 'active'],
        );

        $parallels = ['A', 'B', 'C'];
        $waliArabicNames = ['أحمد الفوزي', 'علي الحسن', 'عبد الرحمن', 'محمد أمين', 'يوسف حكيم', 'إبراهيم صالح', 'حسن البصري', 'زين العابدين', 'عمر الفاروق'];
        $fanArabicNames = ['فاطمة الزهراء', 'عائشة الصديقة', 'خديجة الكبرى', 'مريم العفيفة', 'زينب الهاشمية', 'سلمى النبيلة', 'حفصة الرشيدة', 'أسماء بنت أبي بكر', 'رقية الكريمة'];
        $firstNames = ['Ahmad', 'Muhammad', 'Abdullah', 'Siti', 'Aisyah', 'Fatimah', 'Khadijah', 'Maryam', 'Zainab', 'Hafsah', 'Yusuf', 'Ibrahim', 'Hasan', 'Husain', 'Salman', 'Umar', 'Ali', 'Ridwan', 'Fauzan', 'Nabila'];
        $lastNames = ['Hidayat', 'Nurrahman', 'Fauzi', 'Pratama', 'Wijaya', 'Ramadhan', 'Hakim', 'Syafii', 'Rahman', 'Anwar'];
        $arabicFirstNames = ['أحمد', 'محمد', 'عبد الله', 'سيتي', 'عائشة', 'فاطمة', 'خديجة', 'مريم', 'زينب', 'حفصة', 'يوسف', 'إبراهيم', 'حسن', 'حسين', 'سلمان', 'عمر', 'علي', 'رضوان', 'فوزان', 'نبيلة'];
        $arabicLastNames = ['هداية', 'نور الرحمن', 'فوزي', 'براتاما', 'ويجايا', 'رمضان', 'حكيم', 'شافعي', 'رحمن', 'أنور'];

        $classIndex = 0;
        $studentIndex = 0;

        foreach ($levels as $level) {
            foreach ($parallels as $parallel) {
                $classCode = strtoupper(substr($level->name, 0, 3)).'1'.$parallel;
                $className = '1'.$parallel;

                $schoolClass = SchoolClass::updateOrCreate(
                    ['code' => $classCode],
                    [
                        'level_id' => $level->id,
                        'grade_level' => 1,
                        'parallel_name' => $parallel,
                        'name' => $className,
                        'sort_order' => ++$classIndex,
                        'status' => 'active',
                    ],
                );

                $waliUser = User::updateOrCreate(
                    ['username' => 'wali.'.strtolower($classCode)],
                    [
                        'name' => 'Wali '.$level->name.' '.$className,
                        'email' => 'wali.'.strtolower($classCode).'@simadu.test',
                        'password' => Hash::make('password'),
                        'status' => 'active',
                    ],
                );
                $waliUser->syncRoles(['wali_kelas']);

                $waliTeacher = Teacher::updateOrCreate(
                    ['teacher_code' => 'GR-WALI-'.$classCode],
                    [
                        'user_id' => $waliUser->id,
                        'name' => 'Ustadz Wali '.$level->name.' '.$className,
                        'arabic_name' => $waliArabicNames[$classIndex - 1],
                        'gender' => 'male',
                        'status' => 'active',
                    ],
                );

                $fanUser = User::updateOrCreate(
                    ['username' => 'guru.'.strtolower($classCode)],
                    [
                        'name' => 'Guru Fan '.$level->name.' '.$className,
                        'email' => 'guru.'.strtolower($classCode).'@simadu.test',
                        'password' => Hash::make('password'),
                        'status' => 'active',
                    ],
                );
                $fanUser->syncRoles(['guru_fan']);

                $fanTeacher = Teacher::updateOrCreate(
                    ['teacher_code' => 'GR-FAN-'.$classCode],
                    [
                        'user_id' => $fanUser->id,
                        'name' => 'Ustadzah Fan '.$level->name.' '.$className,
                        'arabic_name' => $fanArabicNames[$classIndex - 1],
                        'gender' => 'female',
                        'status' => 'active',
                    ],
                );

                HomeroomAssignment::updateOrCreate(
                    [
                        'school_class_id' => $schoolClass->id,
                        'academic_year_id' => $academicYear->id,
                        'semester_id' => $ganjil->id,
                    ],
                    ['teacher_id' => $waliTeacher->id],
                );

                foreach ($subjects as $subject) {
                    TeachingAssignment::updateOrCreate([
                        'teacher_id' => $fanTeacher->id,
                        'subject_id' => $subject->id,
                        'school_class_id' => $schoolClass->id,
                        'academic_year_id' => $academicYear->id,
                        'semester_id' => $ganjil->id,
                    ]);
                }

                for ($i = 1; $i <= 10; $i++) {
                    $studentIndex++;
                    $firstName = $firstNames[$studentIndex % count($firstNames)];
                    $lastName = $lastNames[intdiv($studentIndex, count($firstNames)) % count($lastNames)];
                    $arabicFirst = $arabicFirstNames[$studentIndex % count($arabicFirstNames)];
                    $arabicLast = $arabicLastNames[intdiv($studentIndex, count($arabicFirstNames)) % count($arabicLastNames)];

                    $student = Student::updateOrCreate(
                        ['nis' => '2026'.str_pad((string) $studentIndex, 4, '0', STR_PAD_LEFT)],
                        [
                            'name' => $firstName.' '.$lastName,
                            'arabic_name' => $arabicFirst.' '.$arabicLast,
                            'gender' => $studentIndex % 2 === 0 ? 'female' : 'male',
                            'birth_place' => 'Jakarta',
                            'birth_date' => now()->subYears(10)->subDays($studentIndex)->format('Y-m-d'),
                            'address' => 'Jl. Contoh No. '.$studentIndex,
                            'status' => 'active',
                        ],
                    );

                    StudentClassEnrollment::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'academic_year_id' => $academicYear->id,
                            'semester_id' => $ganjil->id,
                        ],
                        [
                            'school_class_id' => $schoolClass->id,
                            'enrollment_status' => 'active',
                            'is_active' => true,
                            'created_by' => $admin->id,
                        ],
                    );
                }
            }
        }

        $this->command->info('Demo data dibuat: 3 level, 9 kelas, 90 santri.');
        $this->command->info('Akun (password: password):');
        $this->command->info('  admin, kepala');
        $this->command->info('  wali.ibt1a, wali.ibt1b, wali.ibt1c, wali.tsa1a, ...');
        $this->command->info('  guru.ibt1a, guru.ibt1b, guru.ibt1c, guru.tsa1a, ...');
    }
}
