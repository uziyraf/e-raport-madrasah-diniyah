<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Dashboard Wali Santri</h2>
    </x-slot>

    <div class="space-y-6">
        @if (!$guardian)
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <div class="py-10 text-center">
                    <p class="text-base font-normal text-neutral-500">
                        Profil wali santri belum terhubung dengan akun Anda. Silakan hubungi admin.
                    </p>
                </div>
            </div>
        @elseif ($students->isEmpty())
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <div class="py-10 text-center">
                    <p class="text-base font-normal text-neutral-500">
                        Belum ada santri yang terhubung dengan akun Anda.
                    </p>
                </div>
            </div>
        @else
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <p class="text-2xl font-bold text-teal-950">
                    Selamat {{ $greeting }} &#x1f44b;
                </p>
                <p class="mt-1 text-lg text-neutral-600">{{ auth()->user()->name }}</p>
                <p class="mt-2 text-sm text-neutral-500">{{ $currentDate }}</p>
            </div>

            @foreach ($students as $student)
                @php
                    $summary = $attendanceSummary[$student->id] ?? ['present' => 0, 'sick' => 0, 'permission' => 0, 'absent' => 0];
                    $today = $todayStatus[$student->id] ?? null;
                    $statusLabels = ['present' => 'Hadir', 'sick' => 'Sakit', 'permission' => 'Izin', 'absent' => 'Alpha'];
                @endphp

                <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <div class="flex items-start gap-4">
                        @if ($student->photo_path)
                            <img src="{{ asset('storage/' . $student->photo_path) }}" alt="{{ $student->name }}"
                                 class="h-14 w-14 rounded-full object-cover outline outline-1 outline-stone-300">
                        @else
                            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-teal-100 text-sm font-bold text-teal-950 outline outline-1 outline-stone-300">
                                {{ strtoupper(substr($student->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <h3 class="text-base font-semibold text-zinc-900">{{ $student->name }}</h3>
                            <p class="text-sm text-neutral-500">NIS: {{ $student->nis }}</p>
                            @if ($student->activeEnrollment?->schoolClass)
                                <p class="mt-1 text-sm text-neutral-500">
                                    {{ $student->activeEnrollment->schoolClass->level->name ?? '' }} - {{ $student->activeEnrollment->schoolClass->name }}
                                </p>
                            @endif
                            @if ($student->activeEnrollment?->academicYear)
                                <p class="text-xs text-neutral-400">
                                    Tahun Akademik: {{ $student->activeEnrollment->academicYear->name }}
                                </p>
                            @endif
                            <div class="mt-2">
                                @if ($student->status === 'active')
                                    <span class="inline-flex rounded-full bg-emerald-200 px-2 py-0.5 text-xs font-semibold text-green-950">Aktif</span>
                                @else
                                    <span class="inline-flex rounded-full bg-zinc-200 px-2 py-0.5 text-xs font-semibold text-neutral-700">Nonaktif</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <p class="mb-3 text-xs font-semibold uppercase text-neutral-500">Ringkasan Absensi</p>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="rounded-sm bg-emerald-200 px-3 py-2.5 text-center">
                                    <span class="block text-lg font-bold text-green-950">{{ $summary['present'] }}</span>
                                    <span class="text-xs font-semibold uppercase text-green-950">Hadir</span>
                                </div>
                                <div class="rounded-sm bg-sky-200 px-3 py-2.5 text-center">
                                    <span class="block text-lg font-bold text-sky-950">{{ $summary['permission'] }}</span>
                                    <span class="text-xs font-semibold uppercase text-sky-950">Izin</span>
                                </div>
                                <div class="rounded-sm bg-yellow-100 px-3 py-2.5 text-center">
                                    <span class="block text-lg font-bold text-yellow-900">{{ $summary['sick'] }}</span>
                                    <span class="text-xs font-semibold uppercase text-yellow-900">Sakit</span>
                                </div>
                                <div class="rounded-sm bg-red-200 px-3 py-2.5 text-center">
                                    <span class="block text-lg font-bold text-red-950">{{ $summary['absent'] }}</span>
                                    <span class="text-xs font-semibold uppercase text-red-950">Alfa</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <p class="mb-3 text-xs font-semibold uppercase text-neutral-500">Status Hari Ini</p>
                            @if ($today)
                                <div class="flex items-center gap-3 rounded-sm px-4 py-3
                                    @if ($today === 'present') bg-emerald-200 text-green-950
                                    @elseif ($today === 'permission') bg-sky-200 text-sky-950
                                    @elseif ($today === 'sick') bg-yellow-100 text-yellow-900
                                    @else bg-red-200 text-red-950 @endif">
                                    <span class="text-lg font-bold">
                                        @if ($today === 'present') &#x2705;
                                        @elseif ($today === 'permission') &#x1f7e1;
                                        @elseif ($today === 'sick') &#x1f7e0;
                                        @else &#x1f534; @endif
                                    </span>
                                    <span class="text-base font-semibold">{{ $statusLabels[$today] ?? '-' }}</span>
                                </div>
                            @else
                                <p class="text-sm text-neutral-500">Belum ada data absensi hari ini.</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-5 flex items-center gap-2 border-t border-stone-200 pt-4">
                        <a href="{{ route('guardian.students.show', $student) }}"
                           class="inline-flex flex-1 items-center justify-center rounded-sm bg-teal-950 px-3 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                            Detail
                        </a>
                        <a href="{{ route('guardian.attendances.index', ['view' => 'calendar', 'student_id' => $student->id, 'month' => now()->month, 'year' => now()->year]) }}"
                           class="inline-flex flex-1 items-center justify-center rounded-sm bg-slate-50 px-3 py-2 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                            Absensi
                        </a>
                    </div>
                </div>
            @endforeach

            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <a href="{{ route('guardian.students.index') }}"
                   class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300 transition hover:bg-slate-50">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-sm bg-teal-950 text-white">
                            <i class="bx bxs-user-rectangle text-lg"></i>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-zinc-900">Data Santri</p>
                            <p class="text-xs text-neutral-500">Lihat data santri</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('guardian.attendances.index') }}"
                   class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300 transition hover:bg-slate-50">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-sm bg-teal-950 text-white">
                            <i class="bx bx-list-ul text-lg"></i>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-zinc-900">Absensi Santri</p>
                            <p class="text-xs text-neutral-500">Lihat riwayat absensi</p>
                        </div>
                    </div>
                </a>
                <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300 opacity-50">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-sm bg-zinc-200 text-neutral-500">
                            <i class="bx bx-file text-lg"></i>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-zinc-900">Raport Santri</p>
                            <p class="text-xs text-neutral-500">Segera hadir</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>