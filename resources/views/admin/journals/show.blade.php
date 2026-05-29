<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Detail Jurnal</h2>
    </x-slot>

    <div class="space-y-5">
        <div class="rounded-lg bg-slate-50 p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Tanggal</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $journal->journal_date->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Jenis</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $journalTypes[$journal->journal_type] ?? $journal->journal_type }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Guru</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $journal->teacher->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Kelas</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $journal->schoolClass->level->name }} {{ $journal->schoolClass->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Status</p>
                    <p class="mt-1">
                        @if ($journal->status === 'draft')
                            <span class="inline-flex rounded-full bg-orange-300 px-3 py-1 text-xs font-semibold text-orange-950">Draft</span>
                        @else
                            <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Submitted</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <h3 class="mb-4 text-base font-semibold text-teal-950">Informasi Jurnal</h3>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Fan/Mapel</p>
                    <p class="mt-1 text-base text-zinc-900">{{ $journal->teachingAssignment?->subject->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Santri</p>
                    <p class="mt-1 text-base text-zinc-900">{{ $journal->student?->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">NIS</p>
                    <p class="mt-1 text-base text-zinc-900">{{ $journal->student?->nis ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Tahun Ajaran</p>
                    <p class="mt-1 text-base text-zinc-900">{{ $journal->academicYear->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Semester</p>
                    <p class="mt-1 text-base text-zinc-900">{{ $journal->semester->name }}</p>
                </div>
            </div>
        </div>

        @if ($journal->journal_type === 'hafalan')
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <h3 class="mb-4 text-base font-semibold text-teal-950">Hafalan</h3>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div><p class="text-xs font-semibold uppercase text-neutral-500">Jenis Hafalan</p><p class="mt-1 text-base text-zinc-900">{{ $journal->memorization_type ?? '-' }}</p></div>
                    <div><p class="text-xs font-semibold uppercase text-neutral-500">Target Hafalan</p><p class="mt-1 text-base text-zinc-900">{{ $journal->memorization_target ?? '-' }}</p></div>
                    <div><p class="text-xs font-semibold uppercase text-neutral-500">Capaian Hafalan</p><p class="mt-1 text-base text-zinc-900">{{ $journal->memorization_result ?? '-' }}</p></div>
                </div>
            </div>
        @endif

        @if ($journal->journal_type === 'legalisir_kitab')
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <h3 class="mb-4 text-base font-semibold text-teal-950">Legalisir Kitab</h3>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div><p class="text-xs font-semibold uppercase text-neutral-500">Nama Kitab</p><p class="mt-1 text-base text-zinc-900">{{ $journal->kitab_name ?? '-' }}</p></div>
                    <div><p class="text-xs font-semibold uppercase text-neutral-500">Halaman</p><p class="mt-1 text-base text-zinc-900">{{ $journal->kitab_page ?? '-' }}</p></div>
                    <div><p class="text-xs font-semibold uppercase text-neutral-500">Status Legalisir</p><p class="mt-1 text-base text-zinc-900">{{ $journal->legalization_status ?? '-' }}</p></div>
                </div>
            </div>
        @endif

        @if (in_array($journal->journal_type, ['nilai_harian', 'tamrinan']))
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <h3 class="mb-4 text-base font-semibold text-teal-950">Nilai</h3>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div><p class="text-xs font-semibold uppercase text-neutral-500">Nilai Harian</p><p class="mt-1 text-base text-zinc-900">{{ $journal->daily_score ?? '-' }}</p></div>
                    <div><p class="text-xs font-semibold uppercase text-neutral-500">Nilai Ujian / Tamrinan</p><p class="mt-1 text-base text-zinc-900">{{ $journal->exam_score ?? '-' }}</p></div>
                    <div><p class="text-xs font-semibold uppercase text-neutral-500">Predikat</p><p class="mt-1 text-base text-zinc-900">{{ $journal->predicate ?? '-' }}</p></div>
                </div>
            </div>
        @endif

        @if ($journal->note)
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <h3 class="mb-4 text-base font-semibold text-teal-950">Catatan</h3>
                <p class="text-base text-zinc-900 whitespace-pre-wrap">{{ $journal->note }}</p>
            </div>
        @endif

        <div>
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                Kembali
            </a>
        </div>
    </div>
</x-app-layout>
