<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Monitoring Nilai</h2>
    </x-slot>

    <div class="space-y-5">
        <form method="GET" action="{{ route('admin.grades.index') }}">
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-5">
                    <div>
                        <label for="academic_year_id" class="mb-2 block text-sm font-medium text-neutral-700">Tahun Ajaran</label>
                        <select name="academic_year_id" id="academic_year_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">Semua</option>
                            @foreach ($academicYears as $id => $name)
                                <option value="{{ $id }}" {{ request('academic_year_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="semester_id" class="mb-2 block text-sm font-medium text-neutral-700">Semester</label>
                        <select name="semester_id" id="semester_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">Semua</option>
                            @foreach ($semesters as $id => $name)
                                <option value="{{ $id }}" {{ request('semester_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="school_class_id" class="mb-2 block text-sm font-medium text-neutral-700">Kelas</label>
                        <select name="school_class_id" id="school_class_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">Semua</option>
                            @foreach ($classes as $id => $label)
                                <option value="{{ $id }}" {{ request('school_class_id') == $id ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="subject_id" class="mb-2 block text-sm font-medium text-neutral-700">Fan/Mapel</label>
                        <select name="subject_id" id="subject_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">Semua</option>
                            @foreach ($subjects as $id => $name)
                                <option value="{{ $id }}" {{ request('subject_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="teacher_id" class="mb-2 block text-sm font-medium text-neutral-700">Guru</label>
                        <select name="teacher_id" id="teacher_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">Semua</option>
                            @foreach ($teachers as $id => $name)
                                <option value="{{ $id }}" {{ request('teacher_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-3">
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                        Filter
                    </button>
                    <a href="{{ route('admin.grades.index') }}"
                       class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
            <table class="min-w-full divide-y divide-stone-300">
                <thead>
                    <tr>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Nama Santri</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Fan/Mapel</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Guru Pengampu</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Kelas</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Nilai</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Predikat</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Keterangan</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-300">
                    @forelse ($grades as $grade)
                        <tr>
                            <td class="border-t border-stone-300 px-4 py-3 text-base font-medium text-zinc-900">{{ $grade->student->name }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $grade->teachingAssignment->subject->name }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $grade->teachingAssignment->teacher->name }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $grade->teachingAssignment->schoolClass->level->name }} {{ $grade->teachingAssignment->schoolClass->name }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $grade->score }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $grade->predicate ?? '-' }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $grade->note ?? '-' }}</td>
                            <td class="border-t border-stone-300 px-4 py-3">
                                @if ($grade->status === 'draft')
                                    <span class="inline-flex rounded-full bg-orange-300 px-3 py-1 text-xs font-semibold text-orange-950">Draft</span>
                                @else
                                    <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Submitted</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-sm text-neutral-500">Belum ada data nilai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $grades->links() }}
        </div>
    </div>
</x-app-layout>
