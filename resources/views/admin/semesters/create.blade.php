<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Tambah Semester</h2>
    </x-slot>

    <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
        <form action="{{ route('admin.semesters.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="academic_year_id" class="mb-2 block text-sm font-medium text-neutral-700">Tahun Ajaran</label>
                <select name="academic_year_id" id="academic_year_id"
                        class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                    <option value="">Pilih Tahun Ajaran</option>
                    @foreach ($academicYears as $academicYear)
                        <option value="{{ $academicYear->id }}" {{ old('academic_year_id') == $academicYear->id ? 'selected' : '' }}>{{ $academicYear->name }}</option>
                    @endforeach
                </select>
                @error('academic_year_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-neutral-700">Nama Semester</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="start_date" class="mb-2 block text-sm font-medium text-neutral-700">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('start_date') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="end_date" class="mb-2 block text-sm font-medium text-neutral-700">Tanggal Selesai</label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('end_date') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="is_active" class="inline-flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}
                           class="h-5 w-5 rounded-sm border-stone-300 text-teal-950 focus:ring-teal-950/10">
                    <span class="text-sm font-medium text-neutral-700">Aktif</span>
                </label>
                @error('is_active') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                    Simpan
                </button>
                <a href="{{ route('admin.semesters.index') }}"
                   class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
