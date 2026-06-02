@php
    $student = $reportData['student'];
    $schoolClass = $reportData['schoolClass'];
    $academicYear = $reportData['academicYear'];
    $semester = $reportData['semester'];
    $subjectRows = $reportData['subjectRows'];
    $totalScore = $reportData['totalScore'];
    $average = $reportData['average'];
    $attitude = $reportData['attitude'];
    $permissionCount = $reportData['permissionCount'];
    $sickCount = $reportData['sickCount'];
    $absentCount = $reportData['absentCount'];
@endphp

<div class="arabic-report" style="direction: rtl; font-family: 'Traditional Arabic', 'Amiri', 'Scheherazade New', serif; font-size: 14px; line-height: 1.6; color: #000; width: 210mm; margin: 0 auto; padding: 15mm 10mm;">

    <table style="width: 100%; border-collapse: collapse; border: 2px solid #000; margin-bottom: 0;">
        <tr>
            <td colspan="2" style="border: 2px solid #000; padding: 12px 16px; text-align: center;">
                <div style="font-size: 22px; font-weight: bold;">بطاقة التقرير</div>
                <div style="font-size: 16px; margin-top: 4px;">RAPORT SANTRI</div>
                <div style="font-size: 12px; margin-top: 2px; color: #333;">
                    {{ $academicYear->name }} - {{ $semester->name }}
                </div>
            </td>
        </tr>
        <tr>
            <td style="width: 50%; border: 2px solid #000; padding: 8px 12px; vertical-align: top;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 3px 0; font-weight: bold; width: 100px;">اسم الطالب</td>
                        <td style="padding: 3px 0;">: {{ $student->arabic_name ?? $student->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 0; font-weight: bold;">القسم</td>
                        <td style="padding: 3px 0;">: {{ $schoolClass->level->name ?? '' }} {{ $schoolClass->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 0; font-weight: bold;">السنة</td>
                        <td style="padding: 3px 0;">: {{ $academicYear->name }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; border: 2px solid #000; padding: 8px 12px; text-align: right; vertical-align: top;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 3px 0; font-weight: bold; width: 100px;">Nama</td>
                        <td style="padding: 3px 0;">: {{ $student->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 0; font-weight: bold;">NIS</td>
                        <td style="padding: 3px 0;">: {{ $student->nis }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 3px 0; font-weight: bold;">Kelas</td>
                        <td style="padding: 3px 0;">: {{ $schoolClass->level->name ?? '' }} {{ $schoolClass->name }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; border: 2px solid #000; border-top: none;">
        <thead>
            <tr style="background-color: #e5e7eb;">
                <th style="border: 2px solid #000; padding: 8px 6px; text-align: center; font-weight: bold; width: 40px;">رقم</th>
                <th style="border: 2px solid #000; padding: 8px 6px; text-align: center; font-weight: bold;">المواد</th>
                <th style="border: 2px solid #000; padding: 8px 6px; text-align: center; font-weight: bold;" colspan="2">الدرجة التي نالها الطالب</th>
            </tr>
            <tr style="background-color: #e5e7eb;">
                <th style="border: 2px solid #000; padding: 4px 6px; text-align: center;"></th>
                <th style="border: 2px solid #000; padding: 4px 6px; text-align: center;"></th>
                <th style="border: 2px solid #000; padding: 4px 6px; text-align: center; font-weight: bold; width: 70px;">يومية</th>
                <th style="border: 2px solid #000; padding: 4px 6px; text-align: center; font-weight: bold; width: 80px;">امتحانية</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($subjectRows as $row)
                <tr>
                    <td style="border: 2px solid #000; padding: 6px; text-align: center;">{{ $row['number'] }}</td>
                    <td style="border: 2px solid #000; padding: 6px 10px; text-align: right;">{{ $row['arabic_name'] }}</td>
                    <td style="border: 2px solid #000; padding: 6px; text-align: center;">{{ $row['daily_score'] }}</td>
                    <td style="border: 2px solid #000; padding: 6px; text-align: center;">{{ $row['exam_score'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="border: 2px solid #000; padding: 10px; text-align: center; color: #666;">
                        Tidak ada data nilai
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table style="width: 100%; border-collapse: collapse; border: 2px solid #000; border-top: none;">
        <tr>
            <td style="border: 2px solid #000; padding: 6px 10px; text-align: right; font-weight: bold; width: 120px;">مجموع الدرجات</td>
            <td style="border: 2px solid #000; padding: 6px 10px; text-align: center; width: 80px;">{{ $totalScore }}</td>
            <td style="border: 2px solid #000; padding: 6px 10px; text-align: right; font-weight: bold; width: 120px;">المواظبة</td>
            <td style="border: 2px solid #000; padding: 6px 10px; text-align: center;">{{ $attitude->discipline ?? '-' }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #000; padding: 6px 10px; text-align: right; font-weight: bold;">نتيجة المعدلة</td>
            <td style="border: 2px solid #000; padding: 6px 10px; text-align: center;">{{ $average }}</td>
            <td style="border: 2px solid #000; padding: 6px 10px; text-align: right; font-weight: bold;">الأخلاق</td>
            <td style="border: 2px solid #000; padding: 6px 10px; text-align: center;">{{ $attitude->akhlak ?? '-' }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #000; padding: 6px 10px; text-align: right; font-weight: bold;">الفائز</td>
            <td style="border: 2px solid #000; padding: 6px 10px; text-align: center;">-</td>
            <td style="border: 2px solid #000; padding: 6px 10px; text-align: right; font-weight: bold;">النظافة</td>
            <td style="border: 2px solid #000; padding: 6px 10px; text-align: center;">{{ $attitude->cleanliness ?? '-' }}</td>
        </tr>
        <tr>
            <td style="border: 2px solid #000; padding: 6px 10px; text-align: right; font-weight: bold;">الغياب</td>
            <td style="border: 2px solid #000; padding: 6px 10px; text-align: center;">
                بعذر: {{ $permissionCount }} &nbsp; بمرض: {{ $sickCount }} &nbsp; بغير عذر: {{ $absentCount }}
            </td>
            <td style="border: 2px solid #000; padding: 6px 10px; text-align: right; font-weight: bold;">العظة</td>
            <td style="border: 2px solid #000; padding: 6px 10px; text-align: center;">{{ $attitude->attitude_note ?? '-' }}</td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; border: 2px solid #000; border-top: none;">
        <tr>
            <td style="width: 33.33%; border: 2px solid #000; padding: 20px 12px 10px; text-align: center; vertical-align: bottom;">
                <div style="font-weight: bold; margin-bottom: 4px;">الأستاذ</div>
                <div style="font-size: 11px; color: #666;">Guru Fan/Mapel</div>
                <div style="margin-top: 32px; border-top: 1px solid #000; padding-top: 4px; font-size: 12px;">( ....................... )</div>
            </td>
            <td style="width: 33.33%; border: 2px solid #000; padding: 20px 12px 10px; text-align: center; vertical-align: bottom;">
                <div style="font-weight: bold; margin-bottom: 4px;">الولي</div>
                <div style="font-size: 11px; color: #666;">Wali Santri</div>
                <div style="margin-top: 32px; border-top: 1px solid #000; padding-top: 4px; font-size: 12px;">( ....................... )</div>
            </td>
            <td style="width: 33.33%; border: 2px solid #000; padding: 20px 12px 10px; text-align: center; vertical-align: bottom;">
                <div style="font-weight: bold; margin-bottom: 4px;">المدير</div>
                <div style="font-size: 11px; color: #666;">Kepala Madrasah</div>
                <div style="margin-top: 32px; border-top: 1px solid #000; padding-top: 4px; font-size: 12px;">( ....................... )</div>
            </td>
        </tr>
    </table>
</div>
