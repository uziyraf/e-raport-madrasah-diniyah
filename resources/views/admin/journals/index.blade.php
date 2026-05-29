<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Monitoring Jurnal Guru</h2>
    </x-slot>

    <div class="space-y-5">
        <div class="rounded-lg bg-white p-5 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <form method="GET" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-700">Jenjang</label>
                        <select name="level_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">Semua Jenjang</option>
                            @foreach ($levels as $id => $name)
                                <option value="{{ $id }}" {{ request('level_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-700">Tingkat</label>
                        <select name="grade_level"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">Semua Tingkat</option>
                            @foreach ($gradeLevels as $level)
                                <option value="{{ $level }}" {{ request('grade_level') == $level ? 'selected' : '' }}>{{ $level }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-neutral-700">Cari Kelas</label>
                        <input type="text" name="keyword" value="{{ request('keyword') }}"
                               placeholder="Cari nama kelas, kode, jenjang..."
                               class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                        Filter
                    </button>
                    <a href="{{ route('admin.journals.index') }}"
                       class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($classes as $class)
                @php
                    $enrollmentTotal = $enrollmentCounts->get($class->id, 0);
                    $classStats = $classJournalCounts->get($class->id);
                    $totalJournals = $classStats->total ?? 0;
                    $latestDate = $classStats->latest_date ?? null;

                    $typeCounts = $journalTotals->get($class->id, collect());
                    $classDisplayName = str_starts_with($class->name, $class->level->name)
                        ? $class->name
                        : $class->level->name . ' ' . $class->name;
                @endphp

                <div class="flex flex-col rounded-lg bg-white p-5 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <h3 class="truncate text-base font-bold text-teal-950">{{ $classDisplayName }}</h3>
                            <p class="mt-0.5 text-xs text-neutral-400">{{ $activeYear->name }} &mdash; {{ $activeSemester->name }}</p>
                        </div>
                        <span class="inline-flex shrink-0 items-center justify-center rounded-full bg-teal-950 px-2.5 py-0.5 text-xs font-semibold text-white">
                            {{ $enrollmentTotal }}
                        </span>
                    </div>

                    <div class="mt-3 flex flex-wrap gap-1.5">
                        @foreach ($journalTypes as $key => $label)
                            @php $count = $typeCounts->where('journal_type', $key)->sum('total'); @endphp
                            @if ($count > 0)
                                <span class="inline-flex items-center rounded-full bg-teal-950/10 px-2.5 py-0.5 text-xs font-medium text-teal-950">
                                    {{ $label }} {{ $count }}
                                </span>
                            @endif
                        @endforeach
                        @if ($totalJournals === 0)
                            <span class="inline-flex items-center rounded-full bg-zinc-200 px-2.5 py-0.5 text-xs font-semibold text-neutral-700">
                                Belum ada jurnal
                            </span>
                        @endif
                    </div>

                    <div class="mt-4 flex items-center justify-between border-t border-stone-100 pt-4">
                        <span class="text-xs text-neutral-400">
                            @if ($totalJournals > 0)
                                {{ $totalJournals }} jurnal
                                @if ($latestDate)
                                    &middot; Terakhir: {{ \Carbon\Carbon::parse($latestDate)->format('d/m/Y') }}
                                @endif
                            @else
                                Belum ada jurnal
                            @endif
                        </span>
                        <a href="{{ route('admin.journals.class', $class) }}"
                           class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                            Lihat Jurnal
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <p class="text-center text-sm text-neutral-500">Tidak ada kelas yang sesuai.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $classes->links() }}
        </div>
    </div>
</x-app-layout>
