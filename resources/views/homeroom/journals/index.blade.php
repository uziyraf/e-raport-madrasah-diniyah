<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Jurnal Guru</h2>
    </x-slot>

    <div class="space-y-5">
        @if (session('success'))
            <div class="rounded-sm bg-emerald-200 px-4 py-3 text-sm font-medium text-green-950">
                {{ session('success') }}
            </div>
        @endif

        @if (!$homeroom)
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <p class="text-center text-sm text-neutral-500">
                    Anda belum ditugaskan sebagai wali kelas untuk tahun ajaran dan semester aktif.
                </p>
            </div>
        @else
            <div class="rounded-lg bg-slate-50 p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <p class="text-xs font-semibold uppercase text-neutral-500">Kelas</p>
                        <p class="mt-1 text-base font-medium text-zinc-900">{{ $homeroom->schoolClass->level->name }} {{ $homeroom->schoolClass->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-neutral-500">Tahun Ajaran</p>
                        <p class="mt-1 text-base font-medium text-zinc-900">{{ $homeroom->academicYear->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-neutral-500">Semester</p>
                        <p class="mt-1 text-base font-medium text-zinc-900">{{ $homeroom->semester->name }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($stats as $type => $stat)
                    <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-teal-950">{{ $labels[$type] }}</h3>
                            <span class="inline-flex items-center justify-center rounded-full bg-teal-950 px-3 py-1 text-xs font-semibold text-white">
                                {{ $stat['total_records'] }}
                            </span>
                        </div>

                        <div class="space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-neutral-500">Total Santri di Kelas</span>
                                <span class="font-medium text-zinc-900">{{ $totalStudents }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-neutral-500">Santri dengan Jurnal</span>
                                <span class="font-medium text-teal-950">{{ $stat['students_with_records'] }}</span>
                            </div>
                        </div>

                        @if ($stat['latest_date'])
                            <div class="mt-4 text-xs text-neutral-500">
                                Jurnal terakhir: {{ \Carbon\Carbon::parse($stat['latest_date'])->format('d/m/Y') }}
                            </div>
                        @else
                            <div class="mt-4 text-xs text-neutral-400">
                                Belum ada catatan
                            </div>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('homeroom.journals.students', $type) }}"
                               class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-2 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                                Lihat Santri
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
