@php
    $currentMonth = (int) ($month ?? now()->month);
    $currentYear = (int) ($year ?? now()->year);
    $attendanceDetails = $attendanceDetails ?? collect();
    $today = now();

    $date = \Carbon\Carbon::create($currentYear, $currentMonth, 1);
    $daysInMonth = $date->daysInMonth;
    $startDayOfWeek = $date->dayOfWeek;

    $weeks = [];
    $week = [];
    for ($i = 0; $i < $startDayOfWeek; $i++) {
        $week[] = null;
    }
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $week[] = $day;
        if (count($week) === 7) {
            $weeks[] = $week;
            $week = [];
        }
    }
    if (count($week) > 0) {
        while (count($week) < 7) {
            $week[] = null;
        }
        $weeks[] = $week;
    }

    $prevMonth = $currentMonth === 1 ? 12 : $currentMonth - 1;
    $prevYear = $currentMonth === 1 ? $currentYear - 1 : $currentYear;
    $nextMonth = $currentMonth === 12 ? 1 : $currentMonth + 1;
    $nextYear = $currentMonth === 12 ? $currentYear + 1 : $currentYear;

    $monthNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

    $statusConfig = [
        'present' => ['bg' => 'bg-emerald-50', 'left' => 'border-l-emerald-400', 'dot' => 'bg-emerald-500', 'label' => 'Hadir'],
        'permission' => ['bg' => 'bg-sky-50', 'left' => 'border-l-sky-400', 'dot' => 'bg-sky-500', 'label' => 'Izin'],
        'sick' => ['bg' => 'bg-yellow-50', 'left' => 'border-l-yellow-400', 'dot' => 'bg-yellow-500', 'label' => 'Sakit'],
        'absent' => ['bg' => 'bg-red-50', 'left' => 'border-l-red-400', 'dot' => 'bg-red-500', 'label' => 'Alfa'],
    ];

    $statusPriority = ['absent' => 0, 'sick' => 1, 'permission' => 2, 'present' => 3];

    $buildUrl = function ($m, $y) {
        $params = array_merge(request()->except(['month', 'year', 'page']), ['month' => $m, 'year' => $y, 'view' => 'calendar']);
        return url()->current() . '?' . http_build_query($params);
    };
@endphp

<div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
    <div class="mb-4 flex items-center justify-between">
        <a href="{{ $buildUrl($prevMonth, $prevYear) }}"
           class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-3 py-2 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
            &larr; {{ $monthNames[$prevMonth] }}
        </a>
        <h3 class="text-lg font-semibold text-teal-950">{{ $monthNames[$currentMonth] }} {{ $currentYear }}</h3>
        <a href="{{ $buildUrl($nextMonth, $nextYear) }}"
           class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-3 py-2 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
            {{ $monthNames[$nextMonth] }} &rarr;
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[560px] table-fixed border-collapse">
            <thead>
                <tr>
                    @foreach ($dayNames as $dayName)
                        <th class="border-b border-stone-300 px-2 py-2 text-center text-xs font-semibold uppercase text-neutral-500">{{ $dayName }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($weeks as $weekDays)
                    <tr>
                        @foreach ($weekDays as $day)
                            @php
                                $cellClasses = 'border border-stone-200 align-top';
                                $emptyCell = !$day;
                                $hasAttendance = false;
                                $worstStatus = null;
                                $tooltipLines = [];
                                $isThursday = false;
                                $isToday = false;

                                if (!$emptyCell) {
                                    $cellDate = \Carbon\Carbon::create($currentYear, $currentMonth, $day);
                                    $isThursday = $cellDate->dayOfWeek === 4;

                                    $dateKey = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                                    $dayAttendances = $attendanceDetails->get($dateKey, collect());
                                    $hasAttendance = $dayAttendances->isNotEmpty();
                                    $isToday = $today->year === $currentYear && $today->month === $currentMonth && $today->day === $day;

                                    if ($hasAttendance) {
                                        $sorted = $dayAttendances->sortBy(fn($d) => $statusPriority[$d->status] ?? 99);
                                        $worstStatus = $sorted->first()->status;
                                        $cfg = $statusConfig[$worstStatus];

                                        $cellClasses .= ' ' . $cfg['bg'] . ' border-l-4 ' . $cfg['left'];

                                        foreach ($dayAttendances as $detail) {
                                            $typeLabel = $detail->session->attendance_type === 'homeroom' ? 'Wali Kelas' : ($detail->session->teachingAssignment?->subject->name ?? 'Mengajar');
                                            $statusLabel = $statusConfig[$detail->status]['label'] ?? $detail->status;
                                            $line = $typeLabel . ': ' . $statusLabel;
                                            if ($detail->session->teacher) {
                                                $line .= ' (' . $detail->session->teacher->name . ')';
                                            }
                                            if ($detail->note) {
                                                $line .= ' - ' . $detail->note;
                                            }
                                            $tooltipLines[] = $line;
                                        }
                                    }

                                    if ($isThursday) {
                                        $tooltipLines[] = 'Libur (Kamis)';
                                    }

                                    if ($isToday) {
                                        $cellClasses .= ' ring-2 ring-inset ring-teal-950/20';
                                    }
                                }

                                if ($emptyCell) {
                                    $cellClasses .= ' bg-slate-50';
                                }
                            @endphp
                            <td class="{{ $cellClasses }}"@if ($tooltipLines) title="{{ implode(' &#10; ', $tooltipLines) }}"@endif>
                                @if (!$emptyCell)
                                    <div class="min-h-[56px] p-1.5">
                                        <div class="flex items-center gap-1">
                                            <span class="text-sm font-semibold {{ $isToday ? 'text-teal-950' : 'text-zinc-900' }}">
                                                {{ $day }}
                                            </span>
                                            @if ($isThursday)
                                                <span class="inline-flex items-center justify-center w-3 h-3 rounded-full border border-amber-400" title="Libur (Kamis)"></span>
                                            @endif
                                        </div>
                                        @if ($hasAttendance)
                                            <div class="mt-1.5 flex items-center gap-1">
                                                <span class="inline-block w-2 h-2 rounded-full {{ $statusConfig[$worstStatus]['dot'] }}"></span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex flex-wrap items-center gap-4 border-t border-stone-200 pt-4">
        <span class="text-xs font-semibold uppercase text-neutral-500">Legenda:</span>
        @foreach ($statusConfig as $key => $color)
            <span class="inline-flex items-center gap-1.5 text-xs">
                <span class="inline-block h-3 w-3 rounded-full {{ $color['dot'] }}"></span>
                <span class="text-neutral-700">{{ $color['label'] }}</span>
            </span>
        @endforeach
        <span class="inline-flex items-center gap-1.5 text-xs">
            <span class="inline-flex items-center justify-center w-3.5 h-3.5 rounded-full border border-amber-400"></span>
            <span class="text-neutral-700">Libur Kamis</span>
        </span>
    </div>
</div>
