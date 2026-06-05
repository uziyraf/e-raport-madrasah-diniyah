<?php

namespace App\Http\Controllers\Homeroom;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AttendanceDetail;
use App\Models\Attitude;
use App\Models\Grade;
use App\Models\HomeroomAssignment;
use App\Models\Semester;
use App\Models\Student;
use App\Models\TeacherJournal;
use App\Traits\CsvExportable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    use CsvExportable;

    private function getHomeroom()
    {
        $teacher = Auth::user()->teacher;

        abort_if(!$teacher, 403, 'Anda tidak terdaftar sebagai guru.');

        $homeroom = HomeroomAssignment::with(['schoolClass.level', 'academicYear', 'semester'])
            ->where('teacher_id', $teacher->id)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->first();

        abort_if(!$homeroom, 403, 'Anda tidak memiliki kelas wali.');

        return $homeroom;
    }

    public function index()
    {
        $homeroom = $this->getHomeroom();

        return view('homeroom.exports.index', compact('homeroom'));
    }

    public function students()
    {
        $homeroom = $this->getHomeroom();

        $filename = 'data-santri-' . $homeroom->schoolClass->level->name . '-' . $homeroom->schoolClass->name . '.csv';

        return $this->streamCsv($filename, [
            'NIS', 'Nama Santri', 'Nama Arab', 'Jenis Kelamin',
            'Tempat Lahir', 'Tanggal Lahir', 'Nama Ayah', 'Nama Ibu',
            'Nama Wali', 'No HP Wali', 'Status',
        ], function ($handle) use ($homeroom) {
            Student::whereHas('classEnrollments', fn($q) => $q
                ->where('school_class_id', $homeroom->school_class_id)
                ->where('academic_year_id', $homeroom->academic_year_id)
                ->where('semester_id', $homeroom->semester_id)
                ->where('is_active', true)
            )
                ->orderBy('name')
                ->chunk(200, function ($students) use ($handle) {
                    foreach ($students as $s) {
                        $this->csvRow($handle, [
                            $s->nis,
                            $s->name,
                            $s->arabic_name ?? '-',
                            $this->genderLabel($s->gender),
                            $s->birth_place ?? '-',
                            $s->birth_date?->format('d/m/Y') ?? '-',
                            $s->father_name ?? '-',
                            $s->mother_name ?? '-',
                            $s->guardian_name ?? '-',
                            $s->guardian_phone ?? '-',
                            $this->statusLabel($s->status),
                        ]);
                    }
                });
        });
    }

    public function attendances(Request $request)
    {
        $homeroom = $this->getHomeroom();

        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        $filename = 'rekap-absensi-' . $homeroom->schoolClass->level->name . '-' . $homeroom->schoolClass->name . '.csv';

        return $this->streamCsv($filename, [
            'Tanggal', 'Jenis Absensi', 'Fan/Mapel',
            'Guru', 'NIS', 'Nama Santri', 'Status Absensi', 'Keterangan',
        ], function ($handle) use ($homeroom, $dateFrom, $dateTo) {
            AttendanceDetail::with([
                'session.teachingAssignment.subject',
                'session.teacher',
                'student',
            ])
                ->whereHas('session', fn($q) => $q
                    ->where('school_class_id', $homeroom->school_class_id)
                    ->where('academic_year_id', $homeroom->academic_year_id)
                    ->where('semester_id', $homeroom->semester_id)
                    ->when($dateFrom, fn($q) => $q->whereDate('attendance_date', '>=', $dateFrom))
                    ->when($dateTo, fn($q) => $q->whereDate('attendance_date', '<=', $dateTo))
                )
                ->orderBy('id')
                ->chunk(200, function ($details) use ($handle) {
                    foreach ($details as $d) {
                        $this->csvRow($handle, [
                            $d->session->attendance_date->format('d/m/Y'),
                            $d->session->attendance_type === 'homeroom' ? 'Absensi Kelas' : 'Absensi Mengajar',
                            $d->session->teachingAssignment?->subject?->name ?? '-',
                            $d->session->teacher?->name ?? '-',
                            $d->student->nis,
                            $d->student->name,
                            $this->attendanceStatusLabel($d->status),
                            $d->note ?? '-',
                        ]);
                    }
                });
        });
    }

    public function grades()
    {
        $homeroom = $this->getHomeroom();

        $filename = 'rekap-nilai-' . $homeroom->schoolClass->level->name . '-' . $homeroom->schoolClass->name . '.csv';

        return $this->streamCsv($filename, [
            'Fan/Mapel', 'Guru Pengampu', 'NIS', 'Nama Santri',
            'Nilai', 'Predikat', 'Keterangan', 'Status',
        ], function ($handle) use ($homeroom) {
            Grade::with([
                'student',
                'teachingAssignment.subject',
                'teachingAssignment.teacher',
            ])
                ->whereHas('teachingAssignment', fn($q) => $q
                    ->where('school_class_id', $homeroom->school_class_id)
                    ->where('academic_year_id', $homeroom->academic_year_id)
                    ->where('semester_id', $homeroom->semester_id)
                )
                ->orderBy('id')
                ->chunk(200, function ($grades) use ($handle) {
                    foreach ($grades as $g) {
                        $this->csvRow($handle, [
                            $g->teachingAssignment?->subject?->name ?? '-',
                            $g->teachingAssignment?->teacher?->name ?? '-',
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

    public function attitudes()
    {
        $homeroom = $this->getHomeroom();

        $filename = 'rekap-sikap-' . $homeroom->schoolClass->level->name . '-' . $homeroom->schoolClass->name . '.csv';

        return $this->streamCsv($filename, [
            'NIS', 'Nama Santri', 'Akhlak', 'Kedisiplinan',
            'Kebersihan', 'Catatan Sikap',
        ], function ($handle) use ($homeroom) {
            Attitude::with('student')
                ->where('school_class_id', $homeroom->school_class_id)
                ->where('academic_year_id', $homeroom->academic_year_id)
                ->where('semester_id', $homeroom->semester_id)
                ->orderBy('id')
                ->chunk(200, function ($attitudes) use ($handle) {
                    foreach ($attitudes as $a) {
                        $this->csvRow($handle, [
                            $a->student->nis,
                            $a->student->name,
                            $a->akhlak ?? '-',
                            $a->discipline ?? '-',
                            $a->cleanliness ?? '-',
                            $a->attitude_note ?? '-',
                        ]);
                    }
                });
        });
    }

    public function journals(Request $request)
    {
        $homeroom = $this->getHomeroom();

        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        $filename = 'rekap-jurnal-' . $homeroom->schoolClass->level->name . '-' . $homeroom->schoolClass->name . '.csv';

        return $this->streamCsv($filename, [
            'Tanggal', 'Jenis Jurnal', 'Fan/Mapel', 'Guru',
            'NIS', 'Nama Santri', 'Isi Ringkas', 'Predikat', 'Status',
        ], function ($handle) use ($homeroom, $dateFrom, $dateTo) {
            TeacherJournal::with([
                'teacher', 'teachingAssignment.subject', 'student',
            ])
                ->where('school_class_id', $homeroom->school_class_id)
                ->where('academic_year_id', $homeroom->academic_year_id)
                ->where('semester_id', $homeroom->semester_id)
                ->when($dateFrom, fn($q) => $q->whereDate('journal_date', '>=', $dateFrom))
                ->when($dateTo, fn($q) => $q->whereDate('journal_date', '<=', $dateTo))
                ->orderBy('journal_date')
                ->chunk(200, function ($journals) use ($handle) {
                    foreach ($journals as $j) {
                        $this->csvRow($handle, [
                            $j->journal_date->format('d/m/Y'),
                            $this->journalTypeLabel($j->journal_type),
                            $j->teachingAssignment?->subject?->name ?? '-',
                            $j->teacher?->name ?? '-',
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
