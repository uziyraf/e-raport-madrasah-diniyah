<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Export Rekap</h2>
    </x-slot>

    <div class="space-y-6">
        @if ($assignments->isEmpty())
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <p class="py-10 text-center text-sm text-neutral-500">
                    Tidak ada penugasan mengajar aktif.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($assignments as $assignment)
                    <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                        <h3 class="text-base font-semibold text-zinc-900">{{ $assignment->subject->name }}</h3>
                        <p class="mt-1 text-sm text-neutral-500">
                            {{ $assignment->schoolClass->level->name ?? '' }} {{ $assignment->schoolClass->name }}
                        </p>
                        <div class="mt-4 space-y-2">
                            <a href="{{ route('teacher.exports.attendances', ['teaching_assignment_id' => $assignment->id]) }}"
                               class="inline-flex w-full items-center justify-center rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                                <i class="bx bx-download mr-1"></i> Rekap Absensi
                            </a>
                            <a href="{{ route('teacher.exports.grades', ['teaching_assignment_id' => $assignment->id]) }}"
                               class="inline-flex w-full items-center justify-center rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                                <i class="bx bx-download mr-1"></i> Rekap Nilai
                            </a>
                            <a href="{{ route('teacher.exports.journals', ['teaching_assignment_id' => $assignment->id]) }}"
                               class="inline-flex w-full items-center justify-center rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                                <i class="bx bx-download mr-1"></i> Rekap Jurnal
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
