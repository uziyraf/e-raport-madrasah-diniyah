<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-3 py-2 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                &larr; Kembali
            </a>
            <h2 class="text-xl font-bold text-teal-950">
                Riwayat Absensi &mdash; {{ $student->name }}
            </h2>
        </div>
    </x-slot>

    <div class="space-y-5">
        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <div class="text-sm text-neutral-600">
                Tahun Ajaran: <span class="font-semibold text-teal-950">{{ $activeYear->name }}</span>
                &mdash; Semester: <span class="font-semibold text-teal-950">{{ $activeSemester->name }}</span>
                &mdash; Kelas: <span class="font-semibold text-teal-950">{{ $schoolClass->level->name }} {{ $schoolClass->name }}</span>
                &mdash; Santri: <span class="font-semibold text-teal-950">{{ $student->name }}</span>
                &mdash; NIS: <span class="font-semibold text-teal-950">{{ $student->nis }}</span>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
            <table class="min-w-full divide-y divide-stone-300">
                <thead>
                    <tr>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">No</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Tanggal</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Tipe</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Fan/Mapel</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Guru</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Status</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-300">
                    @forelse ($attendances as $index => $session)
                        @php
                            $detail = $session->details->first();
                        @endphp
                        <tr>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $attendances->firstItem() + $index }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $session->attendance_date->format('d/m/Y') }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $attendanceTypes[$session->attendance_type] ?? $session->attendance_type }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">
                                {{ $session->teachingAssignment?->subject->name ?? '-' }}
                            </td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $session->teacher?->name ?? '-' }}</td>
                            <td class="border-t border-stone-300 px-4 py-3">
                                @php
                                    $statusClass = match ($detail?->status) {
                                        'present' => 'bg-emerald-200 text-green-950',
                                        'permission' => 'bg-orange-300 text-orange-950',
                                        'sick' => 'bg-amber-200 text-amber-950',
                                        'absent' => 'bg-red-200 text-red-950',
                                        default => 'bg-zinc-200 text-neutral-700',
                                    };
                                @endphp
                                @if ($detail)
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                        {{ $statusLabels[$detail->status] ?? $detail->status }}
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-zinc-200 px-3 py-1 text-xs font-semibold text-neutral-700">-</span>
                                @endif
                            </td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $detail?->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-sm text-neutral-500">Belum ada riwayat absensi untuk santri ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $attendances->links() }}
        </div>
    </div>
</x-app-layout>
