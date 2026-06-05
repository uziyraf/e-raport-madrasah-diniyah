<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\AttendanceDetail;
use App\Models\Attitude;
use App\Models\Grade;
use App\Models\Guardian;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TeacherJournal;
use App\Traits\CsvExportable;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    use CsvExportable;

    public function index()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->where('is_active', true)->first();
        $classes = SchoolClass::active()->with('level')->orderBy('sort_order')->get();
        $years = AcademicYear::orderByDesc('start_date')->get();
        $semesters = Semester::orderByDesc('start_date')->get();

        return view('admin.exports.index', compact(
            'activeYear', 'activeSemester', 'classes', 'years', 'semesters',
        ));
    }

    public function students()
    {
        return $this->streamCsv('data-santri.csv', [
            'NIS', 'Nama Santri', 'Nama Arab', 'Jenis Kelamin',
            'Tempat Lahir', 'Tanggal Lahir', 'Kelas Aktif', 'Jenjang',
            'Tahun Ajaran', 'Semester', 'Nama Ayah', 'Nama Ibu',
            'Nama Wali', 'No HP Wali', 'Status',
        ], function ($handle) {
            Student::with(['activeEnrollment.schoolClass.level', 'activeEnrollment.academicYear', 'activeEnrollment.semester'])
                ->chunk(200, function ($students) use ($handle) {
                    foreach ($students as $s) {
                        $this->csvRow($handle, [
                            $s->nis,
                            $s->name,
                            $s->arabic_name ?? '-',
                            $this->genderLabel($s->gender),
                            $s->birth_place ?? '-',
                            $s->birth_date?->format('d/m/Y') ?? '-',
                            $s->activeEnrollment?->schoolClass?->name ?? '-',
                            $s->activeEnrollment?->schoolClass?->level?->name ?? '-',
                            $s->activeEnrollment?->academicYear?->name ?? '-',
                            $s->activeEnrollment?->semester?->name ?? '-',
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

    public function teachers()
    {
        return $this->streamCsv('data-guru.csv', [
            'Kode Guru', 'Nama Guru', 'Nama Arab', 'Jenis Kelamin',
            'No HP', 'Email', 'Status', 'Akun Login', 'Role',
        ], function ($handle) {
            Teacher::with('user')->chunk(200, function ($teachers) use ($handle) {
                foreach ($teachers as $t) {
                    $roles = $t->user?->roles->pluck('display_name', 'name')
                        ?? $t->user?->getRoleNames() ?? collect();
                    $this->csvRow($handle, [
                        $t->teacher_code,
                        $t->name,
                        $t->arabic_name ?? '-',
                        $this->genderLabel($t->gender),
                        $t->phone ?? '-',
                        $t->email ?? '-',
                        $this->statusLabel($t->status),
                        $t->user?->username ?? '-',
                        $roles->implode(', '),
                    ]);
                }
            });
        });
    }

    public function guardians()
    {
        return $this->streamCsv('data-wali-santri.csv', [
            'Nama Wali', 'No HP', 'Email', 'Username',
            'Status', 'Jumlah Santri Terhubung', 'Santri Terhubung',
        ], function ($handle) {
            Guardian::with('user', 'students')->chunk(200, function ($guardians) use ($handle) {
                foreach ($guardians as $g) {
                    $studentNames = $g->students->pluck('name')->implode('; ');
                    $this->csvRow($handle, [
                        $g->name,
                        $g->phone ?? '-',
                        $g->email ?? '-',
                        $g->user?->username ?? '-',
                        $this->statusLabel($g->status),
                        $g->students->count(),
                        $studentNames ?: '-',
                    ]);
                }
            });
        });
    }

    public function attendances(Request $request)
    {
        $yearId = $request->academic_year_id;
        $semesterId = $request->semester_id;
        $classId = $request->school_class_id;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        $filename = 'rekap-absensi.csv';

        return $this->streamCsv($filename, [
            'Tanggal', 'Jenis Absensi', 'Kelas', 'Fan/Mapel',
            'Guru', 'NIS', 'Nama Santri', 'Status Absensi', 'Keterangan',
        ], function ($handle) use ($yearId, $semesterId, $classId, $dateFrom, $dateTo) {
            AttendanceDetail::with([
                'session.teachingAssignment.subject',
                'session.teacher',
                'session.schoolClass.level',
                'student',
            ])
                ->whereHas('session', fn($q) => $q
                    ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
                    ->when($semesterId, fn($q) => $q->where('semester_id', $semesterId))
                    ->when($classId, fn($q) => $q->where('school_class_id', $classId))
                    ->when($dateFrom, fn($q) => $q->whereDate('attendance_date', '>=', $dateFrom))
                    ->when($dateTo, fn($q) => $q->whereDate('attendance_date', '<=', $dateTo))
                )
                ->orderBy('id')
                ->chunk(200, function ($details) use ($handle) {
                    foreach ($details as $d) {
                        $this->csvRow($handle, [
                            $d->session->attendance_date->format('d/m/Y'),
                            $d->session->attendance_type === 'homeroom' ? 'Absensi Kelas' : 'Absensi Mengajar',
                            $d->session->schoolClass?->level?->name . ' ' . $d->session->schoolClass?->name ?? '-',
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

    public function grades(Request $request)
    {
        $yearId = $request->academic_year_id;
        $semesterId = $request->semester_id;
        $classId = $request->school_class_id;

        $filename = 'rekap-nilai.csv';

        return $this->streamCsv($filename, [
            'Kelas', 'Tahun Ajaran', 'Semester', 'Fan/Mapel',
            'Guru Pengampu', 'NIS', 'Nama Santri', 'Nilai',
            'Predikat', 'Keterangan', 'Status',
        ], function ($handle) use ($yearId, $semesterId, $classId) {
            Grade::with([
                'student',
                'teachingAssignment.subject',
                'teachingAssignment.teacher',
                'teachingAssignment.schoolClass.level',
                'teachingAssignment.academicYear',
                'teachingAssignment.semester',
            ])
                ->whereHas('teachingAssignment', fn($q) => $q
                    ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
                    ->when($semesterId, fn($q) => $q->where('semester_id', $semesterId))
                    ->when($classId, fn($q) => $q->where('school_class_id', $classId))
                )
                ->orderBy('id')
                ->chunk(200, function ($grades) use ($handle) {
                    foreach ($grades as $g) {
                        $this->csvRow($handle, [
                            $g->teachingAssignment?->schoolClass?->level?->name . ' ' . $g->teachingAssignment?->schoolClass?->name ?? '-',
                            $g->teachingAssignment?->academicYear?->name ?? '-',
                            $g->teachingAssignment?->semester?->name ?? '-',
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

    public function attitudes(Request $request)
    {
        $yearId = $request->academic_year_id;
        $semesterId = $request->semester_id;
        $classId = $request->school_class_id;

        $filename = 'rekap-sikap.csv';

        return $this->streamCsv($filename, [
            'Kelas', 'Tahun Ajaran', 'Semester', 'NIS',
            'Nama Santri', 'Akhlak', 'Kedisiplinan', 'Kebersihan', 'Catatan Sikap',
        ], function ($handle) use ($yearId, $semesterId, $classId) {
            Attitude::with(['student', 'schoolClass.level', 'academicYear', 'semester'])
                ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
                ->when($semesterId, fn($q) => $q->where('semester_id', $semesterId))
                ->when($classId, fn($q) => $q->where('school_class_id', $classId))
                ->orderBy('id')
                ->chunk(200, function ($attitudes) use ($handle) {
                    foreach ($attitudes as $a) {
                        $this->csvRow($handle, [
                            $a->schoolClass?->level?->name . ' ' . $a->schoolClass?->name ?? '-',
                            $a->academicYear?->name ?? '-',
                            $a->semester?->name ?? '-',
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
        $yearId = $request->academic_year_id;
        $semesterId = $request->semester_id;
        $classId = $request->school_class_id;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        $journalStatus = $request->status;

        $filename = 'rekap-jurnal.csv';

        return $this->streamCsv($filename, [
            'Tanggal', 'Jenis Jurnal', 'Kelas', 'Fan/Mapel',
            'Guru', 'NIS', 'Nama Santri', 'Isi Ringkas',
            'Predikat', 'Status',
        ], function ($handle) use ($yearId, $semesterId, $classId, $dateFrom, $dateTo, $journalStatus) {
            TeacherJournal::with([
                'teacher', 'teachingAssignment.subject',
                'schoolClass.level', 'student',
            ])
                ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
                ->when($semesterId, fn($q) => $q->where('semester_id', $semesterId))
                ->when($classId, fn($q) => $q->where('school_class_id', $classId))
                ->when($dateFrom, fn($q) => $q->whereDate('journal_date', '>=', $dateFrom))
                ->when($dateTo, fn($q) => $q->whereDate('journal_date', '<=', $dateTo))
                ->when($journalStatus, fn($q) => $q->where('status', $journalStatus))
                ->orderBy('journal_date')
                ->chunk(200, function ($journals) use ($handle) {
                    foreach ($journals as $j) {
                        $this->csvRow($handle, [
                            $j->journal_date->format('d/m/Y'),
                            $this->journalTypeLabel($j->journal_type),
                            $j->schoolClass?->level?->name . ' ' . $j->schoolClass?->name ?? '-',
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
