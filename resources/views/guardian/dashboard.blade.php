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
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <p class="text-xs font-semibold uppercase text-neutral-500">Total Santri</p>
                    <p class="mt-1 text-3xl font-bold text-teal-950">{{ $totalStudents }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($students as $student)
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
                                <h3 class="text-base font-semibold text-zinc-900 truncate">{{ $student->name }}</h3>
                                <p class="text-sm text-neutral-500">NIS: {{ $student->nis }}</p>
                                @if ($student->activeEnrollment?->schoolClass)
                                    <p class="mt-1 text-xs text-neutral-500">
                                        {{ $student->activeEnrollment->schoolClass->level->name ?? '' }} - {{ $student->activeEnrollment->schoolClass->name }}
                                    </p>
                                @endif
                                <div class="mt-1">
                                    @if ($student->status === 'active')
                                        <span class="inline-flex rounded-full bg-emerald-200 px-2 py-0.5 text-xs font-semibold text-green-950">Aktif</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-zinc-200 px-2 py-0.5 text-xs font-semibold text-neutral-700">Nonaktif</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-2 border-t border-stone-200 pt-4">
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
            </div>

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
