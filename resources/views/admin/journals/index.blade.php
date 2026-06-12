<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Monitoring Jurnal Guru</h2>
    </x-slot>

    <div class="space-y-5">
        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <form method="GET" class="grid grid-cols-2 gap-4 md:grid-cols-6">
                <div>
                    <label class="mb-2 block text-sm font-medium text-neutral-700">Jenis</label>
                    <select name="journal_type"
                            class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                        <option value="">Semua</option>
                        @foreach ($journalTypes as $key => $label)
                            <option value="{{ $key }}" {{ request('journal_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-neutral-700">Status</label>
                    <select name="status"
                            class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                        <option value="">Semua</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-neutral-700">Tahun Ajaran</label>
                    <select name="academic_year_id"
                            class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                        <option value="">Semua</option>
                        @foreach ($academicYears as $id => $name)
                            <option value="{{ $id }}" {{ request('academic_year_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-neutral-700">Semester</label>
                    <select name="semester_id"
                            class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                        <option value="">Semua</option>
                        @foreach ($semesters as $id => $name)
                            <option value="{{ $id }}" {{ request('semester_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-neutral-700">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-neutral-700">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                </div>
                <div class="flex items-end gap-2 md:col-span-6">
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

        @if ($journals->isEmpty())
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <p class="text-center text-sm text-neutral-500">Belum ada jurnal.</p>
            </div>
        @else
            <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
                <table class="min-w-full divide-y divide-stone-300">
                    <thead>
                        <tr>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Tanggal</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Guru</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Jenis</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Kelas</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Fan/Mapel</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Status</th>
                            <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-300">
                        @foreach ($journals as $journal)
                            <tr>
                                <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->journal_date->format('d/m/Y') }}</td>
                                <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->teacher->name }}</td>
                                <td class="border-t border-stone-300 px-4 py-3">
                                    <span class="inline-flex rounded-full bg-teal-950 px-3 py-1 text-xs font-semibold text-white">{{ $journalTypes[$journal->journal_type] ?? $journal->journal_type }}</span>
                                </td>
                                <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->schoolClass->level->name }} {{ $journal->schoolClass->name }}</td>
                                <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $journal->teachingAssignment?->subject->name ?? '-' }}</td>
                                <td class="border-t border-stone-300 px-4 py-3">
                                    @if ($journal->status === 'draft')
                                        <span class="inline-flex rounded-full bg-orange-300 px-3 py-1 text-xs font-semibold text-orange-950">Draft</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Submitted</span>
                                    @endif
                                </td>
                                <td class="border-t border-stone-300 px-4 py-3">
                                    <a href="{{ route('admin.journals.show', $journal) }}"
                                       class="text-sm font-medium text-teal-950 underline transition hover:text-emerald-900">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $journals->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
