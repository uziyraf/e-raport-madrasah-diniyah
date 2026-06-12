<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Edit Wali Kelas</h2>
    </x-slot>

    <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
        <form action="{{ route('admin.homeroom-assignments.update', $assignment) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-5">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="teacher_id" class="mb-2 block text-sm font-medium text-neutral-700">Guru</label>
                        <select name="teacher_id" id="teacher_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">Pilih Guru</option>
                            @foreach ($teachers as $id => $name)
                                <option value="{{ $id }}" {{ old('teacher_id', $assignment->teacher_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('teacher_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="school_class_id" class="mb-2 block text-sm font-medium text-neutral-700">Kelas</label>
                        <select name="school_class_id" id="school_class_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">Pilih Kelas</option>
                            @foreach ($schoolClasses as $id => $label)
                                <option value="{{ $id }}" {{ old('school_class_id', $assignment->school_class_id) == $id ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('school_class_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="academic_year_id" class="mb-2 block text-sm font-medium text-neutral-700">Tahun Ajaran</label>
                        <select name="academic_year_id" id="academic_year_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">Pilih Tahun Ajaran</option>
                            @foreach ($academicYears as $id => $name)
                                <option value="{{ $id }}" {{ old('academic_year_id', $assignment->academic_year_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('academic_year_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="semester_id" class="mb-2 block text-sm font-medium text-neutral-700">Semester</label>
                        <select name="semester_id" id="semester_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">Pilih Semester</option>
                            @foreach ($semesters as $id => $name)
                                <option value="{{ $id }}" {{ old('semester_id', $assignment->semester_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('semester_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3 border-t border-stone-300 pt-6">
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                    Perbarui
                </button>
                <a href="{{ route('admin.homeroom-assignments.index') }}"
                   class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
