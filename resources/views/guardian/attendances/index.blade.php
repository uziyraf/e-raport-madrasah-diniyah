<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Absensi Santri</h2>
    </x-slot>

    <div class="space-y-5">
        @if (!$guardian || $studentsSimple->isEmpty())
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <div class="py-10 text-center">
                    <p class="text-base font-normal text-neutral-500">
                        @if (!$guardian)
                            Profil wali santri belum terhubung dengan akun Anda. Silakan hubungi admin.
                        @else
                            Belum ada santri yang terhubung dengan akun Anda.
                        @endif
                    </p>
                </div>
            </div>
        @elseif ($viewMode === 'calendar')
            @php
                $month = (int) ($month ?? now()->month);
                $year = (int) ($year ?? now()->year);
            @endphp

            <div class="flex flex-wrap items-center justify-between gap-3">
                <a href="{{ route('guardian.attendances.index') }}"
                   class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-3 py-2 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                    &larr; Ringkasan
                </a>
                @if ($studentsSimple->count() > 1)
                    <div class="flex flex-wrap gap-2">
                        @foreach ($studentsSimple as $s)
                            @php
                                $url = route('guardian.attendances.index', ['view' => 'calendar', 'student_id' => $s->id, 'month' => $month, 'year' => $year]);
                            @endphp
                            <a href="{{ $url }}"
                               class="inline-flex items-center justify-center rounded-sm px-4 py-2 text-sm font-medium transition
                                  @if ((int) $selectedStudent->id === (int) $s->id)
                                      bg-teal-950 text-white
                                  @else
                                      bg-slate-50 text-slate-600 outline outline-1 outline-neutral-500 hover:bg-slate-100
                                  @endif">
                                {{ $s->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <h3 class="text-base font-semibold text-teal-950">
                    {{ $selectedStudent->name }}
                    @if ($selectedStudent->activeEnrollment?->schoolClass)
                        <span class="text-sm font-normal text-neutral-500">
                            &mdash; {{ $selectedStudent->activeEnrollment->schoolClass->level->name ?? '' }} {{ $selectedStudent->activeEnrollment->schoolClass->name }}
                        </span>
                    @endif
                </h3>
                <p class="text-sm text-neutral-500">NIS: {{ $selectedStudent->nis }}</p>
            </div>

            @include('partials.attendance-calendar', [
                'attendanceDetails' => $attendanceDetails,
                'month' => $month,
                'year' => $year,
            ])
        @else
            <div class="space-y-5">
                @foreach ($students as $student)
                    @php
                        $details = $student->attendanceDetails ?? collect();
                        $presentCount = $details->where('status', 'present')->count();
                        $sickCount = $details->where('status', 'sick')->count();
                        $permissionCount = $details->where('status', 'permission')->count();
                        $absentCount = $details->where('status', 'absent')->count();
                    @endphp
                    <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-semibold text-zinc-900">{{ $student->name }}</h3>
                                <p class="text-sm text-neutral-500">NIS: {{ $student->nis }}</p>
                                @if ($student->activeEnrollment?->schoolClass)
                                    <p class="text-sm text-neutral-500">
                                        {{ $student->activeEnrollment->schoolClass->level->name ?? '' }} - {{ $student->activeEnrollment->schoolClass->name }}
                                    </p>
                                @endif
                            </div>
                            @php
                                $calUrl = route('guardian.attendances.index', ['view' => 'calendar', 'student_id' => $student->id, 'month' => now()->month, 'year' => now()->year]);
                            @endphp
                            <a href="{{ $calUrl }}"
                               class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-3 py-2 text-sm font-medium text-white transition hover:bg-teal-900">
                                Lihat Kalender
                            </a>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-4 md:grid-cols-4">
                            <div class="rounded-sm bg-emerald-200 px-4 py-3 text-center">
                                <span class="block text-lg font-bold text-green-950">{{ $presentCount }}</span>
                                <span class="text-xs font-semibold uppercase text-green-950">Hadir</span>
                            </div>
                            <div class="rounded-sm bg-orange-300 px-4 py-3 text-center">
                                <span class="block text-lg font-bold text-orange-950">{{ $sickCount }}</span>
                                <span class="text-xs font-semibold uppercase text-orange-950">Sakit</span>
                            </div>
                            <div class="rounded-sm bg-sky-200 px-4 py-3 text-center">
                                <span class="block text-lg font-bold text-sky-950">{{ $permissionCount }}</span>
                                <span class="text-xs font-semibold uppercase text-sky-950">Izin</span>
                            </div>
                            <div class="rounded-sm bg-red-200 px-4 py-3 text-center">
                                <span class="block text-lg font-bold text-red-950">{{ $absentCount }}</span>
                                <span class="text-xs font-semibold uppercase text-red-950">Alfa</span>
                            </div>
                        </div>

                        @if ($details->isNotEmpty())
                            <div class="mt-4">
                                <p class="mb-2 text-xs font-semibold uppercase text-neutral-500">Riwayat Terbaru</p>
                                <ul class="divide-y divide-stone-200">
                                    @foreach ($details->take(5) as $detail)
                                        <li class="flex items-center justify-between py-2">
                                            <span class="text-sm text-zinc-900">
                                                {{ $detail->session->attendance_date ? $detail->session->attendance_date->format('d/m/Y') : '-' }}
                                            </span>
                                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold
                                                @if ($detail->status === 'present') bg-emerald-200 text-green-950
                                                @elseif ($detail->status === 'sick') bg-orange-300 text-orange-950
                                                @elseif ($detail->status === 'permission') bg-sky-200 text-sky-950
                                                @elseif ($detail->status === 'absent') bg-red-200 text-red-950
                                                @else bg-zinc-200 text-neutral-700 @endif">
                                                {{ ucfirst($detail->status) }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <p class="mt-4 text-sm text-neutral-500">Belum ada riwayat absensi.</p>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
