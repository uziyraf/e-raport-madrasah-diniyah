<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentClassEnrollment;
use Illuminate\Http\Request;

class StudentPlacementController extends Controller
{
    private const SESSION_KEY = 'placement_selected_students';

    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $filterType = $request->filter_type ?: 'all';
        $page = $request->page ?: 1;

        $selectedIds = session()->get(self::SESSION_KEY, []);

        $query = Student::with(['activeEnrollment.schoolClass.level'])
            ->where('status', 'active');

        if (!empty($selectedIds)) {
            $query->whereNotIn('id', $selectedIds);
        }

        if ($filterType === 'no_class') {
            $query->whereDoesntHave('activeEnrollment');
        } elseif ($filterType === 'has_class') {
            $query->whereHas('activeEnrollment');
        }

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nis', 'like', "%{$keyword}%")
                  ->orWhere('name', 'like', "%{$keyword}%");
            });
        }

        $students = $query->orderBy('name')->paginate(20)->withQueryString();

        $results = $students->map(function ($student) {
            $e = $student->activeEnrollment;
            return (object) [
                'student' => $student,
                'nis' => $student->nis,
                'student_name' => $student->name,
                'current_class_label' => $e ? ($e->schoolClass?->level?->name . ' ' . $e->schoolClass?->name) : '-',
            ];
        });

        $selectedStudents = $this->getSelectedStudents();

        $years = AcademicYear::orderByDesc('start_date')->get();
        $semesters = Semester::with('academicYear')->orderByDesc('start_date')->get();
        $classes = SchoolClass::active()->with('level')->orderBy('sort_order')->get();

        return view('admin.placements.index', compact(
            'results', 'students', 'selectedStudents',
            'years', 'semesters', 'classes',
            'keyword', 'filterType',
        ));
    }

    public function addStudent(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
        ]);

        $selected = session()->get(self::SESSION_KEY, []);
        $studentId = (int) $request->student_id;

        if (!in_array($studentId, $selected)) {
            $selected[] = $studentId;
            session()->put(self::SESSION_KEY, $selected);
        }

        return redirect($this->buildSearchUrl($request));
    }

    public function removeStudent(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
        ]);

        $selected = session()->get(self::SESSION_KEY, []);
        $selected = array_values(array_filter($selected, fn($id) => (int) $id !== (int) $request->student_id));

        session()->put(self::SESSION_KEY, $selected);

        return redirect($this->buildSearchUrl($request));
    }

    public function clearStudents(Request $request)
    {
        session()->forget(self::SESSION_KEY);

        return redirect($this->buildSearchUrl($request));
    }

    public function store(Request $request)
    {
        $request->validate([
            'target_academic_year_id' => ['required', 'exists:academic_years,id'],
            'target_semester_id' => ['required', 'exists:semesters,id'],
            'target_school_class_id' => ['nullable', 'required_if:placement_status,naik', 'required_if:placement_status,tetap', 'required_if:placement_status,pindah', 'exists:school_classes,id'],
            'placement_status' => ['required', 'string', 'in:naik,tetap,pindah,lulus,keluar'],
            'students' => ['required', 'array', 'min:1'],
            'students.*' => ['required', 'exists:students,id'],
        ]);

        $selectedIds = $request->students;

        $targetYearId = (int) $request->target_academic_year_id;
        $targetSemesterId = (int) $request->target_semester_id;
        $targetClassId = $request->target_school_class_id ? (int) $request->target_school_class_id : null;
        $placementStatus = $request->placement_status;
        $userId = auth()->id();

        $processed = 0;
        $skipped = 0;
        $warnings = [];

        foreach ($selectedIds as $studentId) {
            $existing = StudentClassEnrollment::where('student_id', $studentId)
                ->where('academic_year_id', $targetYearId)
                ->where('semester_id', $targetSemesterId)
                ->first();

            if ($existing) {
                $student = Student::find($studentId);
                $warnings[] = "{$student->nis} - {$student->name} sudah memiliki kelas pada tahun ajaran/semester tujuan.";
                $skipped++;
                continue;
            }

            if (in_array($placementStatus, ['lulus', 'keluar'])) {
                Student::where('id', $studentId)->update([
                    'status' => $placementStatus === 'lulus' ? 'graduate' : 'inactive',
                ]);

                if ($placementStatus === 'lulus' && $targetClassId) {
                    StudentClassEnrollment::create([
                        'student_id' => $studentId,
                        'school_class_id' => $targetClassId,
                        'academic_year_id' => $targetYearId,
                        'semester_id' => $targetSemesterId,
                        'enrollment_status' => 'active',
                        'is_active' => true,
                        'created_by' => $userId,
                    ]);
                }

                $processed++;
                continue;
            }

            if ($targetClassId) {
                StudentClassEnrollment::create([
                    'student_id' => $studentId,
                    'school_class_id' => $targetClassId,
                    'academic_year_id' => $targetYearId,
                    'semester_id' => $targetSemesterId,
                    'enrollment_status' => 'active',
                    'is_active' => true,
                    'created_by' => $userId,
                ]);
                $processed++;
            }
        }

        session()->forget(self::SESSION_KEY);

        $statusLabel = match ($placementStatus) {
            'naik' => 'Kenaikan',
            'tetap' => 'Penempatan tetap',
            'pindah' => 'Pemindahan',
            'lulus' => 'Kelulusan',
            'keluar' => 'Pengeluaran',
            default => 'Penempatan',
        };

        $message = "{$statusLabel} santri berhasil diproses ({$processed} santri).";
        if ($skipped > 0) {
            $message .= " {$skipped} santri dilewati karena sudah memiliki kelas tujuan.";
        }

        if (!empty($warnings)) {
            return redirect()->route('admin.placements.index')
                ->with('warning_details', $warnings)
                ->with('success', $message);
        }

        return redirect()->route('admin.placements.index')
            ->with('success', $message);
    }

    private function buildSearchUrl(Request $request): string
    {
        $params = array_filter([
            'keyword' => $request->keyword,
            'filter_type' => $request->filter_type,
            'page' => $request->page,
        ], fn($v) => $v !== null && $v !== '');

        if (empty($params)) {
            return route('admin.placements.index');
        }

        return route('admin.placements.index') . '?' . http_build_query($params);
    }

    private function getSelectedStudents(): \Illuminate\Support\Collection
    {
        $ids = session()->get(self::SESSION_KEY, []);

        if (empty($ids)) {
            return collect();
        }

        return Student::whereIn('id', $ids)
            ->with(['activeEnrollment.schoolClass.level'])
            ->orderBy('name')
            ->get()
            ->map(function ($student) {
                $e = $student->activeEnrollment;
                return (object) [
                    'student' => $student,
                    'nis' => $student->nis,
                    'student_name' => $student->name,
                    'current_class_label' => $e ? ($e->schoolClass?->level?->name . ' ' . $e->schoolClass?->name) : '-',
                ];
            });
    }
}
