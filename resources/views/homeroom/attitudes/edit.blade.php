<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Input Nilai Sikap</h2>
    </x-slot>

    <div class="space-y-5">
        <div class="rounded-lg bg-slate-50 p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Nama Santri</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $student->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">NIS</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $student->nis }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Kelas</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $homeroom->schoolClass->level->name }} {{ $homeroom->schoolClass->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Tahun Ajaran / Semester</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $homeroom->academicYear->name }} / {{ $homeroom->semester->name }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <form action="{{ route('homeroom.attitudes.update', $student->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="akhlak" class="mb-2 block text-sm font-medium text-neutral-700">Akhlak</label>
                        <select name="akhlak" id="akhlak"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">-- Pilih --</option>
                            <option value="Sangat Baik" {{ old('akhlak', $attitude?->akhlak) === 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik</option>
                            <option value="Baik" {{ old('akhlak', $attitude?->akhlak) === 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Cukup" {{ old('akhlak', $attitude?->akhlak) === 'Cukup' ? 'selected' : '' }}>Cukup</option>
                            <option value="Perlu Bimbingan" {{ old('akhlak', $attitude?->akhlak) === 'Perlu Bimbingan' ? 'selected' : '' }}>Perlu Bimbingan</option>
                        </select>
                        @error('akhlak') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="discipline" class="mb-2 block text-sm font-medium text-neutral-700">Kedisiplinan</label>
                        <select name="discipline" id="discipline"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">-- Pilih --</option>
                            <option value="Sangat Baik" {{ old('discipline', $attitude?->discipline) === 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik</option>
                            <option value="Baik" {{ old('discipline', $attitude?->discipline) === 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Cukup" {{ old('discipline', $attitude?->discipline) === 'Cukup' ? 'selected' : '' }}>Cukup</option>
                            <option value="Perlu Bimbingan" {{ old('discipline', $attitude?->discipline) === 'Perlu Bimbingan' ? 'selected' : '' }}>Perlu Bimbingan</option>
                        </select>
                        @error('discipline') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="cleanliness" class="mb-2 block text-sm font-medium text-neutral-700">Kebersihan</label>
                        <select name="cleanliness" id="cleanliness"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">-- Pilih --</option>
                            <option value="Sangat Baik" {{ old('cleanliness', $attitude?->cleanliness) === 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik</option>
                            <option value="Baik" {{ old('cleanliness', $attitude?->cleanliness) === 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Cukup" {{ old('cleanliness', $attitude?->cleanliness) === 'Cukup' ? 'selected' : '' }}>Cukup</option>
                            <option value="Perlu Bimbingan" {{ old('cleanliness', $attitude?->cleanliness) === 'Perlu Bimbingan' ? 'selected' : '' }}>Perlu Bimbingan</option>
                        </select>
                        @error('cleanliness') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="attitude_note" class="mb-2 block text-sm font-medium text-neutral-700">Catatan Sikap</label>
                    <textarea name="attitude_note" id="attitude_note" rows="4"
                              class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">{{ old('attitude_note', $attitude?->attitude_note) }}</textarea>
                    @error('attitude_note') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                        Simpan Nilai Sikap
                    </button>
                    <a href="{{ route('homeroom.attitudes.index') }}"
                       class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
