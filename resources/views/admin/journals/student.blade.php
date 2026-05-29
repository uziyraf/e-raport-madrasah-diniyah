<x-app-layout>
    <x-slot name="header">
        @php
            $classDisplayName = str_starts_with($schoolClass->name, $schoolClass->level->name)
                ? $schoolClass->name
                : $schoolClass->level->name . ' ' . $schoolClass->name;
        @endphp
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.journals.type', [$schoolClass, $journalType]) }}"
               class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-3 py-2 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                &larr; Kembali
            </a>
            <h2 class="text-xl font-bold text-teal-950">
                Riwayat {{ $journalTypes[$journalType] ?? $journalType }} &mdash; {{ $student->name }}
            </h2>
        </div>
    </x-slot>

    <div class="space-y-5">
        <div class="rounded-lg bg-white p-5 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <div class="grid grid-cols-2 gap-4 text-sm text-neutral-600 md:grid-cols-4">
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-400">Santri</p>
                    <p class="mt-0.5 font-medium text-zinc-900">{{ $student->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-400">NIS</p>
                    <p class="mt-0.5 font-medium text-zinc-900">{{ $student->nis }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-400">Kelas</p>
                    <p class="mt-0.5 font-medium text-zinc-900">{{ $classDisplayName }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-400">Jenis Jurnal</p>
                    <p class="mt-0.5 font-medium text-zinc-900">{{ $journalTypes[$journalType] ?? $journalType }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-400">Tahun Ajaran</p>
                    <p class="mt-0.5 font-medium text-zinc-900">{{ $activeYear->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-400">Semester</p>
                    <p class="mt-0.5 font-medium text-zinc-900">{{ $activeSemester->name }}</p>
                </div>
            </div>
        </div>

        @if ($journals->isEmpty())
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <p class="text-center text-sm text-neutral-500">Belum ada jurnal {{ $journalTypes[$journalType] ?? $journalType }} untuk santri ini.</p>
            </div>
        @else
            <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
                <table class="min-w-full divide-y divide-stone-300">
                    <thead>
                        <tr>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">No</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Tanggal</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Guru</th>

                            @if ($journalType === 'hafalan')
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Jenis Hafalan</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Target</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Capaian</th>
                            @elseif ($journalType === 'legalisir_kitab')
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Nama Kitab</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Halaman</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Status Legalisir</th>
                            @elseif (in_array($journalType, ['nilai_harian', 'tamrinan']))
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Fan/Mapel</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-center text-sm font-medium text-neutral-700">
                                    {{ $journalType === 'tamrinan' ? 'Nilai Ujian' : 'Nilai Harian' }}
                                </th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-center text-sm font-medium text-neutral-700">Predikat</th>
                            @elseif ($journalType === 'catatan')
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Catatan</th>
                            @endif

                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Status</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-300">
                        @foreach ($journals as $index => $journal)
                            <tr>
                                <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journals->firstItem() + $index }}</td>
                                <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->journal_date->format('d/m/Y') }}</td>
                                <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->teacher->name }}</td>

                                @if ($journalType === 'hafalan')
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->memorization_type ?? '-' }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->memorization_target ?? '-' }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->memorization_result ?? '-' }}</td>
                                @elseif ($journalType === 'legalisir_kitab')
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->kitab_name ?? '-' }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->kitab_page ?? '-' }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3">
                                        @if ($journal->legalization_status)
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                                {{ $journal->legalization_status === 'legal' ? 'bg-emerald-200 text-green-950' : 'bg-orange-300 text-orange-950' }}">
                                                {{ $journal->legalization_status === 'legal' ? 'Legal' : 'Belum Legal' }}
                                            </span>
                                        @else
                                            <span class="text-base text-zinc-900">-</span>
                                        @endif
                                    </td>
                                @elseif (in_array($journalType, ['nilai_harian', 'tamrinan']))
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->teachingAssignment?->subject->name ?? '-' }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-center text-base text-zinc-900">{{ $journal->exam_score ?? $journal->daily_score ?? '-' }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-center text-base text-zinc-900">{{ $journal->predicate ?? '-' }}</td>
                                @elseif ($journalType === 'catatan')
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900 max-w-xs truncate">{{ $journal->note ?? '-' }}</td>
                                @endif

                                <td class="border-t border-stone-300 px-4 py-3">
                                    @if ($journal->status === 'draft')
                                        <span class="inline-flex rounded-full bg-orange-300 px-3 py-1 text-xs font-semibold text-orange-950">Draft</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Submitted</span>
                                    @endif
                                </td>
                                <td class="border-t border-stone-300 px-4 py-3">
                                    <a href="{{ route('admin.journals.show', $journal) }}"
                                       class="text-sm font-medium text-teal-950 underline transition hover:text-emerald-900">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $journals->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
