<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Edit Penempatan Santri</h2>
    </x-slot>

    <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
        <form action="{{ route('admin.student-enrollments.update', $enrollment) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-5">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="student_id" class="mb-2 block text-sm font-medium text-neutral-700">Santri</label>
                        <select name="student_id" id="student_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">Pilih Santri</option>
                            @foreach ($students as $id => $name)
                                <option value="{{ $id }}" {{ old('student_id', $enrollment->student_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('student_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="school_class_id" class="mb-2 block text-sm font-medium text-neutral-700">Kelas</label>
                        <select name="school_class_id" id="school_class_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">Pilih Kelas</option>
                            @foreach ($schoolClasses as $id => $label)
                                <option value="{{ $id }}" {{ old('school_class_id', $enrollment->school_class_id) == $id ? 'selected' : '' }}>{{ $label }}</option>
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
                                <option value="{{ $id }}" {{ old('academic_year_id', $enrollment->academic_year_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
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
                                <option value="{{ $id }}" {{ old('semester_id', $enrollment->semester_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('semester_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="enrollment_status" class="mb-2 block text-sm font-medium text-neutral-700">Status Penempatan</label>
                        <select name="enrollment_status" id="enrollment_status"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="active" {{ old('enrollment_status', $enrollment->enrollment_status) === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="transfer" {{ old('enrollment_status', $enrollment->enrollment_status) === 'transfer' ? 'selected' : '' }}>Pindah</option>
                            <option value="graduate" {{ old('enrollment_status', $enrollment->enrollment_status) === 'graduate' ? 'selected' : '' }}>Lulus</option>
                            <option value="dropout" {{ old('enrollment_status', $enrollment->enrollment_status) === 'dropout' ? 'selected' : '' }}>Keluar</option>
                        </select>
                        @error('enrollment_status') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="inline-flex items-center gap-3 pt-8">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                   {{ old('is_active', $enrollment->is_active) ? 'checked' : '' }}
                                   class="h-5 w-5 rounded-sm border-stone-300 text-teal-950 focus:ring-teal-950/10">
                            <span class="text-sm font-medium text-neutral-700">Jadikan aktif</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3 border-t border-stone-300 pt-6">
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                    Perbarui
                </button>
                <a href="{{ route('admin.student-enrollments.index') }}"
                   class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
