<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Riwayat Absensi {{ $student->name }}</h2>
    </x-slot>

    <div class="space-y-5">
        <div class="rounded-lg bg-slate-50 p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
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
                    <p class="text-xs font-semibold uppercase text-neutral-500">Tahun Ajaran</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $homeroom->academicYear->name }} / {{ $homeroom->semester->name }}</p>
                </div>
            </div>
        </div>

        @if ($attendances->isEmpty())
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <p class="text-center text-sm text-neutral-500">Belum ada riwayat absensi untuk santri ini.</p>
            </div>
        @else
            <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
                <table class="min-w-full divide-y divide-stone-300">
                    <thead>
                        <tr>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Tanggal</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Tipe</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Status</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-300">
                        @foreach ($attendances as $attendance)
                            @php $detail = $attendance->details->first(); @endphp
                            <tr>
                                <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $attendance->attendance_date->format('d/m/Y') }}</td>
                                <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">Homeroom</td>
                                <td class="border-t border-stone-300 px-4 py-3">
                                    @if ($detail)
                                        @if ($detail->status === 'present')
                                            <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">{{ $statusLabels['present'] }}</span>
                                        @elseif ($detail->status === 'permission')
                                            <span class="inline-flex rounded-full bg-orange-300 px-3 py-1 text-xs font-semibold text-orange-950">{{ $statusLabels['permission'] }}</span>
                                        @elseif ($detail->status === 'sick')
                                            <span class="inline-flex rounded-full bg-orange-300 px-3 py-1 text-xs font-semibold text-orange-950">{{ $statusLabels['sick'] }}</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-red-200 px-3 py-1 text-xs font-semibold text-red-950">{{ $statusLabels['absent'] }}</span>
                                        @endif
                                    @else
                                        <span class="text-neutral-400">-</span>
                                    @endif
                                </td>
                                <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $detail?->note ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $attendances->withQueryString()->links() }}
            </div>
        @endif

        <div class="flex items-center gap-3">
            <a href="{{ route('homeroom.attendances.index') }}"
               class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                Kembali
            </a>
        </div>
    </div>
</x-app-layout>
