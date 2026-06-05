<?php

namespace App\Traits;

trait CsvExportable
{
    protected function streamCsv(string $filename, array $headers, callable $dataGenerator)
    {
        return response()->stream(function () use ($headers, $dataGenerator) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            $this->csvRow($handle, $headers);
            $dataGenerator($handle);
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function csvRow($handle, array $row): void
    {
        fputcsv($handle, $row, ';');
    }

    private function genderLabel(?string $gender): string
    {
        return match ($gender) {
            'male' => 'Laki-laki',
            'female' => 'Perempuan',
            default => '-',
        };
    }

    private function statusLabel(?string $status): string
    {
        return match ($status) {
            'active' => 'Aktif',
            'inactive' => 'Nonaktif',
            default => $status ?? '-',
        };
    }

    private function attendanceStatusLabel(?string $status): string
    {
        return match ($status) {
            'present' => 'Hadir',
            'permission' => 'Izin',
            'sick' => 'Sakit',
            'absent' => 'Alfa',
            default => $status ?? '-',
        };
    }

    private function journalTypeLabel(?string $type): string
    {
        return match ($type) {
            'memorization' => 'Hafalan',
            'kitab_legalization' => 'Legalisir Kitab',
            'daily_score' => 'Nilai Harian',
            'exam_score' => 'Tamrinan',
            'note' => 'Catatan',
            default => $type ?? '-',
        };
    }

    private function journalContentSummary($journal): string
    {
        return match ($journal->journal_type) {
            'memorization' => ($journal->memorization_type ?? '') . ' - Target: ' . ($journal->memorization_target ?? '-') . ' | Capaian: ' . ($journal->memorization_result ?? '-'),
            'kitab_legalization' => ($journal->kitab_name ?? '') . ' - Hal. ' . ($journal->kitab_page ?? '-') . ' | Status: ' . ($journal->legalization_status ?? '-'),
            'daily_score' => 'Nilai: ' . ($journal->daily_score ?? '-'),
            'exam_score' => 'Nilai: ' . ($journal->exam_score ?? '-'),
            'note' => $journal->note ?? '-',
            default => $journal->note ?? '-',
        };
    }
}
