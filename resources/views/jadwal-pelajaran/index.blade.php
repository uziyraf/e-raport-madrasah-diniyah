<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Lihat Jadwal Pelajaran</h2>
    </x-slot>

    <div class="space-y-5">
        @if ($info)
            <div class="rounded-sm bg-blue-200 px-4 py-3 text-sm font-medium text-blue-950">
                {{ $info }}
            </div>
        @endif

        @if (in_array($role, ['super_admin', 'kepala_sekolah']))
            <div class="rounded-lg bg-white p-4 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <form method="GET" action="{{ route('jadwal-pelajaran.index') }}" class="grid grid-cols-2 gap-4 md:grid-cols-6">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-neutral-600">Tahun Ajaran</label>
                        <select name="tahun_ajaran_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">Semua</option>
                            @foreach ($academicYears as $id => $name)
                                <option value="{{ $id }}" @selected(request('tahun_ajaran_id') == $id)>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-neutral-600">Semester</label>
                        <select name="semester_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">Semua</option>
                            @foreach ($semesters as $id => $name)
                                <option value="{{ $id }}" @selected(request('semester_id') == $id)>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-neutral-600">Kelas</label>
                        <select name="kelas_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">Semua</option>
                            @foreach ($schoolClasses as $id => $label)
                                <option value="{{ $id }}" @selected(request('kelas_id') == $id)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-neutral-600">Guru</label>
                        <select name="guru_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">Semua</option>
                            @foreach ($teachers as $id => $name)
                                <option value="{{ $id }}" @selected(request('guru_id') == $id)>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-neutral-600">Fan/Mapel</label>
                        <select name="mapel_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">Semua</option>
                            @foreach ($subjects as $id => $name)
                                <option value="{{ $id }}" @selected(request('mapel_id') == $id)>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-neutral-600">Hari</label>
                        <select name="hari"
                                class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">Semua</option>
                            @foreach ($days as $day)
                                <option value="{{ $day }}" @selected(request('hari') == $day)>{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2 md:col-span-6">
                        <button type="submit"
                                class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                            Filter
                        </button>
                        <a href="{{ route('jadwal-pelajaran.index') }}"
                           class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-2 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        @endif

        <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
            <table class="min-w-full divide-y divide-stone-300">
                <thead>
                    <tr>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Hari</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Jam</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Kelas</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Fan/Mapel</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Guru</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Tahun Ajaran</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Semester</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-300">
                    @forelse ($jadwals as $jadwal)
                        <tr>
                            <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $jadwal->hari }}</td>
                            <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">
                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                            </td>
                            <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $jadwal->kelas->level->name }} {{ $jadwal->kelas->name }}</td>
                            <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $jadwal->mapel->name }}</td>
                            <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $jadwal->guru->name }}</td>
                            <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $jadwal->tahunAjaran->name }}</td>
                            <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $jadwal->semester->name }}</td>
                            <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $jadwal->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-sm text-neutral-500">{{ $info ?? 'Belum ada data jadwal pelajaran.' }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $jadwals->links() }}
        </div>
    </div>
</x-app-layout>
