<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Input Nilai Mengajar</h2>
    </x-slot>

    <div class="space-y-5">
        @if (session('success'))
            <div class="rounded-sm bg-emerald-200 px-4 py-3 text-sm font-medium text-green-950">
                {{ session('success') }}
            </div>
        @endif

        @if ($assignments->isEmpty())
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <p class="text-center text-sm text-neutral-500">
                    Belum ada penugasan mengajar untuk tahun ajaran dan semester aktif.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($assignments as $assignment)
                    <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                        <div class="mb-4">
                            <h3 class="text-base font-semibold text-zinc-900">{{ $assignment->subject->name }}</h3>
                            <p class="mt-0.5 text-xs font-semibold uppercase text-neutral-500">
                                {{ $assignment->schoolClass->level->name }} {{ $assignment->schoolClass->name }}
                            </p>
                        </div>

                        <div class="mb-4 space-y-1 text-sm text-neutral-600">
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

                        <div class="mb-4 flex items-center gap-3">
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

                        <a href="{{ route('teacher.grades.edit', $assignment) }}"
                           class="inline-flex w-full items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                            Lihat / Input Nilai
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $assignments->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
