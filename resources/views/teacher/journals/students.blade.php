<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Santri - Jurnal {{ $labels[$journalType] }}</h2>
    </x-slot>

    <div class="space-y-5">
        @if (session('success'))
            <div class="rounded-sm bg-emerald-200 px-4 py-3 text-sm font-medium text-green-950">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-lg bg-slate-50 p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Fan/Mapel</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $assignment->subject->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Kelas</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $assignment->schoolClass->level->name }} {{ $assignment->schoolClass->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Tahun Ajaran</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $assignment->academicYear->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Semester</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $assignment->semester->name }}</p>
                </div>
            </div>
        </div>

        @if ($students->isEmpty())
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <p class="text-center text-sm text-neutral-500">Tidak ada santri di kelas ini.</p>
            </div>
        @else
            <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
                <table class="min-w-full divide-y divide-stone-300">
                    <thead>
                        <tr>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Nama Santri</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">NIS</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Total Jurnal</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Jurnal Terakhir</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Status Terakhir</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-300">
                        @foreach ($students as $item)
                            <tr>
                                <td class="border-t border-stone-300 px-4 py-3 text-base font-medium text-zinc-900">{{ $item->student->name }}</td>
                                <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $item->student->nis }}</td>
                                <td class="border-t border-stone-300 px-4 py-3">
                                    <span class="inline-flex items-center justify-center rounded-full bg-teal-950 px-3 py-1 text-xs font-semibold text-white">
                                        {{ $item->total_journals }}
                                    </span>
                                </td>
                                <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">
                                    {{ $item->latest_date ? \Carbon\Carbon::parse($item->latest_date)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="border-t border-stone-300 px-4 py-3">
                                    @if ($item->latest_status === 'draft')
                                        <span class="inline-flex rounded-full bg-orange-300 px-3 py-1 text-xs font-semibold text-orange-950">Draft</span>
                                    @elseif ($item->latest_status === 'submitted')
                                        <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Submitted</span>
                                    @else
                                        <span class="text-neutral-400">-</span>
                                    @endif
                                </td>
                                <td class="border-t border-stone-300 px-4 py-3">
                                    <a href="{{ route('teacher.journals.student', [
                                        'journalType' => $journalType,
                                        'student' => $item->student->id,
                                        'teaching_assignment_id' => $assignment->id,
                                    ]) }}"
                                       class="text-sm font-medium text-teal-950 underline transition hover:text-emerald-900">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="flex items-center gap-3">
            <a href="{{ route('teacher.journals.index', ['teaching_assignment_id' => $assignment->id]) }}"
               class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                Kembali
            </a>
        </div>
    </div>
</x-app-layout>
