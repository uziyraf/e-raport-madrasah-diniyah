<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Dashboard</h2>
    </x-slot>

    @if (auth()->user()->hasRole('super_admin'))
        <div class="space-y-6">
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <p class="text-2xl font-bold text-teal-950">
                    Selamat {{ $greeting }}, Super Admin &#x1f44b;
                </p>
                <p class="mt-1 text-lg text-neutral-600">{{ auth()->user()->name }}</p>
                <p class="mt-2 text-sm text-neutral-500">{{ $currentDate }}</p>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <div class="flex items-center gap-4">
                        <span class="flex h-12 w-12 items-center justify-center rounded-sm bg-teal-950 text-white">
                            <i class="bx bx-user text-xl"></i>
                        </span>
                        <div>
                            <p class="text-xs font-semibold uppercase text-neutral-500">Total Guru</p>
                            <p class="mt-1 text-3xl font-bold text-teal-950">{{ $teacherCount }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <div class="flex items-center gap-4">
                        <span class="flex h-12 w-12 items-center justify-center rounded-sm bg-teal-950 text-white">
                            <i class="bx bxs-user-rectangle text-xl"></i>
                        </span>
                        <div>
                            <p class="text-xs font-semibold uppercase text-neutral-500">Total Santri</p>
                            <p class="mt-1 text-3xl font-bold text-teal-950">{{ $studentCount }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <div class="flex items-center gap-4">
                        <span class="flex h-12 w-12 items-center justify-center rounded-sm bg-teal-950 text-white">
                            <i class="bx bx-layer text-xl"></i>
                        </span>
                        <div>
                            <p class="text-xs font-semibold uppercase text-neutral-500">Total Kelas</p>
                            <p class="mt-1 text-3xl font-bold text-teal-950">{{ $classCount }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif (auth()->user()->hasRole('wali_kelas'))
        @php
            $teacher = auth()->user()->teacher;
            $homeroom = $teacher?->currentHomeroomAssignment;
            $schoolClass = $homeroom?->schoolClass;

            if ($schoolClass) {
                $totalSantri = \App\Models\StudentClassEnrollment::where('school_class_id', $schoolClass->id)
                    ->where('is_active', true)
                    ->count();

                $hariMap = ['Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
                            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'];
                $hariIni = $hariMap[now()->format('l')] ?? '';

                $jadwalHariIni = \App\Models\JadwalPelajaran::with(['mapel', 'guru'])
                    ->where('kelas_id', $schoolClass->id)
                    ->where('hari', $hariIni)
                    ->orderBy('jam_mulai')
                    ->get();
            } else {
                $totalSantri = 0;
                $jadwalHariIni = collect();
            }
        @endphp

        <div class="space-y-6">
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <p class="text-2xl font-bold text-teal-950">
                    Selamat {{ $greeting }} &#x1f44b;
                </p>
                <p class="mt-1 text-lg text-neutral-600">{{ auth()->user()->name }}</p>
                <p class="mt-2 text-sm text-neutral-500">{{ $currentDate }}</p>
            </div>

            @if (!$teacher || !$homeroom)
                <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <div class="py-10 text-center">
                        <p class="text-base font-normal text-neutral-500">
                            Akun wali kelas belum terhubung dengan data guru atau kelas. Silakan hubungi admin.
                        </p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                        <div class="flex items-center gap-4">
                            <span class="flex h-12 w-12 items-center justify-center rounded-sm bg-teal-950 text-white">
                                <i class="bx bxs-user-rectangle text-xl"></i>
                            </span>
                            <div>
                                <p class="text-xs font-semibold uppercase text-neutral-500">Total Santri</p>
                                <p class="mt-1 text-3xl font-bold text-teal-950">{{ $totalSantri }}</p>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-neutral-500">{{ $schoolClass->level->name ?? '' }} - {{ $schoolClass->name }}</p>
                    </div>

                    <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                        <p class="text-xs font-semibold uppercase text-neutral-500">Akses Cepat</p>
                        <div class="mt-4">
                            <a href="{{ route('homeroom.journals.index') }}"
                               class="inline-flex w-full items-center justify-center gap-3 rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                                <i class="bx bx-notepad text-lg"></i>
                                Jurnal Guru
                            </a>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <p class="text-xs font-semibold uppercase text-neutral-500">Jadwal Hari Ini</p>

                    @if ($jadwalHariIni->isNotEmpty())
                        <div class="mt-4 space-y-3">
                            @foreach ($jadwalHariIni as $jadwal)
                                <div class="flex items-center gap-4 rounded-sm bg-slate-50 p-3 outline outline-1 outline-stone-200">
                                    <div class="min-w-[100px] text-sm font-medium text-teal-950">
                                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-zinc-900">{{ $jadwal->mapel->name ?? '-' }}</p>
                                        <p class="text-xs text-neutral-500">{{ $jadwal->guru->name ?? '-' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-4 text-sm text-neutral-500">Tidak ada jadwal pelajaran hari ini.</p>
                    @endif
                </div>
            @endif
        </div>
    @elseif (auth()->user()->hasRole('guru_fan'))
        @php
            $teacher = auth()->user()->teacher;
            $teachingAssignments = $teacher?->currentTeachingAssignments; // already eager loads subject, schoolClass.level
            $tas = $teachingAssignments ?? collect();

            $totalKelas = $tas->pluck('school_class_id')->unique()->count();
            $totalFan = $tas->pluck('subject_id')->unique()->count();

            $kelasDiampu = $tas->groupBy(fn($ta) => $ta->schoolClass->level->name . ' - ' . $ta->schoolClass->name);

            $hariMap = ['Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
                        'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'];
            $hariIni = $hariMap[now()->format('l')] ?? '';

            $jadwalHariIni = $teacher && $hariIni
                ? \App\Models\JadwalPelajaran::with(['mapel', 'kelas.level'])
                    ->where('guru_id', $teacher->id)
                    ->where('hari', $hariIni)
                    ->orderBy('jam_mulai')
                    ->get()
                : collect();
        @endphp

        <div class="space-y-6">
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <p class="text-2xl font-bold text-teal-950">
                    Selamat {{ $greeting }} &#x1f44b;
                </p>
                <p class="mt-1 text-lg text-neutral-600">{{ auth()->user()->name }}</p>
                <p class="mt-2 text-sm text-neutral-500">{{ $currentDate }}</p>
            </div>

            @if (!$teacher)
                <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <div class="py-10 text-center">
                        <p class="text-base font-normal text-neutral-500">
                            Akun guru belum terhubung dengan data guru. Silakan hubungi admin.
                        </p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                        <div class="flex items-center gap-4">
                            <span class="flex h-12 w-12 items-center justify-center rounded-sm bg-teal-950 text-white">
                                <i class="bx bx-layer text-xl"></i>
                            </span>
                            <div>
                                <p class="text-xs font-semibold uppercase text-neutral-500">Total Kelas Diampu</p>
                                <p class="mt-1 text-3xl font-bold text-teal-950">{{ $totalKelas }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                        <div class="flex items-center gap-4">
                            <span class="flex h-12 w-12 items-center justify-center rounded-sm bg-teal-950 text-white">
                                <i class="bx bx-book-open text-xl"></i>
                            </span>
                            <div>
                                <p class="text-xs font-semibold uppercase text-neutral-500">Total Fan/Mapel Diampu</p>
                                <p class="mt-1 text-3xl font-bold text-teal-950">{{ $totalFan }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                        <p class="text-xs font-semibold uppercase text-neutral-500">Kelas yang Anda Ampu</p>
                        @if ($kelasDiampu->isNotEmpty())
                            <div class="mt-4 space-y-4">
                                @foreach ($kelasDiampu as $kelasLabel => $assignments)
                                    <div>
                                        <p class="text-sm font-semibold text-teal-950">
                                            &#x1f4d8; {{ $kelasLabel }}
                                        </p>
                                        <ul class="mt-2 space-y-1">
                                            @foreach ($assignments as $ta)
                                                <li class="text-sm text-neutral-600">
                                                    - {{ $ta->subject->name ?? '-' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="mt-4 text-sm text-neutral-500">Belum ada kelas yang diampu.</p>
                        @endif
                    </div>

                    <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                        <p class="text-xs font-semibold uppercase text-neutral-500">Jadwal Hari Ini</p>
                        @if ($jadwalHariIni->isNotEmpty())
                            <div class="mt-4 space-y-4">
                                @foreach ($jadwalHariIni as $jadwal)
                                    <div>
                                        <p class="text-sm font-medium text-teal-950">
                                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                        </p>
                                        <p class="mt-0.5 text-sm font-semibold text-zinc-900">{{ $jadwal->mapel->name ?? '-' }}</p>
                                        <p class="text-xs text-neutral-500">{{ $jadwal->kelas->level->name ?? '' }} {{ $jadwal->kelas->name ?? '-' }}</p>
                                        @if (!$loop->last)
                                            <hr class="my-3 border-stone-200">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="mt-4 text-sm text-neutral-500">Tidak ada jadwal mengajar hari ini.</p>
                        @endif
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <p class="text-xs font-semibold uppercase text-neutral-500">Akses Cepat</p>
                    <div class="mt-4">
                        <a href="{{ route('teacher.journals.index') }}"
                           class="inline-flex w-full items-center justify-center gap-3 rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                            <i class="bx bx-notepad text-lg"></i>
                            Isi Jurnal Guru
                        </a>
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <div class="py-10 text-center">
                <p class="text-base font-normal text-neutral-500">
                    {{ __("You're logged in!") }}
                </p>
            </div>
        </div>
    @endif
</x-app-layout>
