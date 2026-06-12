<?php

namespace App\Actions;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentClassEnrollment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class StudentPromotionAction
{
    public function searchStudents(?int $academicYearId, ?int $semesterId, ?int $classId, ?string $keyword): Collection
    {
        $query = Student::with([
            'activeEnrollment.schoolClass.level',
            'activeEnrollment.academicYear',
            'activeEnrollment.semester',
            'classEnrollments.schoolClass.level',
            'classEnrollments.academicYear',
            'classEnrollments.semester',
        ])->where('status', 'active');

        if ($academicYearId || $semesterId || $classId) {
            $query->whereHas('classEnrollments', function ($q) use ($academicYearId, $semesterId, $classId) {
                $q->where('is_active', true);
                if ($academicYearId) $q->where('academic_year_id', $academicYearId);
                if ($semesterId) $q->where('semester_id', $semesterId);
                if ($classId) $q->where('school_class_id', $classId);
            });
        }

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nis', 'like', "%{$keyword}%")
                  ->orWhere('name', 'like', "%{$keyword}%");
            });
        }

        $students = $query->orderBy('name')->get();

        return $students->map(function ($student) {
            $e = $student->activeEnrollment;
            return (object) [
                'student' => $student,
                'nis' => $student->nis,
                'student_name' => $student->name,
                'current_class_label' => $e ? ($e->schoolClass?->level?->name . ' ' . $e->schoolClass?->name) : '-',
                'current_academic_year' => $e && $e->academicYear ? $e->academicYear->name : '-',
                'current_semester' => $e && $e->semester ? $e->semester->name : '-',
                'validation_status' => 'valid',
                'message' => '',
                'enrollments' => $student->classEnrollments->map(function ($enr) {
                    return (object) [
                        'class' => ($enr->schoolClass?->level?->name ?? '') . ' ' . ($enr->schoolClass?->name ?? '-'),
                        'academic_year' => $enr->academicYear?->name ?? '-',
                        'semester' => $enr->semester?->name ?? '-',
                        'is_active' => $enr->is_active,
                    ];
                }),
            ];
        });
    }

    public function searchBulkInput(string $input): Collection
    {
        $lines = preg_split('/[\r\n,]+/', $input);
        $lines = array_map('trim', $lines);
        $lines = array_filter($lines, fn($v) => $v !== '');

        $items = collect();
        $seenIds = [];

        foreach ($lines as $line) {
            $student = Student::where('nis', $line)->first();

            if (!$student) {
                $student = Student::where('name', $line)->first();
            }

            if (!$student) {
                $nameMatches = Student::where('name', 'like', "%{$line}%")->get();
                if ($nameMatches->count() === 1) {
                    $student = $nameMatches->first();
                } elseif ($nameMatches->count() > 1) {
                    $items->push($this->makeSearchItem(null, $line, $line, '-', 'warning', 'Nama cocok dengan ' . $nameMatches->count() . ' santri. Perlu cek NIS.'));
                    continue;
                } else {
                    $items->push($this->makeSearchItem(null, '-', $line, '-', 'error', 'NIS/Nama tidak ditemukan.'));
                    continue;
                }
            }

            if ($student->status !== 'active') {
                $items->push($this->makeSearchItem($student, $student->nis, $student->name, $this->currentClassLabel($student), 'error', 'Santri tidak aktif.'));
                continue;
            }

            if (in_array($student->id, $seenIds)) {
                $items->push($this->makeSearchItem($student, $student->nis, $student->name, $this->currentClassLabel($student), 'warning', 'Duplikat dalam input.'));
                continue;
            }

            $seenIds[] = $student->id;

            $student->loadMissing('activeEnrollment.schoolClass.level');
            $items->push($this->makeSearchItem($student, $student->nis, $student->name, $this->currentClassLabel($student), 'valid', 'Ditemukan.'));
        }

        return $items;
    }

    public function parseImportForPreview(UploadedFile $file): Collection
    {
        $handle = fopen($file->getRealPath(), 'r');
        $items = collect();
        $seenIds = [];
        $lineNum = 0;

        if (!$handle) {
            return collect();
        }

        while (($row = fgetcsv($handle, 0, ';')) !== false || ($row = fgetcsv($handle, 0, ',')) !== false) {
            $lineNum++;
            if ($lineNum === 1) {
                continue;
            }

            $nis = trim($row[0] ?? '');
            $nameFromFile = trim($row[1] ?? '');

            if (empty($nis) && empty($nameFromFile)) {
                continue;
            }

            $student = null;

            if (!empty($nis)) {
                $student = Student::where('nis', $nis)->first();
            }

            if (!$student && !empty($nameFromFile)) {
                $student = Student::where('name', $nameFromFile)->first();
            }

            if (!$student) {
                $displayName = $nameFromFile ?: $nis;
                $items->push($this->makeSearchItem(null, $nis ?: '-', $displayName, '-', 'error', 'NIS/Nama tidak ditemukan.'));
                continue;
            }

            if (!empty($nameFromFile) && $student->name !== $nameFromFile) {
                $items->push($this->makeSearchItem($student, $nis ?: $student->nis, $student->name, $this->currentClassLabel($student), 'warning', 'Nama tidak cocok dengan NIS.'));
                continue;
            }

            if (in_array($student->id, $seenIds)) {
                $items->push($this->makeSearchItem($student, $student->nis, $student->name, $this->currentClassLabel($student), 'warning', 'Duplikat dalam file.'));
                continue;
            }

            $seenIds[] = $student->id;

            $student->loadMissing('activeEnrollment.schoolClass.level');
            $items->push($this->makeSearchItem($student, $student->nis, $student->name, $this->currentClassLabel($student), 'valid', 'Ditemukan.'));
        }

        fclose($handle);
        return $items;
    }

    public function resolveFromClass(array $studentIds, ?int $sourceYearId, ?int $sourceSemesterId, ?int $sourceClassId, int $targetYearId, int $targetSemesterId, ?int $targetClassId, string $placementStatus): Collection
    {
        $students = Student::with(['activeEnrollment.schoolClass.level'])
            ->whereIn('id', $studentIds)
            ->where('status', 'active')
            ->get();

        $targetClass = $targetClassId ? SchoolClass::with('level')->find($targetClassId) : null;

        return $this->buildItems($students, $targetYearId, $targetSemesterId, $targetClass, $placementStatus);
    }

    public function resolveBulkInput(string $input, int $targetYearId, int $targetSemesterId, ?int $targetClassId, string $placementStatus): Collection
    {
        $lines = preg_split('/[\r\n,]+/', $input);
        $lines = array_map('trim', $lines);
        $lines = array_filter($lines, fn($v) => $v !== '');

        $targetClass = $targetClassId ? SchoolClass::with('level')->find($targetClassId) : null;
        $items = collect();
        $seenIds = [];

        foreach ($lines as $line) {
            $student = Student::where('nis', $line)->first();

            if (!$student) {
                $student = Student::where('name', $line)->first();
            }

            if (!$student) {
                $nameMatches = Student::where('name', 'like', "%{$line}%")->get();
                if ($nameMatches->count() === 1) {
                    $student = $nameMatches->first();
                } elseif ($nameMatches->count() > 1) {
                    $items->push($this->makeItem(null, $line, $line, '-', $targetClass?->level?->name . ' ' . $targetClass?->name ?? '-', $placementStatus, 'warning', 'Nama cocok dengan ' . $nameMatches->count() . ' santri. Perlu cek NIS.'));
                    continue;
                } else {
                    $items->push($this->makeItem(null, '-', $line, '-', $targetClass?->level?->name . ' ' . $targetClass?->name ?? '-', $placementStatus, 'error', 'NIS/Nama tidak ditemukan.'));
                    continue;
                }
            }

            if ($student->status !== 'active') {
                $items->push($this->makeItem($student, $student->nis, $student->name, $this->currentClassLabel($student), $targetClass?->level?->name . ' ' . $targetClass?->name ?? '-', $placementStatus, 'error', 'Santri tidak aktif.'));
                continue;
            }

            if (in_array($student->id, $seenIds)) {
                $items->push($this->makeItem($student, $student->nis, $student->name, $this->currentClassLabel($student), $targetClass?->level?->name . ' ' . $targetClass?->name ?? '-', $placementStatus, 'error', 'Duplikat dalam input.'));
                continue;
            }

            $seenIds[] = $student->id;
        }

        $foundStudents = Student::whereIn('id', $seenIds)->with(['activeEnrollment.schoolClass.level'])->get()->keyBy('id');

        return $items->map(function ($item) use ($foundStudents, $targetYearId, $targetSemesterId, $targetClass, $placementStatus) {
            if ($item->student && $item->validation_status !== 'error') {
                $student = $foundStudents->get($item->student->id);
                if ($student) {
                    $item->student = $student;
                    $item->current_class_label = $this->currentClassLabel($student);
                }
                return $this->applyPlacementConflict($item, $student, $targetYearId, $targetSemesterId, $targetClass, $placementStatus);
            }
            return $item;
        });
    }

    public function parseImportFile(UploadedFile $file, int $targetYearId, int $targetSemesterId, ?int $targetClassId, string $placementStatus): Collection
    {
        $handle = fopen($file->getRealPath(), 'r');
        $items = collect();
        $seenIds = [];
        $lineNum = 0;

        if (!$handle) {
            return collect();
        }

        while (($row = fgetcsv($handle, 0, ';')) !== false || ($row = fgetcsv($handle, 0, ',')) !== false) {
            $lineNum++;
            if ($lineNum === 1) {
                continue;
            }

            $nis = trim($row[0] ?? '');
            $nameFromFile = trim($row[1] ?? '');
            $classFromFile = trim($row[2] ?? '');
            $statusFromFile = trim($row[3] ?? '');

            if (empty($nis) && empty($nameFromFile)) {
                continue;
            }

            $placementStatusToUse = !empty($statusFromFile) ? $statusFromFile : $placementStatus;

            if (!in_array($placementStatusToUse, ['naik', 'tetap', 'pindah', 'lulus', 'keluar'])) {
                $items->push($this->makeItem(null, $nis ?: '-', $nameFromFile ?: '-', '-', $classFromFile ?: '-', $placementStatusToUse, 'error', 'Status penempatan tidak valid.'));
                continue;
            }

            $student = null;
            $resolveClass = $targetClassId ? SchoolClass::with('level')->find($targetClassId) : null;

            if (!empty($nis)) {
                $student = Student::where('nis', $nis)->first();
            }

            if (!$student && !empty($nameFromFile)) {
                $student = Student::where('name', $nameFromFile)->first();
            }

            if (!$student) {
                $items->push($this->makeItem(null, $nis ?: '-', $nameFromFile ?: '-', '-', $classFromFile ?: '-', $placementStatusToUse, 'error', 'NIS/Nama tidak ditemukan.'));
                continue;
            }

            if (!empty($nameFromFile) && $student->name !== $nameFromFile) {
                $items->push($this->makeItem($student, $nis ?: $student->nis, $student->name, $this->currentClassLabel($student), $classFromFile ?: '-', $placementStatusToUse, 'warning', 'Nama tidak cocok dengan NIS.'));
                continue;
            }

            if (in_array($student->id, $seenIds)) {
                $items->push($this->makeItem($student, $student->nis, $student->name, $this->currentClassLabel($student), $classFromFile ?: '-', $placementStatusToUse, 'error', 'Duplikat dalam file.'));
                continue;
            }

            $seenIds[] = $student->id;
        }

        fclose($handle);

        $foundStudents = Student::whereIn('id', $seenIds)->with(['activeEnrollment.schoolClass.level'])->get()->keyBy('id');

        return $items->map(function ($item) use ($foundStudents, $targetYearId, $targetSemesterId, $resolveClass, $placementStatus) {
            if ($item->student && $item->validation_status !== 'error') {
                $student = $foundStudents->get($item->student->id);
                if ($student) {
                    $item->student = $student;
                    $item->current_class_label = $this->currentClassLabel($student);
                    $item->target_class_label = $resolveClass ? ($resolveClass->level->name . ' ' . $resolveClass->name) : ($item->target_class_label ?: '-');
                }
                return $this->applyPlacementConflict($item, $student, $targetYearId, $targetSemesterId, $resolveClass, $item->placement_status);
            }
            return $item;
        });
    }

    public function execute(array $studentIds, int $targetYearId, int $targetSemesterId, ?int $targetClassId, string $placementStatus): void
    {
        $userId = auth()->id();

        foreach ($studentIds as $studentId) {
            StudentClassEnrollment::where('student_id', $studentId)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            if (in_array($placementStatus, ['lulus', 'keluar'])) {
                Student::where('id', $studentId)->update([
                    'status' => $placementStatus === 'lulus' ? 'graduate' : 'inactive',
                ]);

                if ($placementStatus === 'lulus' && $targetClassId) {
                    $this->createOrUpdateEnrollment($studentId, $targetClassId, $targetYearId, $targetSemesterId, $userId);
                }

                continue;
            }

            if ($targetClassId) {
                $this->createOrUpdateEnrollment($studentId, $targetClassId, $targetYearId, $targetSemesterId, $userId);
            }
        }
    }

    private function createOrUpdateEnrollment(int $studentId, int $classId, int $yearId, int $semesterId, int $userId): void
    {
        $enrollment = StudentClassEnrollment::where('student_id', $studentId)
            ->where('academic_year_id', $yearId)
            ->where('semester_id', $semesterId)
            ->first();

        if ($enrollment) {
            $enrollment->update([
                'school_class_id' => $classId,
                'is_active' => true,
                'enrollment_status' => 'active',
            ]);
        } else {
            StudentClassEnrollment::create([
                'student_id' => $studentId,
                'school_class_id' => $classId,
                'academic_year_id' => $yearId,
                'semester_id' => $semesterId,
                'enrollment_status' => 'active',
                'is_active' => true,
                'created_by' => $userId,
            ]);
        }
    }

    private function buildItems(Collection $students, int $targetYearId, int $targetSemesterId, ?SchoolClass $targetClass, string $placementStatus): Collection
    {
        return $students->map(function ($student) use ($targetYearId, $targetSemesterId, $targetClass, $placementStatus) {
            $item = $this->makeItem(
                $student,
                $student->nis,
                $student->name,
                $this->currentClassLabel($student),
                $targetClass ? ($targetClass->level->name . ' ' . $targetClass->name) : '-',
                $placementStatus,
                'valid',
                ''
            );
            return $this->applyPlacementConflict($item, $student, $targetYearId, $targetSemesterId, $targetClass, $placementStatus);
        });
    }

    private function applyPlacementConflict($item, $student, int $targetYearId, int $targetSemesterId, ?SchoolClass $targetClass, string $placementStatus): object
    {
        if ($item->validation_status === 'error') {
            return $item;
        }

        $enrollment = $student?->activeEnrollment;

        if ($student->status !== 'active') {
            $item->validation_status = 'error';
            $item->message = 'Santri tidak aktif.';
            return $item;
        }

        if (in_array($placementStatus, ['naik', 'tetap', 'pindah']) && !$targetClass) {
            $item->validation_status = 'error';
            $item->message = 'Kelas tujuan wajib diisi untuk status ini.';
            return $item;
        }

        if (!$enrollment) {
            $item->message = 'Tidak ada kelas aktif.';
            return $item;
        }

        if ($enrollment->academic_year_id === $targetYearId && $enrollment->semester_id === $targetSemesterId) {
            if ($targetClass && $enrollment->school_class_id === $targetClass->id) {
                $item->validation_status = 'warning';
                $item->message = 'Sama dengan kelas saat ini.';
                return $item;
            }

            $item->validation_status = 'warning';
            $item->message = 'Sudah ditempatkan.';
            return $item;
        }

        if ($targetClass && $this->hasTargetClassEnrollment($student->id, $targetYearId, $targetSemesterId, $targetClass->id)) {
            $item->validation_status = 'warning';
            $item->message = 'Sudah di kelas target.';
            return $item;
        }

        $item->validation_status = 'valid';
        $item->message = 'Aman.';
        return $item;
    }

    private function hasTargetClassEnrollment(int $studentId, int $yearId, int $semesterId, int $classId): bool
    {
        return StudentClassEnrollment::where('student_id', $studentId)
            ->where('academic_year_id', $yearId)
            ->where('semester_id', $semesterId)
            ->where('school_class_id', $classId)
            ->exists();
    }

    private function currentClassLabel(Student $student): string
    {
        $e = $student->activeEnrollment;
        if (!$e) {
            return '-';
        }
        return ($e->schoolClass?->level?->name ?? '') . ' ' . ($e->schoolClass?->name ?? '-');
    }

    private function makeSearchItem($student, string $nis, string $name, string $currentClass, string $validationStatus, string $message): object
    {
        return (object) [
            'student' => $student,
            'nis' => $nis,
            'student_name' => $name,
            'current_class_label' => $currentClass,
            'validation_status' => $validationStatus,
            'message' => $message,
        ];
    }

    private function makeItem($student, string $nis, string $name, string $currentClass, string $targetClass, string $placementStatus, string $validationStatus, string $message): object
    {
        return (object) [
            'student' => $student,
            'nis' => $nis,
            'student_name' => $name,
            'current_class_label' => $currentClass,
            'target_class_label' => $targetClass,
            'placement_status' => $placementStatus,
            'validation_status' => $validationStatus,
            'message' => $message,
        ];
    }
}
