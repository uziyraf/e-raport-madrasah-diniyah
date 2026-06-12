@if ($students->isNotEmpty())
    <form method="POST" action="{{ route('admin.promotions.preview') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="mode" value="class">
        <input type="hidden" name="source_academic_year_id" value="{{ request('source_academic_year_id') }}">
        <input type="hidden" name="source_school_class_id" value="{{ request('source_school_class_id') }}">

        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div>
                                <label for="class_target_academic_year_id" class="mb-2 block text-sm font-medium text-neutral-700">Tahun Ajaran Tujuan</label>
                                <select name="target_academic_year_id" id="class_target_academic_year_id" required
                                        class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                    <option value="">Pilih Tahun Ajaran</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="class_target_semester_id" class="mb-2 block text-sm font-medium text-neutral-700">Semester Tujuan</label>
                                <select name="target_semester_id" id="class_target_semester_id" required
                                        class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                    <option value="">Pilih Semester</option>
                                    @foreach ($semesters as $semester)
                                        <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="class_target_school_class_id" class="mb-2 block text-sm font-medium text-neutral-700">Kelas Tujuan</label>
                                <select name="target_school_class_id" id="class_target_school_class_id"
                                        class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                    <option value="">Pilih Kelas (opsional untuk Lulus/Keluar)</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->level->name }} - {{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="class_placement_status" class="mb-2 block text-sm font-medium text-neutral-700">Status Penempatan</label>
                                <select name="placement_status" id="class_placement_status" required
                                        class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                    <option value="naik">Naik</option>
                                    <option value="tetap">Tetap</option>
                                    <option value="pindah">Pindah</option>
                                    <option value="lulus">Lulus</option>
                                    <option value="keluar">Keluar</option>
                                </select>
                                <p class="mt-1 text-xs text-neutral-500">Kelas Tujuan wajib untuk status Naik, Tetap, dan Pindah.</p>
                            </div>
        </div>

        <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
            <div class="flex items-center justify-between border-b border-stone-300 bg-white px-6 py-4">
                <h4 class="text-base font-bold text-teal-950">Daftar Santri</h4>
                <span class="text-xs font-semibold uppercase text-neutral-500">
                    {{ $students->count() }} santri
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">
                                <input type="checkbox" id="select-all-students" class="rounded border-stone-300 text-teal-950 focus:ring-teal-950/10">
                            </th>
                            <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">NIS</th>
                            <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Nama Santri</th>
                            <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Kelas Aktif</th>
                            <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr class="border-t border-stone-300">
                                <td class="px-6 py-4 text-base font-normal text-zinc-900">
                                    <input type="checkbox" name="students[]" value="{{ $student->id }}" class="student-checkbox rounded border-stone-300 text-teal-950 focus:ring-teal-950/10">
                                </td>
                                <td class="px-6 py-4 text-base font-normal text-zinc-900">{{ $student->nis }}</td>
                                <td class="px-6 py-4 text-base font-normal text-zinc-900">{{ $student->name }}</td>
                                <td class="px-6 py-4 text-base font-normal text-zinc-900">
                                    {{ $student->activeEnrollment?->schoolClass?->level?->name }} {{ $student->activeEnrollment?->schoolClass?->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($student->status === 'active')
                                        <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Aktif</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-zinc-200 px-3 py-1 text-xs font-semibold text-neutral-700">Nonaktif</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-6 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                Preview Penempatan
            </button>
        </div>
    </form>
@elseif (request('source_school_class_id'))
    <p class="py-4 text-center text-sm text-neutral-500">Tidak ada santri ditemukan di kelas asal.</p>
@else
    <p class="py-4 text-center text-sm text-neutral-500">Pilih kelas asal untuk menampilkan daftar santri.</p>
@endif
