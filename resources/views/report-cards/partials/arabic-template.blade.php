@php
    $student = $reportData['student'];
    $schoolClass = $reportData['schoolClass'];
    $academicYear = $reportData['academicYear'];
    $semester = $reportData['semester'];
    $semesterArabic = $reportData['semesterArabic'] ?? $semester->name;
    $hijriYear = $reportData['hijriYear'] ?? '-';
    $masehiYear = $reportData['masehiYear'] ?? $academicYear->name;
    $subjectRows = collect($reportData['subjectRows'] ?? [])->values();
    $totalScore = $reportData['totalScore'] ?? '-';
    $average = $reportData['average'] ?? '-';
    $rank = $reportData['rank'] ?? '-';
    $attitude = $reportData['attitude'] ?? null;
    $permissionCount = $reportData['permissionCount'] ?? 0;
    $sickCount = $reportData['sickCount'] ?? 0;
    $absentCount = $reportData['absentCount'] ?? 0;
    $attendanceDays = $reportData['attendanceDays'] ?? 0;
    $principal = $reportData['principal'] ?? null;
    $homeroom = $reportData['homeroomTeacher'] ?? null;

    $studentArabicName = $student->arabic_name ?: $student->name;
    $className = trim(($schoolClass->level->name ?? '') . ' ' . $schoolClass->name);
    $principalName = $principal?->arabic_name ?: 'Nama Arab tidak ditemukan';
    $homeroomName = $homeroom?->arabic_name ?: 'Nama Arab tidak ditemukan';

    $isMale = ($student->gender ?? null) === 'male';
    $studentLabel = $isMale ? 'اسم الطالب' : 'اسم الطالبة';
    $scoreHeading = $isMale ? 'الدرجة التي نالها الطالب' : 'الدرجة التي نالتها الطالبة';
    $rankLabel = $isMale ? 'الفائز' : 'الفائزة';

    $dash = fn($v) => ($v === null || $v === '' || $v === '-') ? 'ـ' : $v;

    // Ubah angka latin menjadi angka Arab (١٢٣)
    $ar = fn($v) => strtr((string) $v, [
        '0' => '٠',
        '1' => '١',
        '2' => '٢',
        '3' => '٣',
        '4' => '٤',
        '5' => '٥',
        '6' => '٦',
        '7' => '٧',
        '8' => '٨',
        '9' => '٩',
    ]);
    // Rapikan format tahun: "2026/2027" -> "٢٠٢٦ - ٢٠٢٧"
    $year = fn($v) => $ar(preg_replace('/\s*[\/\-]\s*/', ' - ', (string) $v));

    // Template memiliki 18 baris mata pelajaran
    $minRows = 18;
    $totalRows = max($minRows, $subjectRows->count());
@endphp

<div class="arabic-sheet" dir="rtl" lang="ar">
    <style>
        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        .arabic-sheet {
            width: 100%;
            max-width: 194mm;
            margin: 0 auto;
            padding: 8mm 0;
            background: #fff;
            color: #000;
            direction: rtl;
            font-family: 'Noto Naskh Arabic', 'Amiri', 'Traditional Arabic', serif;
            font-size: 11pt;
            line-height: 1.25;
        }

        .arabic-sheet * {
            box-sizing: border-box;
        }

        .arabic-sheet table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .arabic-sheet td,
        .arabic-sheet th {
            border: 1px solid #000;
            padding: 0 2mm;
            font-weight: normal;
            vertical-align: middle;
            text-align: right;
        }

        .arabic-sheet th {
            font-weight: bold;
            text-align: center;
        }

        .arabic-sheet .center {
            text-align: center;
        }

        .arabic-sheet .bold {
            font-weight: bold;
        }

        /* ===== Tinggi baris (sesuai template) ===== */
        .arabic-sheet .r-id {
            height: 8.2mm;
        }

        .arabic-sheet .r-head1 {
            height: 7.8mm;
        }

        .arabic-sheet .r-head2 {
            height: 7mm;
        }

        .arabic-sheet .r-subj {
            height: 7.35mm;
        }

        .arabic-sheet .r-sum {
            height: 7mm;
        }

        .arabic-sheet .r-foot {
            height: 26.8mm;
        }

        /* ===== Header atas ===== */
        .arabic-sheet .year-cell {
            text-align: center;
            line-height: 1.6;
        }

        .arabic-sheet .year-cell div {
            font-weight: bold;
        }

        /* ===== Tabel nilai ===== */
        .arabic-sheet .subject-name {
            text-align: right;
            padding-right: 3mm;
        }

        .arabic-sheet .score {
            text-align: center;
            font-size: 10pt;
        }

        /* Kotak tanda tangan wali kelas (digabung 18 baris) */
        .arabic-sheet .sign-cell {
            text-align: center;
            vertical-align: middle;
        }

        .arabic-sheet .sign-title {
            font-size: 13pt;
            font-weight: bold;
            line-height: 1.2;
        }

        .arabic-sheet .sign-sub {
            font-size: 11pt;
            line-height: 1.2;
        }

        .arabic-sheet .sign-line {
            width: 75%;
            margin: 1mm auto 0;
            border-bottom: 1px solid #000;
        }

        /* ===== Ringkasan ===== */
        .arabic-sheet .label {
            text-align: right;
            padding-right: 3mm;
        }

        .arabic-sheet .group {
            text-align: center;
            font-size: 10pt;
        }

        .arabic-sheet .note-cell {
            vertical-align: top;
            text-align: center;
            padding-top: 2mm;
        }

        .arabic-sheet .note-text {
            margin-top: 2mm;
            font-size: 10pt;
        }

        .arabic-sheet .days-cell {
            text-align: center;
        }

        /* ===== Footer ===== */
        .arabic-sheet .principal-cell {
            text-align: center;
            vertical-align: top;
            padding-top: 4mm;
            padding-bottom: 2mm;
        }

        .arabic-sheet .principal-name {
            margin-top: 12mm;
        }

        @media print {
            .arabic-sheet {
                width: 100% !important;
                max-width: none !important;
                padding: 0 !important;
                box-shadow: none !important;
            }

            .arabic-sheet tr {
                page-break-inside: avoid;
            }
        }
    </style>

    <table>
        <colgroup>
            <col style="width: 8.4%"> {{-- رقم --}}
            <col style="width: 30.6%"> {{-- المواد --}}
            <col style="width: 14.7%"> {{-- يومية --}}
            <col style="width: 14.6%"> {{-- امتحانية --}}
            <col style="width: 31.7%"> {{-- التوقيع --}}
        </colgroup>

        {{-- ============ HEADER IDENTITAS ============ --}}
        <tr class="r-id">
            <td colspan="4"><span class="bold">{{ $studentLabel }}:</span> {{ $studentArabicName }}</td>
            <td rowspan="3" class="year-cell">
                <div>السنة الدراسية</div>
                <div>{{ $year($hijriYear) }}</div>
                <div>{{ $year($masehiYear) }}</div>
            </td>
        </tr>
        <tr class="r-id">
            <td colspan="4"><span class="bold">القسم:</span> {{ $className }}</td>
        </tr>
        <tr class="r-id">
            <td colspan="4">{{ $semesterArabic }}</td>
        </tr>

        {{-- ============ HEADER TABEL NILAI ============ --}}
        <tr class="r-head1">
            <th rowspan="2">رقم</th>
            <th rowspan="2">المواد</th>
            <th colspan="2">{{ $scoreHeading }}</th>
            <th rowspan="2">التوقيع</th>
        </tr>
        <tr class="r-head2">
            <th>يومية</th>
            <th>امتحانية</th>
        </tr>

        {{-- ============ BARIS MATA PELAJARAN ============ --}}
        @for ($i = 0; $i < $totalRows; $i++)
            @php $row = $subjectRows->get($i); @endphp
            <tr class="r-subj">
                <td class="center">{{ $ar($row['number'] ?? ($i + 1)) }}</td>
                <td class="subject-name">{{ $dash($row['arabic_name'] ?? null) }}</td>
                <td class="score">{{ $dash($row['daily_score'] ?? null) }}</td>
                <td class="score">{{ $dash($row['exam_score'] ?? null) }}</td>
                @if ($i === 0)
                    <td class="sign-cell" rowspan="{{ $totalRows }}">
                        <div class="sign-title">{{ $homeroomName }}</div>
                        <div class="sign-sub">الأستاذ / الأستاذة</div>
                        <div class="sign-sub">الولى</div>
                        <div class="sign-line"></div>
                    </td>
                @endif
            </tr>
        @endfor

        {{-- ============ KEPRIBADIAN + NILAI AKHIR ============ --}}
        <tr class="r-sum">
            <td rowspan="3" class="group">الشخصية</td>
            <td class="label">النظافة</td>
            <td colspan="2" class="center">{{ $attitude?->cleanliness }}</td>
            <td rowspan="6" class="note-cell">
                <div class="bold">العظة :</div>
                <div class="note-text">{{ $attitude?->attitude_note }}</div>
            </td>
        </tr>
        <tr class="r-sum">
            <td class="label">الأخلاق</td>
            <td colspan="2" class="center">{{ $attitude?->akhlak }}</td>
        </tr>
        <tr class="r-sum">
            <td class="label">المواظبة</td>
            <td colspan="2" class="center">{{ $attitude?->discipline }}</td>
        </tr>
        <tr class="r-sum">
            <td></td>
            <td class="label">مجموع الدرجات</td>
            <td colspan="2" class="center">{{ $dash($totalScore) }}</td>
        </tr>
        <tr class="r-sum">
            <td></td>
            <td class="label">نتيجة المعدلة</td>
            <td colspan="2" class="center">{{ $dash($average) }}</td>
        </tr>
        <tr class="r-sum">
            <td></td>
            <td class="label">{{ $rankLabel }}</td>
            <td colspan="2" class="center">{{ $dash($rank) }}</td>
        </tr>

        {{-- ============ KETIDAKHADIRAN ============ --}}
        <tr class="r-sum">
            <td rowspan="3" class="group">عدم الحضور</td>
            <td class="label">بمرض</td>
            <td colspan="2" class="center">{{ $ar($sickCount) }}</td>
            <td rowspan="3" class="days-cell">
                الأيام
                @if ($attendanceDays)
                    <div class="bold">{{ $ar($attendanceDays) }}</div>
                @endif
            </td>
        </tr>
        <tr class="r-sum">
            <td class="label">بعذر</td>
            <td colspan="2" class="center">{{ $ar($permissionCount) }}</td>
        </tr>
        <tr class="r-sum">
            <td class="label">بغير عذر</td>
            <td colspan="2" class="center">{{ $ar($absentCount) }}</td>
        </tr>

        {{-- ============ FOOTER ============ --}}
        <tr class="r-foot">
            <td colspan="3"></td>
            <td colspan="2" class="principal-cell">
                <div>المدير</div>
                <div class="principal-name">( {{ $principalName }} )</div>
            </td>
        </tr>
    </table>
</div>