<?php

namespace App\Services;

use App\Models\AttendanceDetail;
use App\Models\AttendanceSession;
use App\Models\Attitude;
use App\Models\Grade;
use App\Models\HomeroomAssignment;
use App\Models\Student;
use App\Models\StudentClassEnrollment;
use App\Models\Teacher;
use App\Models\TeacherJournal;
use App\Models\TeachingAssignment;
use App\Models\User;
use IntlDateFormatter;

class ReportCardService
{
    public function build(Student $student, StudentClassEnrollment $enrollment): array
    {
        $schoolClass = $enrollment->schoolClass;
        $academicYear = $enrollment->academicYear;
        $semester = $enrollment->semester;

        $teachingAssignments = TeachingAssignment::with(['subject', 'teacher'])
            ->where('school_class_id', $schoolClass->id)
            ->where('academic_year_id', $academicYear->id)
            ->where('semester_id', $semester->id)
            ->get();

        $teachingAssignmentIds = $teachingAssignments->pluck('id');

        $dailyScores = TeacherJournal::whereIn('teaching_assignment_id', $teachingAssignmentIds)
            ->where('student_id', $student->id)
            ->where('journal_type', 'nilai_harian')
            ->where('status', 'submitted')
            ->selectRaw('teaching_assignment_id, AVG(daily_score) as daily_score')
            ->groupBy('teaching_assignment_id')
            ->pluck('daily_score', 'teaching_assignment_id');

        $journalExamScores = TeacherJournal::whereIn('teaching_assignment_id', $teachingAssignmentIds)
            ->where('student_id', $student->id)
            ->where('journal_type', 'tamrinan')
            ->where('status', 'submitted')
            ->selectRaw('teaching_assignment_id, MAX(exam_score) as exam_score')
            ->groupBy('teaching_assignment_id')
            ->pluck('exam_score', 'teaching_assignment_id');

        $grades = Grade::whereIn('teaching_assignment_id', $teachingAssignmentIds)
            ->where('student_id', $student->id)
            ->where('status', 'submitted')
            ->get()
            ->keyBy('teaching_assignment_id');

        $attitude = Attitude::where('student_id', $student->id)
            ->where('school_class_id', $schoolClass->id)
            ->where('academic_year_id', $academicYear->id)
            ->where('semester_id', $semester->id)
            ->first();

        $sessions = AttendanceSession::where('school_class_id', $schoolClass->id)
            ->where('academic_year_id', $academicYear->id)
            ->where('semester_id', $semester->id);

        $attendanceDays = (clone $sessions)->distinct('attendance_date')->count('attendance_date');

        $sessionIds = (clone $sessions)->pluck('id');

        $attendanceCounts = AttendanceDetail::where('student_id', $student->id)
            ->whereIn('attendance_session_id', $sessionIds)
            ->selectRaw("
                SUM(CASE WHEN status = 'permission' THEN 1 ELSE 0 END) as permission_count,
                SUM(CASE WHEN status = 'sick' THEN 1 ELSE 0 END) as sick_count,
                SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_count
            ")
            ->first();

        $subjectRows = collect();
        $totalFinalScores = 0;
        $countFinalScores = 0;
        $dailyScoreTotal = 0;
        $examScoreTotal = 0;

        $sortedAssignments = $teachingAssignments->sortBy(function ($ta) {
            return sprintf('%03d-%06d', $ta->subject?->sort_order ?? 999, $ta->id);
        })->values();

        foreach ($sortedAssignments as $ta) {
            $grade = $grades->get($ta->id);
            $daily = $dailyScores->get($ta->id);
            $exam = $grade?->score ?? $journalExamScores->get($ta->id);
            $final = $exam ?? $daily;

            if ($daily !== null) {
                $dailyScoreTotal += (float) $daily;
            }
            if ($exam !== null) {
                $examScoreTotal += (float) $exam;
            }
            if ($final !== null) {
                $totalFinalScores += (float) $final;
                $countFinalScores++;
            }

            $subjectRows->push([
                'number' => $subjectRows->count() + 1,
                'subject_name' => $ta->subject?->name ?? '-',
                'arabic_name' => $ta->subject?->arabic_name ?? $ta->subject?->name ?? '-',
                'daily_score' => $daily !== null ? number_format((float) $daily, 0) : '-',
                'exam_score' => $exam !== null ? number_format((float) $exam, 0) : '-',
                'final_score' => $final !== null ? number_format((float) $final, 2) : '-',
                'predicate' => $grade?->predicate,
                'teacher_name' => $ta->teacher?->name ?? '-',
                'teacher_arabic_name' => $ta->teacher?->arabic_name ?? $ta->teacher?->name ?? '-',
            ]);
        }

        $average = $countFinalScores > 0
            ? number_format($totalFinalScores / $countFinalScores, 2)
            : '-';

        $homeroomAssignment = HomeroomAssignment::with('teacher')
            ->where('school_class_id', $schoolClass->id)
            ->where('academic_year_id', $academicYear->id)
            ->where('semester_id', $semester->id)
            ->first();

        $homeroomTeacher = $homeroomAssignment?->teacher;

        $principal = Teacher::whereHas('user', function ($q) {
            $q->whereHas('roles', function ($r) {
                $r->where('name', 'kepala_sekolah');
            });
        })->first();

        if (! $principal) {
            $principal = User::whereHas('roles', function ($q) {
                $q->where('name', 'kepala_sekolah');
            })->first()?->teacher;
        }

        $rank = $this->calculateRank($student, $schoolClass, $academicYear, $semester);

        $hijriYear = $this->formatYearRange(
            $academicYear->start_date,
            $academicYear->end_date,
            'ar_SA@calendar=islamic-umalqura',
            'هـ',
            IntlDateFormatter::TRADITIONAL
        );

        $masehiYear = $this->formatYearRange(
            $academicYear->start_date,
            $academicYear->end_date,
            'ar_SA',
            'م',
            IntlDateFormatter::GREGORIAN
        );

        return [
            'student' => $student,
            'schoolClass' => $schoolClass,
            'academicYear' => $academicYear,
            'semester' => $semester,
            'semesterArabic' => $this->semesterArabicName($semester->name),
            'hijriYear' => $hijriYear,
            'masehiYear' => $masehiYear,
            'subjectRows' => $subjectRows,
            'totalScore' => $countFinalScores > 0 ? number_format($totalFinalScores, 2) : '-',
            'average' => $average,
            'rank' => $rank,
            'attitude' => $attitude,
            'permissionCount' => $attendanceCounts->permission_count ?? 0,
            'sickCount' => $attendanceCounts->sick_count ?? 0,
            'absentCount' => $attendanceCounts->absent_count ?? 0,
            'attendanceDays' => $attendanceDays,
            'homeroomTeacher' => $homeroomTeacher,
            'firstTeacher' => $sortedAssignments->first()?->teacher,
            'principal' => $principal,
            'dailyScoreTotal' => $dailyScoreTotal,
            'examScoreTotal' => $examScoreTotal,
            'subjectCount' => $subjectRows->count(),
        ];
    }

    private function calculateRank(
        Student $student,
        $schoolClass,
        $academicYear,
        $semester
    ): ?int {
        $assignmentIds = TeachingAssignment::where('school_class_id', $schoolClass->id)
            ->where('academic_year_id', $academicYear->id)
            ->where('semester_id', $semester->id)
            ->pluck('id');

        if ($assignmentIds->isEmpty()) {
            return null;
        }

        $studentIds = StudentClassEnrollment::where('school_class_id', $schoolClass->id)
            ->where('academic_year_id', $academicYear->id)
            ->where('semester_id', $semester->id)
            ->where('is_active', true)
            ->pluck('student_id');

        $averages = Grade::whereIn('student_id', $studentIds)
            ->whereIn('teaching_assignment_id', $assignmentIds)
            ->where('status', 'submitted')
            ->whereNotNull('score')
            ->get()
            ->groupBy('student_id')
            ->map(fn($grades) => (float) $grades->avg('score'))
            ->sortDesc();

        $position = $averages->keys()->search($student->id);

        return $position === false ? null : $position + 1;
    }

    private function formatYearRange($startDate, $endDate, string $locale, string $suffix, int $calendar): ?string
    {
        if (!$startDate || !$endDate) {
            return null;
        }

        try {
            $formatter = new IntlDateFormatter(
                $locale,
                IntlDateFormatter::NONE,
                IntlDateFormatter::NONE,
                null,
                $calendar,
                'yyyy'
            );

            $start = $formatter->format($startDate);
            $end = $formatter->format($endDate);

            return $start . ' - ' . $end . ' ' . $suffix;
        } catch (\Throwable) {
            return null;
        }
    }

    private function semesterArabicName(string $name): string
    {
        return match (mb_strtolower($name)) {
            'ganjil', 'semester ganjil', 'semester 1' => 'النصف الأول',
            'genap', 'semester genap', 'semester 2' => 'النصف الثاني',
            default => $name,
        };
    }
}
