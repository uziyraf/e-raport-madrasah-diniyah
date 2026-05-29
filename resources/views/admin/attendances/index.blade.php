<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Monitoring Absensi</h2>
    </x-slot>

    <div class="space-y-5">
        <div class="rounded-lg bg-white p-5 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <div class="text-sm text-neutral-600">
                Tahun Ajaran: <span class="font-semibold text-teal-950">{{ $activeYear->name }}</span>
                &mdash; Semester: <span class="font-semibold text-teal-950">{{ $activeSemester->name }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($classes as $class)
                @php
                    $enrollmentTotal = $enrollmentCounts->get($class->id, 0);

                    $classSessions = $sessionCounts->get($class->id, collect());
                    $homeroomSessions = $classSessions->where('attendance_type', 'homeroom')->sum('total');
                    $teachingSessions = $classSessions->where('attendance_type', 'teaching')->sum('total');
                    $totalSessions = $homeroomSessions + $teachingSessions;

                    $statusCounts = $sessionStatusCounts->get($class->id, collect());
                    $submittedCount = $statusCounts->where('status', 'submitted')->sum('total');
                    $draftCount = $statusCounts->where('status', 'draft')->sum('total');

                    $classDisplayName = str_starts_with($class->name, $class->level->name)
                        ? $class->name
                        : $class->level->name . ' ' . $class->name;
                @endphp

                <div class="flex flex-col rounded-lg bg-white p-5 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="min-w-0 truncate text-base font-bold text-teal-950">{{ $classDisplayName }}</h3>
                        <span class="inline-flex shrink-0 items-center justify-center rounded-full bg-teal-950 px-2.5 py-0.5 text-xs font-semibold text-white">
                            {{ $enrollmentTotal }}
                        </span>
                    </div>

                    <div class="mt-3 space-y-1 text-sm text-neutral-600">
                        <p>Tahun Ajaran: <span class="font-medium text-zinc-900">{{ $activeYear->name }}</span></p>
                        <p>Semester: <span class="font-medium text-zinc-900">{{ $activeSemester->name }}</span></p>
                    </div>

                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        @if ($totalSessions > 0)
                            @if ($submittedCount > 0)
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-200 px-2.5 py-0.5 text-xs font-semibold text-green-950">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                    {{ $submittedCount }} Dikirim
                                </span>
                            @endif
                            @if ($draftCount > 0)
                                <span class="inline-flex items-center gap-1 rounded-full bg-orange-300 px-2.5 py-0.5 text-xs font-semibold text-orange-950">
                                    <span class="h-1.5 w-1.5 rounded-full bg-orange-600"></span>
                                    {{ $draftCount }} Draft
                                </span>
                            @endif
                        @else
                            <span class="inline-flex items-center rounded-full bg-zinc-200 px-2.5 py-0.5 text-xs font-semibold text-neutral-700">
                                Belum ada absensi
                            </span>
                        @endif
                    </div>

                    <div class="mt-4 flex items-center justify-between border-t border-stone-100 pt-4">
                        <span class="text-xs text-neutral-400">
                            @if ($totalSessions > 0)
                                {{ $totalSessions }} sesi
                                @if ($homeroomSessions && $teachingSessions)
                                    ({{ $homeroomSessions }} kelas / {{ $teachingSessions }} mapel)
                                @elseif ($homeroomSessions)
                                    (kelas)
                                @elseif ($teachingSessions)
                                    (mapel)
                                @endif
                            @else
                                Belum ada sesi
                            @endif
                        </span>
                        <a href="{{ route('admin.attendances.class', $class) }}"
                           class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                            Lihat Absensi
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <p class="text-center text-sm text-neutral-500">Belum ada kelas aktif.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $classes->links() }}
        </div>
    </div>
</x-app-layout>
