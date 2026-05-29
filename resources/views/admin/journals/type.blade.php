<x-app-layout>
    <x-slot name="header">
        @php
            $classDisplayName = str_starts_with($schoolClass->name, $schoolClass->level->name)
                ? $schoolClass->name
                : $schoolClass->level->name . ' ' . $schoolClass->name;
        @endphp
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.journals.class', $schoolClass) }}"
               class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-3 py-2 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                &larr; Kembali
            </a>
            <h2 class="text-xl font-bold text-teal-950">
                {{ $journalTypes[$journalType] ?? $journalType }} &mdash; {{ $classDisplayName }}
            </h2>
        </div>
    </x-slot>

    <div class="space-y-5">
        <div class="rounded-lg bg-white p-5 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <div class="text-sm text-neutral-600">
                Tahun Ajaran: <span class="font-semibold text-teal-950">{{ $activeYear->name }}</span>
                &mdash; Semester: <span class="font-semibold text-teal-950">{{ $activeSemester->name }}</span>
                &mdash; Kelas: <span class="font-semibold text-teal-950">{{ $classDisplayName }}</span>
                &mdash; Jenis: <span class="font-semibold text-teal-950">{{ $journalTypes[$journalType] ?? $journalType }}</span>
            </div>
        </div>

        <div class="rounded-lg bg-white p-5 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <form method="GET" class="flex items-center gap-3">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama santri atau NIS..."
                           class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                </div>
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                    Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('admin.journals.type', [$schoolClass, $journalType]) }}"
                       class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
            <table class="min-w-full divide-y divide-stone-300">
                <thead>
                    <tr>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">No</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Nama Santri</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">NIS</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-center text-sm font-medium text-neutral-700">Jumlah Jurnal</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Terakhir</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-300">
                    @forelse ($students as $index => $student)
                        @php
                            $stats = $studentJournalCounts->get($student->id);
                        @endphp
                        <tr>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $students->firstItem() + $index }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base font-medium text-zinc-900">{{ $student->name }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $student->nis }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-center text-base text-zinc-900">{{ $stats ? $stats->total_records : 0 }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">
                                {{ $stats && $stats->latest_date ? \Carbon\Carbon::parse($stats->latest_date)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="border-t border-stone-300 px-4 py-3">
                                <a href="{{ route('admin.journals.student', [$schoolClass, $journalType, $student]) }}"
                                   class="text-sm font-medium text-teal-950 underline transition hover:text-emerald-900">Riwayat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-neutral-500">Tidak ada santri di kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $students->links() }}
        </div>
    </div>
</x-app-layout>
