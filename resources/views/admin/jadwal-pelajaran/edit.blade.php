<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Edit Jadwal Pelajaran</h2>
    </x-slot>

    <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
        <form action="{{ route('admin.jadwal-pelajaran.update', $jadwal) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-5">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="tahun_ajaran_id" class="mb-2 block text-sm font-medium text-neutral-700">Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" id="tahun_ajaran_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10 @error('tahun_ajaran_id') border-red-400 @enderror">
                            <option value="">Pilih Tahun Ajaran</option>
                            @foreach ($academicYears as $id => $name)
                                <option value="{{ $id }}" {{ old('tahun_ajaran_id', $jadwal->tahun_ajaran_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('tahun_ajaran_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="semester_id" class="mb-2 block text-sm font-medium text-neutral-700">Semester</label>
                        <select name="semester_id" id="semester_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10 @error('semester_id') border-red-400 @enderror">
                            <option value="">Pilih Semester</option>
                            @foreach ($semesters as $id => $name)
                                <option value="{{ $id }}" {{ old('semester_id', $jadwal->semester_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('semester_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="kelas_id" class="mb-2 block text-sm font-medium text-neutral-700">Kelas</label>
                        <select name="kelas_id" id="kelas_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10 @error('kelas_id') border-red-400 @enderror">
                            <option value="">Pilih Kelas</option>
                            @foreach ($schoolClasses as $id => $label)
                                <option value="{{ $id }}" {{ old('kelas_id', $jadwal->kelas_id) == $id ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('kelas_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="mapel_id" class="mb-2 block text-sm font-medium text-neutral-700">Fan/Mapel</label>
                        <select name="mapel_id" id="mapel_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10 @error('mapel_id') border-red-400 @enderror">
                            <option value="">Pilih Fan/Mapel</option>
                            @foreach ($subjects as $id => $name)
                                <option value="{{ $id }}" {{ old('mapel_id', $jadwal->mapel_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('mapel_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="guru_id" class="mb-2 block text-sm font-medium text-neutral-700">Guru</label>
                        <select name="guru_id" id="guru_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10 @error('guru_id') border-red-400 @enderror">
                            <option value="">Pilih Guru</option>
                            @foreach ($teachers as $id => $name)
                                <option value="{{ $id }}" {{ old('guru_id', $jadwal->guru_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('guru_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="hari" class="mb-2 block text-sm font-medium text-neutral-700">Hari</label>
                        <select name="hari" id="hari"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10 @error('hari') border-red-400 @enderror">
                            <option value="">Pilih Hari</option>
                            @foreach ($days as $day)
                                <option value="{{ $day }}" {{ old('hari', $jadwal->hari) == $day ? 'selected' : '' }}>{{ $day }}</option>
                            @endforeach
                        </select>
                        @error('hari') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="jam_mulai" class="mb-2 block text-sm font-medium text-neutral-700">Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai', $jadwal->jam_mulai ? \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') : '') }}"
                               class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10 @error('jam_mulai') border-red-400 @enderror">
                        @error('jam_mulai') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="jam_selesai" class="mb-2 block text-sm font-medium text-neutral-700">Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai', $jadwal->jam_selesai ? \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') : '') }}"
                               class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10 @error('jam_selesai') border-red-400 @enderror">
                        @error('jam_selesai') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="keterangan" class="mb-2 block text-sm font-medium text-neutral-700">Keterangan <span class="text-xs text-neutral-400">(opsional)</span></label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                              class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10 @error('keterangan') border-red-400 @enderror">{{ old('keterangan', $jadwal->keterangan) }}</textarea>
                    @error('keterangan') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3 border-t border-stone-300 pt-6">
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.jadwal-pelajaran.index') }}"
                   class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
