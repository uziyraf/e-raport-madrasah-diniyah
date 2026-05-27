<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Monitoring Nilai Kelas</h2>
    </x-slot>

    <div class="space-y-5">
        @if (!$homeroom)
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <p class="text-center text-sm text-neutral-500">
                    Anda belum ditugaskan sebagai wali kelas untuk tahun ajaran dan semester aktif.
                </p>
            </div>
        @else
            <div class="rounded-lg bg-slate-50 p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <div class="grid grid-cols-3 gap-4">
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

            @if ($assignments->isEmpty())
                <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <p class="text-center text-sm text-neutral-500">
                        Belum ada penugasan guru fan untuk kelas ini.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($assignments as $assignment)
                        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                            <div class="mb-4">
                                <h3 class="text-base font-semibold text-zinc-900">{{ $assignment->subject->name }}</h3>
                                <p class="mt-0.5 text-xs font-semibold uppercase text-neutral-500">
                                    {{ $assignment->teacher->name }}
                                </p>
                            </div>

                            <div class="mb-4 space-y-1 text-sm text-neutral-600">
                                <div class="flex items-center justify-between">
                                    <span>Kelas</span>
                                    <span class="font-medium text-zinc-900">{{ $assignment->schoolClass->level->name }} {{ $assignment->schoolClass->name }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Tahun Ajaran</span>
                                    <span class="font-medium text-zinc-900">{{ $assignment->academicYear->name }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Semester</span>
                                    <span class="font-medium text-zinc-900">{{ $assignment->semester->name }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Total Santri</span>
                                    <span class="font-medium text-zinc-900">{{ $assignment->enrolled_students_count }}</span>
                                </div>
                            </div>

                            <div class="mb-4 flex flex-wrap items-center gap-2">
                                <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">
                                    {{ $assignment->submitted_grades_count }} Terkirim
                                </span>
                                <span class="inline-flex rounded-full bg-orange-300 px-3 py-1 text-xs font-semibold text-orange-950">
                                    {{ $assignment->draft_grades_count }} Draft
                                </span>
                                <span class="inline-flex rounded-full bg-zinc-200 px-3 py-1 text-xs font-semibold text-neutral-700">
                                    {{ $assignment->enrolled_students_count - $assignment->grades_count }} Belum
                                </span>
                            </div>

                            <a href="{{ route('homeroom.grades.show', $assignment) }}"
                               class="inline-flex w-full items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                                Lihat Detail
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
