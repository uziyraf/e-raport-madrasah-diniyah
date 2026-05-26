@php
    $s = $student ?? null;
    $enrollment = $s?->activeEnrollment;
@endphp
<div class="space-y-5">
    {{-- Identitas Santri --}}
    <div>
        <h3 class="mb-4 text-lg font-semibold text-teal-950">Identitas Santri</h3>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label for="nis" class="mb-2 block text-sm font-medium text-neutral-700">NIS</label>
                <input type="text" name="nis" id="nis" value="{{ old('nis', $s->nis ?? '') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('nis') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-neutral-700">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $s->name ?? '') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="arabic_name" class="mb-2 block text-sm font-medium text-neutral-700">Nama Arab</label>
                <input type="text" name="arabic_name" id="arabic_name" value="{{ old('arabic_name', $s->arabic_name ?? '') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('arabic_name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="gender" class="mb-2 block text-sm font-medium text-neutral-700">Jenis Kelamin</label>
                <select name="gender" id="gender"
                        class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="male" {{ old('gender', $s->gender ?? '') === 'male' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="female" {{ old('gender', $s->gender ?? '') === 'female' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('gender') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="birth_place" class="mb-2 block text-sm font-medium text-neutral-700">Tempat Lahir</label>
                <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place', $s->birth_place ?? '') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('birth_place') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="birth_date" class="mb-2 block text-sm font-medium text-neutral-700">Tanggal Lahir</label>
                <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $s?->birth_date?->format('Y-m-d') ?? '') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('birth_date') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-neutral-700">Status Santri</label>
                <select name="status" id="status"
                        class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                    <option value="active" {{ old('status', $s->status ?? 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $s->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label for="address" class="mb-2 block text-sm font-medium text-neutral-700">Alamat</label>
                <textarea name="address" id="address" rows="3"
                          class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">{{ old('address', $s->address ?? '') }}</textarea>
                @error('address') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Orang Tua / Wali --}}
    <div>
        <h3 class="mb-4 text-lg font-semibold text-teal-950">Orang Tua / Wali</h3>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label for="father_name" class="mb-2 block text-sm font-medium text-neutral-700">Nama Ayah</label>
                <input type="text" name="father_name" id="father_name" value="{{ old('father_name', $s->father_name ?? '') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('father_name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="mother_name" class="mb-2 block text-sm font-medium text-neutral-700">Nama Ibu</label>
                <input type="text" name="mother_name" id="mother_name" value="{{ old('mother_name', $s->mother_name ?? '') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('mother_name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="guardian_name" class="mb-2 block text-sm font-medium text-neutral-700">Nama Wali</label>
                <input type="text" name="guardian_name" id="guardian_name" value="{{ old('guardian_name', $s->guardian_name ?? '') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('guardian_name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="guardian_phone" class="mb-2 block text-sm font-medium text-neutral-700">No. HP Wali</label>
                <input type="text" name="guardian_phone" id="guardian_phone" value="{{ old('guardian_phone', $s->guardian_phone ?? '') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('guardian_phone') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Penempatan Kelas --}}
    <div>
        <h3 class="mb-4 text-lg font-semibold text-teal-950">Penempatan Kelas</h3>
        <div class="mb-4">
            <label class="inline-flex items-center gap-3">
                <input type="checkbox" id="enable_placement"
                       onchange="document.getElementById('placement-fields').classList.toggle('hidden', !this.checked)"
                       {{ old('school_class_id', $enrollment?->school_class_id) ? 'checked' : '' }}>
                <span class="text-sm font-medium text-neutral-700">Tempatkan di kelas?</span>
            </label>
        </div>

        <div id="placement-fields" class="{{ old('school_class_id', $enrollment?->school_class_id) ? '' : 'hidden' }} space-y-5">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div>
                    <label for="school_class_id" class="mb-2 block text-sm font-medium text-neutral-700">Kelas</label>
                    <select name="school_class_id" id="school_class_id"
                            class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                        <option value="">Pilih Kelas</option>
                        @foreach ($schoolClasses as $id => $label)
                            <option value="{{ $id }}" {{ old('school_class_id', $enrollment?->school_class_id) == $id ? 'selected' : '' }}>{{ $label }}</option>
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
                            <option value="{{ $id }}" {{ old('academic_year_id', $enrollment?->academic_year_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
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
                            <option value="{{ $id }}" {{ old('semester_id', $enrollment?->semester_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('semester_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Dokumen --}}
    <div>
        <h3 class="mb-4 text-lg font-semibold text-teal-950">Dokumen</h3>
        <div>
            <label for="photo" class="mb-2 block text-sm font-medium text-neutral-700">Foto Santri</label>
            @if ($s?->photo_path)
                <div class="mb-3">
                    <img src="{{ Storage::url($s->photo_path) }}" alt="Foto Santri"
                         class="max-h-32 rounded-sm border border-stone-300">
                    <p class="mt-1 text-xs text-neutral-500">Foto saat ini. Upload ulang untuk mengganti.</p>
                </div>
            @endif
            <input type="file" name="photo" id="photo" accept="image/jpg,image/jpeg,image/png,image/webp"
                   class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition file:mr-4 file:rounded-sm file:border-0 file:bg-teal-950 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
            @error('photo') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
    </div>
</div>
