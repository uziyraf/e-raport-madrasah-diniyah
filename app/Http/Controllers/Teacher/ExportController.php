<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AttendanceDetail;
use App\Models\Grade;
use App\Models\TeacherJournal;
use App\Models\TeachingAssignment;
use App\Traits\CsvExportable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    use CsvExportable;

    private function getTeacher()
    {
        $teacher = Auth::user()->teacher;

        abort_if(!$teacher, 403, 'Anda tidak terdaftar sebagai guru.');

        return $teacher;
    }

    private function getAssignments()
    {
        $teacher = $this->getTeacher();

        return TeachingAssignment::with(['subject', 'schoolClass.level', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->orderBy('subject_id')
            ->get();
    }

    private function getAssignmentOrFail($id)
    {
        $teacher = $this->getTeacher();

        $assignment = TeachingAssignment::with(['subject', 'schoolClass.level', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->findOrFail($id);

        return $assignment;
    }

    public function index()
    {
        $assignments = $this->getAssignments();
        $teacher = $this->getTeacher();

        return view('teacher.exports.index', compact('assignments', 'teacher'));
    }

    public function attendances(Request $request)
    {
        $assignment = $this->getAssignmentOrFail($request->query('teaching_assignment_id'));

        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        $filename = 'rekap-absensi-' . $assignment->subject->name . '-' . $assignment->schoolClass->level->name . '-' . $assignment->schoolClass->name . '.csv';

        return $this->streamCsv($filename, [
            'Tanggal', 'Kelas', 'Fan/Mapel', 'NIS', 'Nama Santri',
            'Status Absensi', 'Keterangan',
        ], function ($handle) use ($assignment, $dateFrom, $dateTo) {
            AttendanceDetail::with(['student', 'session.teacher'])
                ->whereHas('session', fn($q) => $q
                    ->where('teacher_id', $assignment->teacher_id)
                    ->where('attendance_type', 'teaching')
                    ->where('teaching_assignment_id', $assignment->id)
                    ->where('school_class_id', $assignment->school_class_id)
                    ->where('academic_year_id', $assignment->academic_year_id)
                    ->where('semester_id', $assignment->semester_id)
                    ->when($dateFrom, fn($q) => $q->whereDate('attendance_date', '>=', $dateFrom))
                    ->when($dateTo, fn($q) => $q->whereDate('attendance_date', '<=', $dateTo))
                )
                ->orderBy('id')
                ->chunk(200, function ($details) use ($handle, $assignment) {
                    foreach ($details as $d) {
                        $this->csvRow($handle, [
                            $d->session->attendance_date->format('d/m/Y'),
                            $assignment->schoolClass->level->name . ' ' . $assignment->schoolClass->name,
                            $assignment->subject->name,
                            $d->student->nis,
                            $d->student->name,
                            $this->attendanceStatusLabel($d->status),
                            $d->note ?? '-',
                        ]);
                    }
                });
        });
    }

    public function grades(Request $request)
    {
        $assignment = $this->getAssignmentOrFail($request->query('teaching_assignment_id'));

        $filename = 'rekap-nilai-' . $assignment->subject->name . '-' . $assignment->schoolClass->level->name . '-' . $assignment->schoolClass->name . '.csv';

        return $this->streamCsv($filename, [
            'NIS', 'Nama Santri', 'Nilai', 'Predikat', 'Keterangan', 'Status',
        ], function ($handle) use ($assignment) {
            Grade::with('student')
                ->where('teaching_assignment_id', $assignment->id)
                ->orderBy('id')
                ->chunk(200, function ($grades) use ($handle) {
                    foreach ($grades as $g) {
                        $this->csvRow($handle, [
                            $g->student->nis,
                            $g->student->name,
                            $g->score ?? '-',
                            $g->predicate ?? '-',
                            $g->note ?? '-',
                            $this->statusLabel($g->status),
                        ]);
                    }
                });
        });
    }

    public function journals(Request $request)
    {
        $assignment = $this->getAssignmentOrFail($request->query('teaching_assignment_id'));

        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        $filename = 'rekap-jurnal-' . $assignment->subject->name . '-' . $assignment->schoolClass->level->name . '-' . $assignment->schoolClass->name . '.csv';

        return $this->streamCsv($filename, [
            'Tanggal', 'Jenis Jurnal', 'NIS', 'Nama Santri',
            'Isi Ringkas', 'Predikat', 'Status',
        ], function ($handle) use ($assignment, $dateFrom, $dateTo) {
            TeacherJournal::with('student')
                ->where('teacher_id', $assignment->teacher_id)
                ->where('teaching_assignment_id', $assignment->id)
                ->where('school_class_id', $assignment->school_class_id)
                ->where('academic_year_id', $assignment->academic_year_id)
                ->where('semester_id', $assignment->semester_id)
                ->when($dateFrom, fn($q) => $q->whereDate('journal_date', '>=', $dateFrom))
                ->when($dateTo, fn($q) => $q->whereDate('journal_date', '<=', $dateTo))
                ->orderBy('journal_date')
                ->chunk(200, function ($journals) use ($handle) {
                    foreach ($journals as $j) {
                        $this->csvRow($handle, [
                            $j->journal_date->format('d/m/Y'),
                            $this->journalTypeLabel($j->journal_type),
                            $j->student?->nis ?? '-',
                            $j->student?->name ?? '-',
                            $this->journalContentSummary($j),
                            $j->predicate ?? '-',
                            $this->statusLabel($j->status),
                        ]);
                    }
                });
        });
    }
}
