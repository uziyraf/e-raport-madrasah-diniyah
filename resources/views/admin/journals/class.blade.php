<x-app-layout>
    <x-slot name="header">
        @php
            $classDisplayName = str_starts_with($schoolClass->name, $schoolClass->level->name)
                ? $schoolClass->name
                : $schoolClass->level->name . ' ' . $schoolClass->name;
        @endphp
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.journals.index') }}"
               class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-3 py-2 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                &larr; Kembali
            </a>
            <h2 class="text-xl font-bold text-teal-950">{{ $classDisplayName }}</h2>
        </div>
    </x-slot>

    <div class="space-y-5">
        <div class="rounded-lg bg-white p-5 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <div class="text-sm text-neutral-600">
                Tahun Ajaran: <span class="font-semibold text-teal-950">{{ $activeYear->name }}</span>
                &mdash; Semester: <span class="font-semibold text-teal-950">{{ $activeSemester->name }}</span>
                &mdash; Kelas: <span class="font-semibold text-teal-950">{{ $classDisplayName }}</span>
                &mdash; Santri: <span class="font-semibold text-teal-950">{{ $enrollmentTotal }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($journalTypes as $key => $label)
                @php $stats = $typeStats[$key]; @endphp
                <div class="flex flex-col rounded-lg bg-white p-5 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <div>
                        <h3 class="text-base font-bold text-teal-950">{{ $label }}</h3>
                        <p class="mt-0.5 text-xs text-neutral-400">{{ $stats['total_records'] }} catatan &middot; {{ $stats['student_count'] }} santri</p>
                    </div>

                    @if ($stats['total_records'] > 0)
                        <div class="mt-3 flex flex-wrap gap-2">
                            @if ($stats['submitted_count'] > 0)
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-200 px-2.5 py-0.5 text-xs font-semibold text-green-950">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                    {{ $stats['submitted_count'] }} Dikirim
                                </span>
                            @endif
                            @if ($stats['draft_count'] > 0)
                                <span class="inline-flex items-center gap-1 rounded-full bg-orange-300 px-2.5 py-0.5 text-xs font-semibold text-orange-950">
                                    <span class="h-1.5 w-1.5 rounded-full bg-orange-600"></span>
                                    {{ $stats['draft_count'] }} Draft
                                </span>
                            @endif
                        </div>

                        <div class="mt-3 text-xs text-neutral-400">
                            @if ($stats['latest_date'])
                                Terakhir: {{ \Carbon\Carbon::parse($stats['latest_date'])->format('d/m/Y') }}
                            @endif
                        </div>
                    @else
                        <div class="mt-3">
                            <span class="inline-flex items-center rounded-full bg-zinc-200 px-2.5 py-0.5 text-xs font-semibold text-neutral-700">
                                Belum ada data
                            </span>
                        </div>
                    @endif

                    <div class="mt-auto border-t border-stone-100 pt-4">
                        <a href="{{ route('admin.journals.type', [$schoolClass, $key]) }}"
                           class="inline-flex w-full items-center justify-center rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                            Lihat Santri
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
