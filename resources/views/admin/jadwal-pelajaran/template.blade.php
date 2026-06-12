<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Template Jadwal Pelajaran</h2>
    </x-slot>

    <div class="space-y-5">
        <div class="overflow-x-auto rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            {{-- Title --}}
            <div class="mb-6 text-center">
                <h3 class="text-lg font-bold uppercase tracking-wide text-teal-950">
                    Jadwal Asatidz / Ustadzat &amp; Mata Pelajaran
                </h3>
                <p class="mt-1 text-sm font-semibold text-neutral-600">
                    Tahun Ajaran {{ $activeAcademicYear?->name ?? '-' }}
                </p>
            </div>

            {{-- Table --}}
            <table class="w-full border-collapse border border-stone-400 text-sm">
                <thead>
                    <tr class="bg-stone-100">
                        <th class="border border-stone-400 px-3 py-3 text-center text-sm font-bold text-neutral-800">
                            NO
                        </th>
                        <th class="border border-stone-400 px-3 py-3 text-center text-sm font-bold text-neutral-800">
                            KELAS
                        </th>
                        @foreach ($days as $day)
                            <th class="border border-stone-400 px-3 py-3 text-center text-sm font-bold text-neutral-800">
                                {{ $day }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($classes as $class)
                        <tr>
                            <td class="border border-stone-400 px-3 py-2 text-center align-top text-sm font-semibold text-neutral-800">
                                {{ $loop->iteration }}
                            </td>
                            <td class="border border-stone-400 px-3 py-2 align-top text-sm font-bold text-neutral-800">
                                {{ $class->level->name }} {{ $class->name }}
                            </td>
                            @foreach ($days as $day)
                                @php
                                    $dayJadwals = $jadwalByClass[$class->id][$day] ?? collect();
                                @endphp
                                <td class="border border-stone-400 px-2 py-2 align-top">
                                    @forelse ($dayJadwals as $jp)
                                        <div class="not-last:mb-2 not-last:border-b not-last:border-dashed not-last:border-stone-300 not-last:pb-2">
                                            <div class="text-xs font-medium text-neutral-700">
                                                {{ $jp->guru->name }}
                                            </div>
                                            <div class="mt-0.5 text-sm font-bold leading-relaxed" dir="rtl">
                                                {{ $jp->mapel->arabic_name ?? $jp->mapel->name }}
                                            </div>
                                        </div>
                                    @empty
                                        <span class="text-xs text-neutral-400">&mdash;</span>
                                    @endforelse
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($days) + 2 }}" class="border border-stone-400 px-3 py-8 text-center text-sm text-neutral-500">
                                Belum ada data jadwal pelajaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Print button --}}
        <div class="flex justify-end">
            <button type="button" onclick="window.print()"
                    class="inline-flex items-center gap-2 rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                <i class="bx bx-printer text-base"></i>
                Cetak Template
            </button>
        </div>
    </div>

    @push('styles')
        <style>
            @media print {
                body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                .sidebar, nav, [x-cloak], button { display: none !important; }
                .overflow-x-auto { overflow: visible !important; }
                table { page-break-inside: auto; }
                tr { page-break-inside: avoid; }
                thead { display: table-header-group; }
            }
        </style>
    @endpush
</x-app-layout>
