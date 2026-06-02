<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Raport Santri</h2>
    </x-slot>

    <div class="space-y-5">
        @if (!$homeroom)
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <p class="text-center text-sm text-neutral-500">
                    Anda belum ditugaskan sebagai wali kelas untuk tahun ajaran dan semester aktif.
                </p>
            </div>
        @else
            <div class="rounded-lg bg-slate-50 p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase text-neutral-500">Kelas</p>
                        <p class="mt-1 text-base font-medium text-zinc-900">{{ $homeroom->schoolClass->level->name }} {{ $homeroom->schoolClass->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-neutral-500">Tahun Ajaran</p>
                        <p class="mt-1 text-base font-medium text-zinc-900">{{ $homeroom->academicYear->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-neutral-500">Semester</p>
                        <p class="mt-1 text-base font-medium text-zinc-900">{{ $homeroom->semester->name }}</p>
                    </div>
                </div>
            </div>

            @if ($students->isEmpty())
                <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <p class="text-center text-sm text-neutral-500">
                        Belum ada santri terdaftar di kelas ini.
                    </p>
                </div>
            @else
                <div class="overflow-hidden rounded-lg bg-white shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-stone-200 bg-stone-50">
                                <th class="px-4 py-3 font-semibold text-neutral-600">No</th>
                                <th class="px-4 py-3 font-semibold text-neutral-600">NIS</th>
                                <th class="px-4 py-3 font-semibold text-neutral-600">Nama</th>
                                <th class="px-4 py-3 font-semibold text-neutral-600">Nama Arab</th>
                                <th class="px-4 py-3 font-semibold text-neutral-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200">
                            @foreach ($students as $index => $enrollment)
                                <tr class="hover:bg-stone-50">
                                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-medium text-zinc-900">{{ $enrollment->student->nis }}</td>
                                    <td class="px-4 py-3 text-zinc-900">{{ $enrollment->student->name }}</td>
                                    <td class="px-4 py-3 text-zinc-900">{{ $enrollment->student->arabic_name ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('homeroom.report-cards.show', $enrollment->student) }}"
                                           class="inline-flex items-center gap-1 rounded-sm bg-slate-50 px-3 py-2 text-xs font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                                            Preview Raport
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
