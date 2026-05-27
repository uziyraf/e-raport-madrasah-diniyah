<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Edit Kelas</h2>
    </x-slot>

    <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
        <form action="{{ route('admin.school-classes.update', $schoolClass) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="level_id" class="mb-2 block text-sm font-medium text-neutral-700">Jenjang</label>
                <select name="level_id" id="level_id"
                        class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                    <option value="">Pilih Jenjang</option>
                    @foreach ($levels as $level)
                        <option value="{{ $level->id }}" {{ old('level_id', $schoolClass->level_id) == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                    @endforeach
                </select>
                @error('level_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="grade_level" class="mb-2 block text-sm font-medium text-neutral-700">Tingkat Kelas</label>
                <input type="number" name="grade_level" id="grade_level" value="{{ old('grade_level', $schoolClass->grade_level) }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10"
                       min="1" max="20">
                @error('grade_level') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="parallel_name" class="mb-2 block text-sm font-medium text-neutral-700">Paralel</label>
                <input type="text" name="parallel_name" id="parallel_name" value="{{ old('parallel_name', $schoolClass->parallel_name) }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('parallel_name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-neutral-700">Nama Kelas</label>
                <input type="text" name="name" id="name" value="{{ old('name', $schoolClass->name) }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="code" class="mb-2 block text-sm font-medium text-neutral-700">Kode</label>
                <input type="text" name="code" id="code" value="{{ old('code', $schoolClass->code) }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('code') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="sort_order" class="mb-2 block text-sm font-medium text-neutral-700">Urutan</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $schoolClass->sort_order) }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('sort_order') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-neutral-700">Status</label>
                <select name="status" id="status"
                        class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                    <option value="active" {{ old('status', $schoolClass->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $schoolClass->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                    Perbarui
                </button>
                <a href="{{ route('admin.school-classes.index') }}"
                   class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
