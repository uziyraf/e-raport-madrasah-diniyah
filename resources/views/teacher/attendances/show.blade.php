<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Detail Absensi</h2>
    </x-slot>

    <div class="space-y-5">
        @if (session('success'))
            <div class="rounded-sm bg-emerald-200 px-4 py-3 text-sm font-medium text-green-950">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-lg bg-slate-50 p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Tanggal</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $session->attendance_date->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Fan/Mapel</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $session->teachingAssignment?->subject->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Kelas</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $session->schoolClass->level->name }} {{ $session->schoolClass->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Tahun Ajaran</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $session->academicYear->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Semester</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $session->semester->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Guru</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $session->teacher->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Status</p>
                    <p class="mt-1">
                        @if ($session->status === 'draft')
                            <span class="inline-flex rounded-full bg-orange-300 px-3 py-1 text-xs font-semibold text-orange-950">Draft</span>
                        @else
                            <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Submitted</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        @if ($session->details->isEmpty())
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <p class="text-center text-sm text-neutral-500">Belum ada data absensi.</p>
            </div>
        @else
            <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
                <table class="min-w-full divide-y divide-stone-300">
                    <thead>
                        <tr>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">No</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Nama Santri</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">NIS</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Status</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-300">
                        @foreach ($session->details as $index => $detail)
                            <tr>
                                <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $index + 1 }}</td>
                                <td class="border-t border-stone-300 px-4 py-3 text-base font-medium text-zinc-900">
                                    <a href="{{ route('teacher.attendances.student', [
                                        'student' => $detail->student,
                                        'teaching_assignment_id' => $session->teaching_assignment_id,
                                    ]) }}"
                                       class="text-teal-950 underline transition hover:text-emerald-900">
                                        {{ $detail->student->name }}
                                    </a>
                                </td>
                                <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $detail->student->nis }}</td>
                                <td class="border-t border-stone-300 px-4 py-3">
                                    @if ($detail->status === 'present')
                                        <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">{{ $labels['present'] }}</span>
                                    @elseif ($detail->status === 'permission')
                                        <span class="inline-flex rounded-full bg-orange-300 px-3 py-1 text-xs font-semibold text-orange-950">{{ $labels['permission'] }}</span>
                                    @elseif ($detail->status === 'sick')
                                        <span class="inline-flex rounded-full bg-orange-300 px-3 py-1 text-xs font-semibold text-orange-950">{{ $labels['sick'] }}</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-red-200 px-3 py-1 text-xs font-semibold text-red-950">{{ $labels['absent'] }}</span>
                                    @endif
                                </td>
                                <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $detail->note ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="flex items-center gap-3">
            <a href="{{ route('teacher.attendances.edit', $session) }}"
               class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                Edit Absensi
            </a>
            <a href="{{ route('teacher.attendances.index', ['teaching_assignment_id' => $session->teaching_assignment_id]) }}"
               class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                Kembali
            </a>
        </div>
    </div>
</x-app-layout>
