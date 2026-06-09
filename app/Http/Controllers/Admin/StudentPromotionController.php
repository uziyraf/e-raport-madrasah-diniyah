<?php

namespace App\Http\Controllers\Admin;

use App\Actions\StudentPromotionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ImportStudentPromotionRequest;
use App\Http\Requests\Admin\PreviewStudentPromotionRequest;
use App\Http\Requests\Admin\StoreStudentPromotionRequest;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentPromotionController extends Controller
{
    public function index(Request $request, StudentPromotionAction $action)
    {
        $data = $this->viewData();

        $data['results'] = null;
        $data['importItems'] = null;
        $data['activeTab'] = 'search';

        if ($request->hasFile('import_file') && $request->file('import_file')->isValid()) {
            $data['importItems'] = $action->parseImportForPreview($request->file('import_file'));
            $data['activeTab'] = 'import';
        }

        return view('admin.promotions.index', $data);
    }

    public function search(Request $request, StudentPromotionAction $action)
    {
        $request->validate([
            'source_academic_year_id' => ['nullable', 'exists:academic_years,id'],
            'source_semester_id' => ['nullable', 'exists:semesters,id'],
            'source_school_class_id' => ['nullable', 'exists:school_classes,id'],
            'keyword' => ['nullable', 'string', 'max:255'],
        ]);

        $hasAny = $request->filled('source_academic_year_id')
            || $request->filled('source_semester_id')
            || $request->filled('source_school_class_id')
            || $request->filled('keyword');

        if (!$hasAny) {
            return back()->withErrors(['search' => 'Isi minimal salah satu filter pencarian.']);
        }

        $results = $action->searchStudents(
            $request->source_academic_year_id,
            $request->source_semester_id,
            $request->source_school_class_id,
            $request->keyword,
        );

        $data = $this->viewData();
        $data['results'] = $results;
        $data['importItems'] = null;
        $data['activeTab'] = 'search';

        return view('admin.promotions.index', $data);
    }

    private function viewData(): array
    {
        $years = AcademicYear::orderByDesc('start_date')->get();
        $semesters = Semester::with('academicYear')->orderByDesc('start_date')->get();
        $classes = SchoolClass::active()->with('level')->orderBy('sort_order')->get();

        return compact('years', 'semesters', 'classes');
    }

    public function template()
    {
        $headers = ['NIS', 'Nama Santri', 'Kelas Tujuan', 'Status Penempatan', 'Catatan'];

        return response()->stream(function () use ($headers) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $headers, ';');
            fputcsv($handle, ['001', 'Ahmad Zainuddin', 'Ibtidaiyah 2 A', 'naik', 'Naik ke kelas 2'], ';');
            fputcsv($handle, ['002', 'Siti Aisyah', 'Ibtidaiyah 1 B', 'tetap', 'Tetap di kelas 1'], ';');
            fputcsv($handle, ['', 'Muhammad Farhan', 'Tsanawiyah 1 A', 'naik', 'NIS optional jika nama diisi'], ';');
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template-penempatan-santri.csv"',
        ]);
    }

    public function preview(PreviewStudentPromotionRequest $request, StudentPromotionAction $action)
    {
        $sourceYear = $request->source_academic_year_id ? AcademicYear::find($request->source_academic_year_id) : null;
        $sourceSemester = $request->source_semester_id ? Semester::find($request->source_semester_id) : null;
        $sourceClass = $request->source_school_class_id ? SchoolClass::with('level')->find($request->source_school_class_id) : null;
        $targetYear = AcademicYear::find($request->target_academic_year_id);
        $targetSemester = Semester::find($request->target_semester_id);
        $targetClass = $request->target_school_class_id ? SchoolClass::with('level')->find($request->target_school_class_id) : null;

        if ($request->mode === 'class') {
            $items = $action->resolveFromClass(
                $request->students ?? [],
                $request->source_academic_year_id,
                $request->source_semester_id,
                $request->source_school_class_id,
                $request->target_academic_year_id,
                $request->target_semester_id,
                $request->target_school_class_id,
                $request->placement_status,
            );
        } else {
            $items = $action->resolveBulkInput(
                $request->bulk_input ?? '',
                $request->target_academic_year_id,
                $request->target_semester_id,
                $request->target_school_class_id,
                $request->placement_status,
            );
        }

        $validCount = $items->where('validation_status', 'valid')->count();
        $warningCount = $items->where('validation_status', 'warning')->count();
        $errorCount = $items->where('validation_status', 'error')->count();

        return view('admin.promotions.preview', compact(
            'sourceYear', 'sourceSemester', 'sourceClass',
            'targetYear', 'targetSemester', 'targetClass',
            'items', 'validCount', 'warningCount', 'errorCount',
        ));
    }

    public function importPreview(ImportStudentPromotionRequest $request, StudentPromotionAction $action)
    {
        $sourceYear = $request->source_academic_year_id ? AcademicYear::find($request->source_academic_year_id) : null;
        $sourceSemester = $request->source_semester_id ? Semester::find($request->source_semester_id) : null;
        $sourceClass = $request->source_school_class_id ? SchoolClass::with('level')->find($request->source_school_class_id) : null;
        $targetYear = AcademicYear::find($request->target_academic_year_id);
        $targetSemester = Semester::find($request->target_semester_id);
        $targetClass = $request->target_school_class_id ? SchoolClass::with('level')->find($request->target_school_class_id) : null;

        $items = $action->parseImportFile(
            $request->file('import_file'),
            $request->target_academic_year_id,
            $request->target_semester_id,
            $request->target_school_class_id,
            $request->placement_status,
        );

        $validCount = $items->where('validation_status', 'valid')->count();
        $warningCount = $items->where('validation_status', 'warning')->count();
        $errorCount = $items->where('validation_status', 'error')->count();

        return view('admin.promotions.preview', compact(
            'sourceYear', 'sourceSemester', 'sourceClass',
            'targetYear', 'targetSemester', 'targetClass',
            'items', 'validCount', 'warningCount', 'errorCount',
        ));
    }

    public function store(StoreStudentPromotionRequest $request, StudentPromotionAction $action)
    {
        $action->execute(
            $request->students,
            $request->target_academic_year_id,
            $request->target_semester_id,
            $request->target_school_class_id,
            $request->placement_status,
        );

        $statusLabel = match ($request->placement_status) {
            'naik' => 'Kenaikan',
            'tetap' => 'Penempatan tetap',
            'pindah' => 'Pemindahan',
            'lulus' => 'Kelulusan',
            'keluar' => 'Pengeluaran',
            default => 'Penempatan',
        };

        return redirect()->route('admin.promotions.index')
            ->with('success', $statusLabel . ' santri berhasil diproses (' . count($request->students) . ' santri).');
    }

    private function setActiveTab(Request $request): string
    {
        if ($request->has('students')) {
            return 'class';
        }
        if ($request->has('bulk_input')) {
            return 'bulk';
        }
        return 'class';
    }
}
