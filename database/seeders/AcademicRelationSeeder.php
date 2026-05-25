<?php

namespace Database\Seeders;

use App\Models\GradeComponent;
use App\Models\HomeroomAssignment;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentClassEnrollment;
use App\Models\Teacher;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\Database\Seeder;

class AcademicRelationSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $semester = Semester::where('academic_year_id', $academicYear->id)->where('is_active', true)->first();
        $adminUser = User::where('email', 'admin@simadu.test')->first();

        // Homeroom Assignments
        $waliKelasTeacher = Teacher::where('name', 'Ahmad Fauzi')->first();
        $schoolClasses = SchoolClass::all();

        foreach ($schoolClasses as $index => $class) {
            HomeroomAssignment::create([
                'teacher_id' => $waliKelasTeacher->id,
                'school_class_id' => $class->id,
                'academic_year_id' => $academicYear->id,
                'semester_id' => $semester->id,
            ]);
        }

        // Student Class Enrollments
        $students = Student::all();
        $students->each(function ($student, $index) use ($schoolClasses, $academicYear, $semester, $adminUser) {
            $classIndex = $index % $schoolClasses->count();
            StudentClassEnrollment::create([
                'student_id' => $student->id,
                'school_class_id' => $schoolClasses[$classIndex]->id,
                'academic_year_id' => $academicYear->id,
                'semester_id' => $semester->id,
                'enrollment_status' => 'active',
                'is_active' => true,
                'created_by' => $adminUser?->id,
            ]);
        });

        // Teaching Assignments
        $teachers = Teacher::all();
        $subjects = \App\Models\Subject::all();
        $teachingAssignments = [];

        foreach ($schoolClasses as $class) {
            foreach ($subjects as $subject) {
                $teacher = $teachers[($class->id + $subject->id) % $teachers->count()];
                $ta = TeachingAssignment::create([
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subject->id,
                    'school_class_id' => $class->id,
                    'academic_year_id' => $academicYear->id,
                    'semester_id' => $semester->id,
                ]);
                $teachingAssignments[] = $ta;
            }
        }

        // Grade Components for each Teaching Assignment
        $componentNames = ['Nilai Harian', 'Tamrinan', 'Ujian Akhir'];
        foreach ($teachingAssignments as $ta) {
            foreach ($componentNames as $order => $name) {
                GradeComponent::create([
                    'teaching_assignment_id' => $ta->id,
                    'name' => $name,
                    'type' => match ($name) {
                        'Nilai Harian' => 'harian',
                        'Tamrinan' => 'tamrinan',
                        'Ujian Akhir' => 'ujian',
                        default => null,
                    },
                    'weight' => match ($name) {
                        'Nilai Harian' => 40,
                        'Tamrinan' => 30,
                        'Ujian Akhir' => 30,
                        default => 0,
                    },
                    'sort_order' => $order + 1,
                ]);
            }
        }
    }
}
