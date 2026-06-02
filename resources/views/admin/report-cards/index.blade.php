<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Preview Raport Arab</h2>
    </x-slot>

    <div class="space-y-5">
        <form method="GET" action="{{ route('admin.report-cards.index') }}"
              class="rounded-lg bg-slate-50 p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div>
                    <label for="academic_year_id" class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Tahun Ajaran</label>
                    <select name="academic_year_id" id="academic_year_id"
                            onchange="this.form.submit()"
                            class="w-full rounded-sm border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900 outline outline-1 outline-stone-300 focus:outline-2 focus:outline-teal-500">
                        <option value="">-- Pilih --</option>
                        @foreach ($academicYears as $ay)
                            <option value="{{ $ay->id }}" {{ $selectedAcademicYear == $ay->id ? 'selected' : '' }}>
                                {{ $ay->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="semester_id" class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Semester</label>
                    <select name="semester_id" id="semester_id"
                            onchange="this.form.submit()"
                            class="w-full rounded-sm border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900 outline outline-1 outline-stone-300 focus:outline-2 focus:outline-teal-500">
                        <option value="">-- Pilih --</option>
                        @foreach ($semesters as $sem)
                            <option value="{{ $sem->id }}" {{ $selectedSemester == $sem->id ? 'selected' : '' }}>
                                {{ $sem->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="school_class_id" class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Kelas</label>
                    <select name="school_class_id" id="school_class_id"
                            onchange="this.form.submit()"
                            class="w-full rounded-sm border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900 outline outline-1 outline-stone-300 focus:outline-2 focus:outline-teal-500">
                        <option value="">-- Pilih --</option>
                        @foreach ($schoolClasses as $class)
                            <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>
                                {{ $class->level->name ?? '' }} {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="keyword" class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Cari Santri</label>
                    <input type="text" name="keyword" id="keyword" value="{{ $keyword }}"
                           placeholder="Nama / NIS"
                           class="w-full rounded-sm border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900 outline outline-1 outline-stone-300 focus:outline-2 focus:outline-teal-500">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit"
                        class="inline-flex items-center gap-1 rounded-sm bg-teal-700 px-4 py-2 text-sm font-medium text-white transition hover:bg-teal-800">
                    Cari
                </button>
            </div>
        </form>

        @if ($students->isNotEmpty())
            <div class="overflow-hidden rounded-lg bg-white shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-stone-200 bg-stone-50">
                            <th class="px-4 py-3 font-semibold text-neutral-600">No</th>
                            <th class="px-4 py-3 font-semibold text-neutral-600">NIS</th>
                            <th class="px-4 py-3 font-semibold text-neutral-600">Nama</th>
                            <th class="px-4 py-3 font-semibold text-neutral-600">Nama Arab</th>
                            <th class="px-4 py-3 font-semibold text-neutral-600">Kelas</th>
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
                                <td class="px-4 py-3 text-zinc-900">{{ $enrollment->schoolClass->level->name ?? '' }} {{ $enrollment->schoolClass->name }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.report-cards.show', [
                                        'student' => $enrollment->student,
                                        'academic_year_id' => $selectedAcademicYear,
                                        'semester_id' => $selectedSemester,
                                        'school_class_id' => $selectedClass,
                                    ]) }}"
                                       class="inline-flex items-center gap-1 rounded-sm bg-slate-50 px-3 py-2 text-xs font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                                        Preview Raport
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif ($selectedAcademicYear && $selectedSemester && $selectedClass)
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <p class="text-center text-sm text-neutral-500">
                    Tidak ada santri ditemukan.
                </p>
            </div>
        @endif
    </div>
</x-app-layout>
