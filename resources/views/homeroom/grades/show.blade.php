<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Detail Nilai {{ $assignment->subject->name }}</h2>
    </x-slot>

    <div class="space-y-5">
        @if (session('success'))
            <div class="rounded-lg bg-emerald-100 p-4 text-sm font-medium text-emerald-800 outline outline-1 outline-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-lg bg-slate-50 p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <div class="grid flex-1 grid-cols-2 gap-4 md:grid-cols-4">
                    <div>
                        <p class="text-xs font-semibold uppercase text-neutral-500">Fan/Mapel</p>
                        <p class="mt-1 text-base font-medium text-zinc-900">{{ $assignment->subject->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-neutral-500">Guru Pengampu</p>
                        <p class="mt-1 text-base font-medium text-zinc-900">{{ $assignment->teacher->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-neutral-500">Kelas</p>
                        <p class="mt-1 text-base font-medium text-zinc-900">{{ $assignment->schoolClass->level->name }} {{ $assignment->schoolClass->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-neutral-500">Tahun Ajaran</p>
                        <p class="mt-1 text-base font-medium text-zinc-900">{{ $assignment->academicYear->name }} / {{ $assignment->semester->name }}</p>
                    </div>
                </div>

                <div class="flex-shrink-0">
                    <a href="{{ route('homeroom.grades.edit', $assignment) }}"
                       class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-emerald-900">
                        <i class="bx bx-edit mr-2 text-base"></i> Input / Edit Nilai
                    </a>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
            <table class="min-w-full divide-y divide-stone-300">
                <thead>
                    <tr>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700 w-8">No</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Nama Santri</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">NIS</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Nilai</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Predikat</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Keterangan</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Status Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-300">
                    @forelse ($students as $enrollment)
                        @php
                            $grade = $existingGrades->get($enrollment->student_id);
                        @endphp
                        <tr>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $loop->iteration }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base font-medium text-zinc-900">{{ $enrollment->student->name }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $enrollment->student->nis }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $grade?->score ?? '-' }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $grade?->predicate ?? '-' }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $grade?->note ?? '-' }}</td>
                            <td class="border-t border-stone-300 px-4 py-3">
                                @if (!$grade)
                                    <span class="inline-flex rounded-full bg-zinc-200 px-3 py-1 text-xs font-semibold text-neutral-700">Belum diisi</span>
                                @elseif ($grade->status === 'draft')
                                    <span class="inline-flex rounded-full bg-orange-300 px-3 py-1 text-xs font-semibold text-orange-950">Draft</span>
                                @else
                                    <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Sudah dikirim</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-sm text-neutral-500">
                                Tidak ada santri terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('homeroom.grades.index') }}"
               class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                Kembali
            </a>
            <a href="{{ route('homeroom.grades.edit', $assignment) }}"
               class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                Input / Edit Nilai
            </a>
        </div>
    </div>
</x-app-layout>
