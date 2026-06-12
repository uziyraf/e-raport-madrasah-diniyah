<x-app-layout>
    <x-slot name="header">
        @php
            $classDisplayName = str_starts_with($schoolClass->name, $schoolClass->level->name)
                ? $schoolClass->name
                : $schoolClass->level->name . ' ' . $schoolClass->name;
        @endphp
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.attendances.index') }}"
               class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-3 py-2 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                &larr; Kembali
            </a>
            <h2 class="text-xl font-bold text-teal-950">{{ $classDisplayName }}</h2>
        </div>
    </x-slot>

    <div class="space-y-5">
        <div class="rounded-lg bg-white p-5 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <div class="text-sm text-neutral-600">
                Tahun Ajaran: <span class="font-semibold text-teal-950">{{ $activeYear->name }}</span>
                &mdash; Semester: <span class="font-semibold text-teal-950">{{ $activeSemester->name }}</span>
                &mdash; Kelas: <span class="font-semibold text-teal-950">{{ $classDisplayName }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            {{-- Homeroom context card --}}
            <div class="flex flex-col rounded-lg bg-white p-5 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <div>
                    <h3 class="text-base font-bold text-teal-950">Absensi Wali Kelas</h3>
                    @if ($homeroom)
                        <p class="mt-1 text-sm text-neutral-600">
                            Wali Kelas: <span class="font-semibold">{{ $homeroom->teacher->name }}</span>
                        </p>
                    @else
                        <p class="mt-1 text-sm text-orange-600">Belum ada wali kelas.</p>
                    @endif
                </div>

                @if ($homeroom)
                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <div class="rounded-sm bg-slate-50 p-2.5 text-center">
                            <p class="text-xs font-semibold uppercase text-neutral-400">Hadir</p>
                            <p class="mt-0.5 text-lg font-bold text-teal-950">{{ $homeroomDetailCounts->get('present', 0) }}</p>
                        </div>
                        <div class="rounded-sm bg-slate-50 p-2.5 text-center">
                            <p class="text-xs font-semibold uppercase text-neutral-400">Izin</p>
                            <p class="mt-0.5 text-lg font-bold text-orange-600">{{ $homeroomDetailCounts->get('permission', 0) }}</p>
                        </div>
                        <div class="rounded-sm bg-slate-50 p-2.5 text-center">
                            <p class="text-xs font-semibold uppercase text-neutral-400">Sakit</p>
                            <p class="mt-0.5 text-lg font-bold text-amber-600">{{ $homeroomDetailCounts->get('sick', 0) }}</p>
                        </div>
                        <div class="rounded-sm bg-slate-50 p-2.5 text-center">
                            <p class="text-xs font-semibold uppercase text-neutral-400">Alfa</p>
                            <p class="mt-0.5 text-lg font-bold text-red-600">{{ $homeroomDetailCounts->get('absent', 0) }}</p>
                        </div>
                    </div>

                    <div class="mt-3 flex items-center justify-between border-t border-stone-100 pt-3">
                        <span class="text-xs text-neutral-400">{{ $homeroomSessionCount }} sesi</span>
                        <a href="{{ route('admin.attendances.homeroom', $schoolClass) }}"
                           class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-3.5 py-1.5 text-xs font-medium text-white transition hover:bg-emerald-900">
                            Lihat Rekap
                        </a>
                    </div>
                @endif
            </div>

            {{-- Teaching assignment cards --}}
            @forelse ($teachingSummaries as $summary)
                <div class="flex flex-col rounded-lg bg-white p-5 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <div>
                        <h3 class="text-base font-bold text-teal-950">{{ $summary['assignment']->subject->name }}</h3>
                        <p class="mt-1 text-sm text-neutral-600">
                            Guru: <span class="font-semibold">{{ $summary['assignment']->teacher->name }}</span>
                        </p>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <div class="rounded-sm bg-slate-50 p-2.5 text-center">
                            <p class="text-xs font-semibold uppercase text-neutral-400">Hadir</p>
                            <p class="mt-0.5 text-lg font-bold text-teal-950">{{ $summary['present'] }}</p>
                        </div>
                        <div class="rounded-sm bg-slate-50 p-2.5 text-center">
                            <p class="text-xs font-semibold uppercase text-neutral-400">Izin</p>
                            <p class="mt-0.5 text-lg font-bold text-orange-600">{{ $summary['permission'] }}</p>
                        </div>
                        <div class="rounded-sm bg-slate-50 p-2.5 text-center">
                            <p class="text-xs font-semibold uppercase text-neutral-400">Sakit</p>
                            <p class="mt-0.5 text-lg font-bold text-amber-600">{{ $summary['sick'] }}</p>
                        </div>
                        <div class="rounded-sm bg-slate-50 p-2.5 text-center">
                            <p class="text-xs font-semibold uppercase text-neutral-400">Alfa</p>
                            <p class="mt-0.5 text-lg font-bold text-red-600">{{ $summary['absent'] }}</p>
                        </div>
                    </div>

                    <div class="mt-3 flex items-center justify-between border-t border-stone-100 pt-3">
                        <span class="text-xs text-neutral-400">{{ $summary['session_count'] }} sesi</span>
                        <a href="{{ route('admin.attendances.teaching', [$schoolClass, $summary['assignment']]) }}"
                           class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-3.5 py-1.5 text-xs font-medium text-white transition hover:bg-emerald-900">
                            Lihat Rekap
                        </a>
                    </div>
                </div>
            @empty
                <div class="rounded-lg bg-white p-5 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <p class="text-center text-sm text-neutral-500">Belum ada data absensi mengajar.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
