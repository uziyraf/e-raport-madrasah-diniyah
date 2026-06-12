<?php

namespace App\Http\Controllers\Homeroom;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreJournalRequest;
use App\Models\HomeroomAssignment;
use App\Models\Student;
use App\Models\StudentClassEnrollment;
use App\Models\TeacherJournal;
use App\Models\TeachingAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $homeroom = HomeroomAssignment::with(['schoolClass.level', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->first();

        $labels = [
            'hafalan' => 'Hafalan',
            'legalisir_kitab' => 'Legalisir Kitab',
            'nilai_harian' => 'Nilai Harian',
            'tamrinan' => 'Tamrinan',
            'catatan' => 'Catatan',
        ];

        $types = ['hafalan', 'legalisir_kitab', 'nilai_harian', 'tamrinan', 'catatan'];
        $stats = [];
        $totalStudents = 0;

        if ($homeroom) {
            $totalStudents = StudentClassEnrollment::where('school_class_id', $homeroom->school_class_id)
                ->where('academic_year_id', $homeroom->academic_year_id)
                ->where('semester_id', $homeroom->semester_id)
                ->where('is_active', true)
                ->count();

            foreach ($types as $type) {
                $base = TeacherJournal::where('teacher_id', $teacher->id)
                    ->where('journal_type', $type)
                    ->where('school_class_id', $homeroom->school_class_id)
                    ->where('academic_year_id', $homeroom->academic_year_id)
                    ->where('semester_id', $homeroom->semester_id);

                $stats[$type] = [
                    'total_records' => (clone $base)->count(),
                    'students_with_records' => (clone $base)->distinct('student_id')->count('student_id'),
                    'latest_date' => (clone $base)->max('journal_date'),
                ];
            }
        }

        return view('homeroom.journals.index', compact(
            'homeroom', 'labels', 'stats', 'totalStudents'
        ));
    }

    public function students($journalType, Request $request)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $validTypes = ['hafalan', 'legalisir_kitab', 'nilai_harian', 'tamrinan', 'catatan'];

        if (!in_array($journalType, $validTypes)) {
            abort(404);
        }

        $homeroom = HomeroomAssignment::with(['schoolClass.level', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->firstOrFail();

        $labels = [
            'hafalan' => 'Hafalan',
            'legalisir_kitab' => 'Legalisir Kitab',
            'nilai_harian' => 'Nilai Harian',
            'tamrinan' => 'Tamrinan',
            'catatan' => 'Catatan',
        ];

        $students = StudentClassEnrollment::with('student')
            ->where('school_class_id', $homeroom->school_class_id)
            ->where('academic_year_id', $homeroom->academic_year_id)
            ->where('semester_id', $homeroom->semester_id)
            ->where('is_active', true)
            ->orderBy('created_at')
            ->get()
            ->map(function ($enrollment) use ($teacher, $journalType, $homeroom) {
                $journals = TeacherJournal::where('teacher_id', $teacher->id)
                    ->where('journal_type', $journalType)
                    ->where('student_id', $enrollment->student_id)
                    ->where('school_class_id', $homeroom->school_class_id)
                    ->where('academic_year_id', $homeroom->academic_year_id)
                    ->where('semester_id', $homeroom->semester_id)
                    ->orderByDesc('journal_date');

                return (object) [
                    'student' => $enrollment->student,
                    'total_journals' => (clone $journals)->count(),
                    'latest_date' => (clone $journals)->value('journal_date'),
                    'latest_status' => (clone $journals)->value('status'),
                ];
            });

        return view('homeroom.journals.students', compact(
            'students', 'journalType', 'labels', 'homeroom'
        ));
    }

    public function studentJournal($journalType, Student $student, Request $request)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $validTypes = ['hafalan', 'legalisir_kitab', 'nilai_harian', 'tamrinan', 'catatan'];

        if (!in_array($journalType, $validTypes)) {
            abort(404);
        }

        $homeroom = HomeroomAssignment::with(['schoolClass.level', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->firstOrFail();

        $enrolled = StudentClassEnrollment::where('student_id', $student->id)
            ->where('school_class_id', $homeroom->school_class_id)
            ->where('academic_year_id', $homeroom->academic_year_id)
            ->where('semester_id', $homeroom->semester_id)
            ->where('is_active', true)
            ->exists();

        if (!$enrolled) {
            abort(403, 'Santri ini tidak terdaftar di kelas wali anda.');
        }

        $labels = [
            'hafalan' => 'Hafalan',
            'legalisir_kitab' => 'Legalisir Kitab',
            'nilai_harian' => 'Nilai Harian',
            'tamrinan' => 'Tamrinan',
            'catatan' => 'Catatan',
        ];

        $journals = TeacherJournal::where('teacher_id', $teacher->id)
            ->where('journal_type', $journalType)
            ->where('student_id', $student->id)
            ->where('school_class_id', $homeroom->school_class_id)
            ->where('academic_year_id', $homeroom->academic_year_id)
            ->where('semester_id', $homeroom->semester_id)
            ->orderByDesc('journal_date')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('homeroom.journals.student', compact(
            'journals', 'journalType', 'labels', 'student', 'homeroom'
        ));
    }

    public function create($journalType, Student $student, Request $request)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $validTypes = ['hafalan', 'legalisir_kitab', 'nilai_harian', 'tamrinan', 'catatan'];

        if (!in_array($journalType, $validTypes)) {
            abort(404);
        }

        $homeroom = HomeroomAssignment::with(['schoolClass.level', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->firstOrFail();

        $enrolled = StudentClassEnrollment::where('student_id', $student->id)
            ->where('school_class_id', $homeroom->school_class_id)
            ->where('academic_year_id', $homeroom->academic_year_id)
            ->where('semester_id', $homeroom->semester_id)
            ->where('is_active', true)
            ->exists();

        if (!$enrolled) {
            abort(403, 'Santri ini tidak terdaftar di kelas wali anda.');
        }

        $labels = [
            'hafalan' => 'Hafalan',
            'legalisir_kitab' => 'Legalisir Kitab',
            'nilai_harian' => 'Nilai Harian',
            'tamrinan' => 'Tamrinan',
            'catatan' => 'Catatan',
        ];

        $teachingAssignments = TeachingAssignment::with(['subject', 'schoolClass.level', 'academicYear', 'semester'])
            ->where('school_class_id', $homeroom->school_class_id)
            ->where('academic_year_id', $homeroom->academic_year_id)
            ->where('semester_id', $homeroom->semester_id)
            ->orderBy('created_at')
            ->get();

        return view('homeroom.journals.create', compact(
            'journalType', 'labels', 'student', 'homeroom', 'teachingAssignments'
        ));
    }

    public function store(StoreJournalRequest $request)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $homeroom = HomeroomAssignment::where('teacher_id', $teacher->id)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->firstOrFail();

        $enrolled = StudentClassEnrollment::where('student_id', $request->student_id)
            ->where('school_class_id', $homeroom->school_class_id)
            ->where('academic_year_id', $homeroom->academic_year_id)
            ->where('semester_id', $homeroom->semester_id)
            ->where('is_active', true)
            ->exists();

        if (!$enrolled) {
            abort(403, 'Santri ini tidak terdaftar di kelas wali anda.');
        }

        TeacherJournal::create([
            'journal_date' => $request->journal_date,
            'teacher_id' => $teacher->id,
            'teaching_assignment_id' => $request->teaching_assignment_id,
            'school_class_id' => $homeroom->school_class_id,
            'academic_year_id' => $homeroom->academic_year_id,
            'semester_id' => $homeroom->semester_id,
            'journal_type' => $request->journal_type,
            'student_id' => $request->student_id,
            'memorization_type' => $request->memorization_type,
            'memorization_target' => $request->memorization_target,
            'memorization_result' => $request->memorization_result,
            'kitab_name' => $request->kitab_name,
            'kitab_page' => $request->kitab_page,
            'legalization_status' => $request->legalization_status,
            'daily_score' => $request->daily_score,
            'exam_score' => $request->exam_score,
            'predicate' => $request->predicate,
            'note' => $request->note,
            'status' => $request->status,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('homeroom.journals.student', [
            'journalType' => $request->journal_type,
            'student' => $request->student_id,
        ])->with('success', 'Jurnal berhasil disimpan.');
    }

    public function show(TeacherJournal $teacherJournal)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $homeroom = HomeroomAssignment::where('teacher_id', $teacher->id)
            ->where('school_class_id', $teacherJournal->school_class_id)
            ->where('academic_year_id', $teacherJournal->academic_year_id)
            ->where('semester_id', $teacherJournal->semester_id)
            ->first();

        if ($teacherJournal->teacher_id !== $teacher->id && !$homeroom) {
            abort(403, 'Anda tidak memiliki akses ke jurnal ini.');
        }

        $journal = $teacherJournal->loadMissing([
            'teachingAssignment.subject', 'schoolClass.level', 'academicYear', 'semester', 'student', 'teacher',
        ]);

        $labels = [
            'hafalan' => 'Hafalan',
            'legalisir_kitab' => 'Legalisir Kitab',
            'nilai_harian' => 'Nilai Harian',
            'tamrinan' => 'Tamrinan',
            'catatan' => 'Catatan',
        ];

        return view('homeroom.journals.show', compact('journal', 'labels'));
    }

    public function edit(TeacherJournal $teacherJournal)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $homeroom = HomeroomAssignment::where('teacher_id', $teacher->id)
            ->where('school_class_id', $teacherJournal->school_class_id)
            ->where('academic_year_id', $teacherJournal->academic_year_id)
            ->where('semester_id', $teacherJournal->semester_id)
            ->first();

        if ($teacherJournal->teacher_id !== $teacher->id && !$homeroom) {
            abort(403, 'Anda tidak memiliki akses ke jurnal ini.');
        }

        $journal = $teacherJournal->loadMissing([
            'teachingAssignment.subject', 'schoolClass.level', 'academicYear', 'semester', 'student',
        ]);

        $labels = [
            'hafalan' => 'Hafalan',
            'legalisir_kitab' => 'Legalisir Kitab',
            'nilai_harian' => 'Nilai Harian',
            'tamrinan' => 'Tamrinan',
            'catatan' => 'Catatan',
        ];

        $teachingAssignments = TeachingAssignment::with(['subject', 'schoolClass.level', 'academicYear', 'semester'])
            ->where('school_class_id', $teacherJournal->school_class_id)
            ->where('academic_year_id', $teacherJournal->academic_year_id)
            ->where('semester_id', $teacherJournal->semester_id)
            ->orderBy('created_at')
            ->get();

        return view('homeroom.journals.edit', compact('journal', 'labels', 'teachingAssignments'));
    }

    public function update(StoreJournalRequest $request, TeacherJournal $teacherJournal)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $homeroom = HomeroomAssignment::where('teacher_id', $teacher->id)
            ->where('school_class_id', $teacherJournal->school_class_id)
            ->where('academic_year_id', $teacherJournal->academic_year_id)
            ->where('semester_id', $teacherJournal->semester_id)
            ->first();

        if ($teacherJournal->teacher_id !== $teacher->id && !$homeroom) {
            abort(403, 'Anda tidak memiliki akses ke jurnal ini.');
        }

        $data = [
            'journal_date' => $request->journal_date,
            'memorization_type' => $request->memorization_type,
            'memorization_target' => $request->memorization_target,
            'memorization_result' => $request->memorization_result,
            'kitab_name' => $request->kitab_name,
            'kitab_page' => $request->kitab_page,
            'legalization_status' => $request->legalization_status,
            'daily_score' => $request->daily_score,
            'exam_score' => $request->exam_score,
            'predicate' => $request->predicate,
            'note' => $request->note,
            'status' => $request->status,
        ];

        if ($request->filled('teaching_assignment_id')) {
            $assignment = TeachingAssignment::where('school_class_id', $teacherJournal->school_class_id)
                ->where('academic_year_id', $teacherJournal->academic_year_id)
                ->where('semester_id', $teacherJournal->semester_id)
                ->find($request->teaching_assignment_id);

            if (!$assignment) {
                abort(403, 'Penugasan mengajar tidak valid.');
            }

            $data['teaching_assignment_id'] = $assignment->id;
        }

        $teacherJournal->update($data);

        return redirect()->route('homeroom.journals.student', [
            'journalType' => $teacherJournal->journal_type,
            'student' => $teacherJournal->student_id,
        ])->with('success', 'Jurnal berhasil diperbarui.');
    }

    public function destroy(TeacherJournal $teacherJournal)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $homeroom = HomeroomAssignment::where('teacher_id', $teacher->id)
            ->where('school_class_id', $teacherJournal->school_class_id)
            ->where('academic_year_id', $teacherJournal->academic_year_id)
            ->where('semester_id', $teacherJournal->semester_id)
            ->first();

        if ($teacherJournal->teacher_id !== $teacher->id && !$homeroom) {
            abort(403, 'Anda tidak memiliki akses ke jurnal ini.');
        }

        $journalType = $teacherJournal->journal_type;
        $studentId = $teacherJournal->student_id;
        $teacherJournal->delete();

        return redirect()->route('homeroom.journals.student', [
            'journalType' => $journalType,
            'student' => $studentId,
        ])->with('success', 'Jurnal berhasil dihapus.');
    }
}
