<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Riwayat Jurnal {{ $labels[$journalType] }}</h2>
    </x-slot>

    <div class="space-y-5">
        @if (session('success'))
            <div class="rounded-sm bg-emerald-200 px-4 py-3 text-sm font-medium text-green-950">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Nama Santri</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $student->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">NIS</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $student->nis }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Kelas</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $homeroom->schoolClass->level->name }} {{ $homeroom->schoolClass->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Jenis Jurnal</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $labels[$journalType] }}</p>
                </div>
            </div>
        </div>

        @if ($journals->isEmpty())
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <p class="text-center text-sm text-neutral-500">Belum ada jurnal {{ $labels[$journalType] }} untuk santri ini.</p>
            </div>
        @else
            <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
                <table class="min-w-full divide-y divide-stone-300">
                    <thead>
                        <tr>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Tanggal</th>
                            @if ($journalType === 'hafalan')
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Jenis Hafalan</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Target</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Capaian</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Predikat</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Keterangan</th>
                            @elseif ($journalType === 'legalisir_kitab')
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Kitab</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Halaman</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Status Legalisir</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Keterangan</th>
                            @elseif ($journalType === 'nilai_harian')
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Nilai</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Predikat</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Keterangan</th>
                            @elseif ($journalType === 'tamrinan')
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Nilai</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Predikat</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Keterangan</th>
                            @elseif ($journalType === 'catatan')
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Catatan</th>
                            @endif
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Status</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-300">
                        @foreach ($journals as $journal)
                            <tr>
                                <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->journal_date->format('d/m/Y') }}</td>

                                @if ($journalType === 'hafalan')
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->memorization_type ?? '-' }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->memorization_target ?? '-' }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->memorization_result ?? '-' }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3">
                                        @if ($journal->predicate)
                                            <span class="inline-flex rounded-full bg-teal-950 px-3 py-1 text-xs font-semibold text-white">{{ $journal->predicate }}</span>
                                        @else
                                            <span class="text-neutral-400">-</span>
                                        @endif
                                    </td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->note ?? '-' }}</td>
                                @elseif ($journalType === 'legalisir_kitab')
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->kitab_name ?? '-' }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->kitab_page ?? '-' }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3">
                                        @if ($journal->legalization_status)
                                            <span class="inline-flex rounded-full bg-orange-300 px-3 py-1 text-xs font-semibold text-orange-950">{{ $journal->legalization_status }}</span>
                                        @else
                                            <span class="text-neutral-400">-</span>
                                        @endif
                                    </td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->note ?? '-' }}</td>
                                @elseif ($journalType === 'nilai_harian')
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->daily_score ?? '-' }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3">
                                        @if ($journal->predicate)
                                            <span class="inline-flex rounded-full bg-teal-950 px-3 py-1 text-xs font-semibold text-white">{{ $journal->predicate }}</span>
                                        @else
                                            <span class="text-neutral-400">-</span>
                                        @endif
                                    </td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->note ?? '-' }}</td>
                                @elseif ($journalType === 'tamrinan')
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->exam_score ?? '-' }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3">
                                        @if ($journal->predicate)
                                            <span class="inline-flex rounded-full bg-teal-950 px-3 py-1 text-xs font-semibold text-white">{{ $journal->predicate }}</span>
                                        @else
                                            <span class="text-neutral-400">-</span>
                                        @endif
                                    </td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->note ?? '-' }}</td>
                                @elseif ($journalType === 'catatan')
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ Str::limit($journal->note, 80) ?? '-' }}</td>
                                @endif

                                <td class="border-t border-stone-300 px-4 py-3">
                                    @if ($journal->status === 'draft')
                                        <span class="inline-flex rounded-full bg-orange-300 px-3 py-1 text-xs font-semibold text-orange-950">Draft</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Submitted</span>
                                    @endif
                                </td>
                                <td class="border-t border-stone-300 px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('homeroom.journals.show', $journal) }}"
                                           class="text-sm font-medium text-teal-950 underline transition hover:text-emerald-900">Detail</a>
                                        <a href="{{ route('homeroom.journals.edit', $journal) }}"
                                           class="text-sm font-medium text-teal-950 underline transition hover:text-emerald-900">Edit</a>
                                        <form action="{{ route('homeroom.journals.destroy', $journal) }}" method="POST"
                                              onsubmit="return confirm('Hapus jurnal ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="text-sm font-medium text-red-700 underline transition hover:text-red-900">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $journals->withQueryString()->links() }}
            </div>
        @endif

        <div class="flex items-center gap-3">
            <a href="{{ route('homeroom.journals.create', [
                'journalType' => $journalType,
                'student' => $student->id,
            ]) }}"
               class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                + Tambah Jurnal
            </a>
            <a href="{{ route('homeroom.journals.students', $journalType) }}"
               class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                Kembali
            </a>
        </div>
    </div>
</x-app-layout>
