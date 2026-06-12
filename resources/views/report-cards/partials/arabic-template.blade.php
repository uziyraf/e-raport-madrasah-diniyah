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
    $homeroomTeacher = $reportData['homeroomTeacher'] ?? null;
    $principal = $reportData['principal'] ?? null;
    $dailyScoreTotal = $reportData['dailyScoreTotal'] ?? 0;
    $examScoreTotal = $reportData['examScoreTotal'] ?? 0;
    $subjectCount = $reportData['subjectCount'] ?? 0;
@endphp

<div dir="rtl" lang="ar" class="font-arabic" style="width: 210mm; margin: 0 auto; padding: 10mm 8mm; font-size: 13px; line-height: 1.5; color: #000;">

    {{-- TITLE --}}
    <table style="width: 100%; border-collapse: collapse; border: 2px solid #000; margin-bottom: 0;">
        <tr>
            <td style="border: 2px solid #000; padding: 10px 16px; text-align: center; font-size: 20px; font-weight: bold;">
                بطاقة التقرير
                <div style="font-size: 14px; font-weight: normal; margin-top: 2px; color: #000;">RAPORT SANTRI</div>
                <div style="font-size: 11px; font-weight: normal; margin-top: 2px; color: #000;">
                    {{ $academicYear->name }} - {{ $semester->name }}
                </div>
            </td>
        </tr>
    </table>

    {{-- HEADER: Student Info --}}
    <table style="width: 100%; border-collapse: collapse; border: 2px solid #000; border-top: none;">
        <tr>
            <td style="width: 50%; border: 2px solid #000; padding: 8px 12px; vertical-align: top;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 2px 0; font-weight: bold; width: 90px;">اسم الطالب</td>
                        <td style="padding: 2px 0;">: {{ $student->arabic_name ?? $student->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 2px 0; font-weight: bold;">القسم</td>
                        <td style="padding: 2px 0;">: {{ $schoolClass->level->name ?? '' }} {{ $schoolClass->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 2px 0; font-weight: bold;">السنة</td>
                        <td style="padding: 2px 0;">: {{ $academicYear->name }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; border: 2px solid #000; padding: 8px 12px; vertical-align: top;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 2px 0; font-weight: bold; width: 90px;">Nama Santri</td>
                        <td style="padding: 2px 0;">: {{ $student->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 2px 0; font-weight: bold;">NIS</td>
                        <td style="padding: 2px 0;">: {{ $student->nis }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 2px 0; font-weight: bold;">Tahun Masehi</td>
                        <td style="padding: 2px 0;">: {{ $academicYear->name }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- MAIN CONTENT: Two columns --}}
    <table style="width: 100%; border-collapse: collapse; border: 2px solid #000; border-top: none;">
        <tr>
            {{-- RIGHT COLUMN: Main grade table (65%) --}}
            <td style="width: 65%; border: 2px solid #000; padding: 0; vertical-align: top;">

                {{-- Subject table --}}
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #e5e7eb;">
                            <th style="border: 2px solid #000; padding: 6px 4px; text-align: center; font-weight: bold; width: 32px;">رقم</th>
                            <th style="border: 2px solid #000; padding: 6px 4px; text-align: center; font-weight: bold;">المواد</th>
                            <th style="border: 2px solid #000; padding: 6px 4px; text-align: center; font-weight: bold;" colspan="2">الدرجة التي نالها الطالب</th>
                        </tr>
                        <tr style="background: #e5e7eb;">
                            <th style="border: 2px solid #000; padding: 3px 4px;"></th>
                            <th style="border: 2px solid #000; padding: 3px 4px;"></th>
                            <th style="border: 2px solid #000; padding: 3px 4px; text-align: center; font-weight: bold; width: 60px;">يومية</th>
                            <th style="border: 2px solid #000; padding: 3px 4px; text-align: center; font-weight: bold; width: 65px;">امتحانية</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subjectRows as $row)
                            <tr>
                                <td style="border: 2px solid #000; padding: 4px; text-align: center;">{{ $row['number'] }}</td>
                                <td style="border: 2px solid #000; padding: 4px 8px; text-align: right;">{{ $row['arabic_name'] }}</td>
                                <td style="border: 2px solid #000; padding: 4px; text-align: center;">{{ $row['daily_score'] }}</td>
                                <td style="border: 2px solid #000; padding: 4px; text-align: center;">{{ $row['exam_score'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="border: 2px solid #000; padding: 10px; text-align: center; color: #666;">
                                    -
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Summary rows: total / average / faiz --}}
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="border: 2px solid #000; padding: 5px 8px; text-align: right; font-weight: bold; width: 100px;">مجموع الدرجات</td>
                        <td style="border: 2px solid #000; padding: 5px 8px; text-align: center; width: 70px;">{{ $totalScore }}</td>
                        <td style="border: 2px solid #000; padding: 5px 8px; text-align: right; font-weight: bold; width: 80px;">المواظبة</td>
                        <td style="border: 2px solid #000; padding: 5px 8px; text-align: center;">{{ $attitude->discipline ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="border: 2px solid #000; padding: 5px 8px; text-align: right; font-weight: bold;">نتيجة المعدلة</td>
                        <td style="border: 2px solid #000; padding: 5px 8px; text-align: center;">{{ $average }}</td>
                        <td style="border: 2px solid #000; padding: 5px 8px; text-align: right; font-weight: bold;">الأخلاق</td>
                        <td style="border: 2px solid #000; padding: 5px 8px; text-align: center;">{{ $attitude->akhlak ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="border: 2px solid #000; padding: 5px 8px; text-align: right; font-weight: bold;">الفائز</td>
                        <td style="border: 2px solid #000; padding: 5px 8px; text-align: center;">-</td>
                        <td style="border: 2px solid #000; padding: 5px 8px; text-align: right; font-weight: bold;">النظافة</td>
                        <td style="border: 2px solid #000; padding: 5px 8px; text-align: center;">{{ $attitude->cleanliness ?? '-' }}</td>
                    </tr>
                </table>

                {{-- Attendance section --}}
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="border: 2px solid #000; padding: 5px 8px; text-align: right; font-weight: bold; width: 100px;">عدم الحضور</td>
                        <td style="border: 2px solid #000; padding: 5px 8px; text-align: center;">
                            بعذر: {{ $permissionCount }} &nbsp;&nbsp; بمرض: {{ $sickCount }} &nbsp;&nbsp; بغير عذر: {{ $absentCount }}
                        </td>
                        <td style="border: 2px solid #000; padding: 5px 8px; text-align: right; font-weight: bold; width: 80px;">العظة</td>
                        <td style="border: 2px solid #000; padding: 5px 8px; text-align: center;">{{ $attitude->attitude_note ?? '-' }}</td>
                    </tr>
                </table>

            </td>

            {{-- LEFT COLUMN: QR + Signatures (35%) --}}
            <td style="width: 35%; border: 2px solid #000; padding: 0; vertical-align: top;">

                {{-- QR placeholder --}}
                <div style="border-bottom: 2px solid #000; padding: 10px; text-align: center;">
                    <div style="width: 80px; height: 80px; margin: 0 auto; border: 1px solid #000; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #666;">
                        QR
                    </div>
                </div>

                {{-- النصف الأول --}}
                <div style="border-bottom: 2px solid #000; padding: 10px; text-align: center;">
                    <div style="font-weight: bold; margin-bottom: 4px;">النصف الأول</div>
                    <div style="font-size: 11px; color: #666;">Semester {{ $semester->name }}</div>
                    <div style="margin-top: 6px; font-size: 11px;">( .............. )</div>
                </div>

                {{-- الأستاذ (Guru Fan/Mapel) --}}
                <div style="border-bottom: 2px solid #000; padding: 10px; text-align: center;">
                    <div style="font-weight: bold; margin-bottom: 4px;">الأستاذ</div>
                    <div style="font-size: 11px; color: #666;">Guru Fan/Mapel</div>
                    @php
                        $firstTeacher = $subjectRows->first()['teacher_arabic_name'] ?? null;
                    @endphp
                    <div style="margin-top: 8px; font-size: 12px;">( {{ $firstTeacher ?? '.......................' }} )</div>
                </div>

                {{-- الولي (Wali Santri) --}}
                <div style="border-bottom: 2px solid #000; padding: 10px; text-align: center;">
                    <div style="font-weight: bold; margin-bottom: 4px;">الولي</div>
                    <div style="font-size: 11px; color: #666;">Wali Santri</div>
                    <div style="margin-top: 8px; font-size: 12px;">( ....................... )</div>
                </div>

                {{-- المدير (Kepala Madrasah) --}}
                <div style="border-bottom: 2px solid #000; padding: 10px; text-align: center;">
                    <div style="font-weight: bold; margin-bottom: 4px;">المدير</div>
                    <div style="font-size: 11px; color: #666;">Kepala Madrasah</div>
                    <div style="margin-top: 8px; font-size: 12px;">( {{ $principal ? ($principal->arabic_name ?? $principal->name) : '.......................' }} )</div>
                </div>

                {{-- العظة --}}
                <div style="padding: 10px; text-align: center;">
                    <div style="font-weight: bold; margin-bottom: 4px;">العظة</div>
                    <div style="font-size: 11px; color: #666;">Catatan Wali Kelas</div>
                    <div style="margin-top: 8px; font-size: 11px;">{{ $homeroomTeacher ? ($homeroomTeacher->arabic_name ?? $homeroomTeacher->name) : '.......................' }}</div>
                </div>

            </td>
        </tr>
    </table>
</div>
