<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Nilai Sikap</h2>
    </x-slot>

    <div class="space-y-5">
        @if (session('success'))
            <div class="rounded-sm bg-emerald-200 px-4 py-3 text-sm font-medium text-green-950">
                {{ session('success') }}
            </div>
        @endif

        @if (!$homeroom)
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <p class="text-center text-sm text-neutral-500">
                    Anda belum ditugaskan sebagai wali kelas untuk tahun ajaran dan semester aktif.
                </p>
            </div>
        @else
            <div class="rounded-lg bg-slate-50 p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
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
                    <div>
                        <p class="text-xs font-semibold uppercase text-neutral-500">Total Santri</p>
                        <p class="mt-1 text-base font-medium text-zinc-900">{{ $students->count() }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-neutral-500">Terisi</p>
                        <p class="mt-1 text-base font-medium {{ $filledCount === $students->count() ? 'text-green-950' : 'text-orange-950' }}">
                            {{ $filledCount }}/{{ $students->count() }}
                        </p>
                    </div>
                </div>
            </div>

            @if ($students->isEmpty())
                <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <p class="text-center text-sm text-neutral-500">
                        Tidak ada santri terdaftar di kelas ini.
                    </p>
                </div>
            @else
                <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <table class="min-w-full divide-y divide-stone-300">
                        <thead>
                            <tr>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700 w-8">No</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Nama Santri</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">NIS</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Akhlak</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Kedisiplinan</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Kebersihan</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Status</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-300">
                            @forelse ($students as $enrollment)
                                @php
                                    $attitude = $existingAttitudes->get($enrollment->student_id);
                                @endphp
                                <tr>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $loop->iteration }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base font-medium text-zinc-900">{{ $enrollment->student->name }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $enrollment->student->nis }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $attitude?->akhlak ?? '-' }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $attitude?->discipline ?? '-' }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $attitude?->cleanliness ?? '-' }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3">
                                        @if (!$attitude)
                                            <span class="inline-flex rounded-full bg-zinc-200 px-3 py-1 text-xs font-semibold text-neutral-700">Belum diisi</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Sudah diisi</span>
                                        @endif
                                    </td>
                                    <td class="border-t border-stone-300 px-4 py-3">
                                        <a href="{{ route('homeroom.attitudes.edit', $enrollment->student_id) }}"
                                           class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-3 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                                            {{ $attitude ? 'Edit' : 'Input' }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-10 text-center text-sm text-neutral-500">
                                        Tidak ada santri terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
