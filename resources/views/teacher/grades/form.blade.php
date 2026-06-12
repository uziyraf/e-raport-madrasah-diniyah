<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">
            {{ isset($assignment) ? 'Edit Nilai' : 'Input Nilai Baru' }}
        </h2>
    </x-slot>

    @if (!isset($assignment) && !$selectedAssignment)
        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <form method="GET" action="{{ route('teacher.grades.create') }}" class="space-y-5">
                <div>
                    <label for="teaching_assignment_id" class="mb-2 block text-sm font-medium text-neutral-700">Pilih Penugasan</label>
                    <select name="teaching_assignment_id" id="teaching_assignment_id" onchange="this.form.submit()"
                            class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                        <option value="">-- Pilih Penugasan --</option>
                        @foreach ($assignments as $ta)
                            <option value="{{ $ta->id }}">
                                {{ $ta->subject->name }} - {{ $ta->schoolClass->level->name }} {{ $ta->schoolClass->name }}
                                ({{ $ta->academicYear->name }} / {{ $ta->semester->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    @else
        @php
            $ta = $assignment ?? $selectedAssignment;
            $route = isset($assignment)
                ? route('teacher.grades.update', $assignment)
                : route('teacher.grades.store');
            $method = isset($assignment) ? 'PUT' : 'POST';
        @endphp

        <div class="space-y-5">
            <div class="rounded-lg bg-slate-50 p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                    <div>
                        <p class="text-xs font-semibold uppercase text-neutral-500">Fan/Mapel</p>
                        <p class="mt-1 text-base font-medium text-zinc-900">{{ $ta->subject->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-neutral-500">Kelas</p>
                        <p class="mt-1 text-base font-medium text-zinc-900">{{ $ta->schoolClass->level->name }} {{ $ta->schoolClass->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-neutral-500">Tahun Ajaran</p>
                        <p class="mt-1 text-base font-medium text-zinc-900">{{ $ta->academicYear->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-neutral-500">Semester</p>
                        <p class="mt-1 text-base font-medium text-zinc-900">{{ $ta->semester->name }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ $route }}" method="POST">
                @csrf
                @method($method)

                @if (!isset($assignment))
                    <input type="hidden" name="teaching_assignment_id" value="{{ $selectedAssignment->id }}">
                @endif

                <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <table class="min-w-full divide-y divide-stone-300">
                        <thead>
                            <tr>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700 w-8">No</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Nama Santri</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">NIS</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Nilai</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Predikat</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Keterangan</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-300">
                            @forelse ($students as $index => $enrollment)
                                @php
                                    $grade = $existingGrades->get($enrollment->student_id);
                                @endphp
                                <tr>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $loop->iteration }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base font-medium text-zinc-900">
                                        {{ $enrollment->student->name }}
                                        <input type="hidden" name="grades[{{ $index }}][student_id]" value="{{ $enrollment->student_id }}">
                                    </td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $enrollment->student->nis }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3">
                                        <input type="number" name="grades[{{ $index }}][score]"
                                               value="{{ old('grades.' . $index . '.score', $grade?->score) }}"
                                               min="0" max="100" step="0.01"
                                               class="w-24 rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                        @error('grades.' . $index . '.score') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
                                    </td>
                                    <td class="border-t border-stone-300 px-4 py-3">
                                        <input type="text" name="grades[{{ $index }}][predicate]"
                                               value="{{ old('grades.' . $index . '.predicate', $grade?->predicate) }}"
                                               maxlength="50"
                                               class="w-28 rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                        @error('grades.' . $index . '.predicate') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
                                    </td>
                                    <td class="border-t border-stone-300 px-4 py-3">
                                        <input type="text" name="grades[{{ $index }}][note]"
                                               value="{{ old('grades.' . $index . '.note', $grade?->note) }}"
                                               maxlength="500"
                                               class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                        @error('grades.' . $index . '.note') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
                                    </td>
                                    <td class="border-t border-stone-300 px-4 py-3">
                                        <select name="grades[{{ $index }}][status]"
                                                class="w-32 rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                            <option value="draft" {{ old('grades.' . $index . '.status', $grade?->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="submitted" {{ old('grades.' . $index . '.status', $grade?->status) === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                        </select>
                                        @error('grades.' . $index . '.status') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-sm text-neutral-500">
                                        Tidak ada santri terdaftar di kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($students->isNotEmpty())
                    <div class="mt-6 flex items-center gap-3">
                        <button type="submit"
                                class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                            Simpan Nilai
                        </button>
                        <a href="{{ route('teacher.grades.index') }}"
                           class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                            Batal
                        </a>
                    </div>
                @endif
            </form>
        </div>
    @endif
</x-app-layout>
