<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.attendances.class', $schoolClass) }}"
               class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-3 py-2 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                &larr; Kembali
            </a>
            <h2 class="text-xl font-bold text-teal-950">
                Rekap Absensi Wali Kelas &mdash; {{ $schoolClass->level->name }} {{ $schoolClass->name }}
            </h2>
        </div>
    </x-slot>

    <div class="space-y-5">
        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <div class="text-sm text-neutral-600">
                Tahun Ajaran: <span class="font-semibold text-teal-950">{{ $activeYear->name }}</span>
                &mdash; Semester: <span class="font-semibold text-teal-950">{{ $activeSemester->name }}</span>
                &mdash; Kelas: <span class="font-semibold text-teal-950">{{ $schoolClass->level->name }} {{ $schoolClass->name }}</span>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
            <table class="min-w-full divide-y divide-stone-300">
                <thead>
                    <tr>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">No</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Nama Santri</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">NIS</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-center text-sm font-medium text-neutral-700">Hadir</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-center text-sm font-medium text-neutral-700">Izin</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-center text-sm font-medium text-neutral-700">Sakit</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-center text-sm font-medium text-neutral-700">Alfa</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-center text-sm font-medium text-neutral-700">Total</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-center text-sm font-medium text-neutral-700">% Hadir</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-300">
                    @forelse ($students as $index => $student)
                        @php
                            $summary = $summaryQuery->get($student->id);
                            $total = $summary ? $summary->total_sessions : 0;
                            $present = $summary ? $summary->present_count : 0;
                            $permission = $summary ? $summary->permission_count : 0;
                            $sick = $summary ? $summary->sick_count : 0;
                            $absent = $summary ? $summary->absent_count : 0;
                            $percentage = $total > 0 ? round(($present / $total) * 100) : 0;
                        @endphp
                        <tr>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $students->firstItem() + $index }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base font-medium text-zinc-900">{{ $student->name }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $student->nis }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-center text-base text-zinc-900">{{ $present }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-center text-base text-zinc-900">{{ $permission }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-center text-base text-zinc-900">{{ $sick }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-center text-base text-zinc-900">{{ $absent }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-center text-base font-semibold text-zinc-900">{{ $total }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-center">
                                @if ($percentage >= 80)
                                    <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">{{ $percentage }}%</span>
                                @elseif ($percentage >= 50)
                                    <span class="inline-flex rounded-full bg-orange-300 px-3 py-1 text-xs font-semibold text-orange-950">{{ $percentage }}%</span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-200 px-3 py-1 text-xs font-semibold text-red-950">{{ $percentage }}%</span>
                                @endif
                            </td>
                            <td class="border-t border-stone-300 px-4 py-3">
                                <a href="{{ route('admin.attendances.student', [$schoolClass, $student]) }}"
                                   class="text-sm font-medium text-teal-950 underline transition hover:text-emerald-900">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-10 text-center text-sm text-neutral-500">Belum ada santri terdaftar di kelas ini.</td>
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
