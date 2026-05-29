<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Input Absensi Baru</h2>
    </x-slot>

    <div class="space-y-5">
        @if (session('error'))
            <div class="rounded-sm bg-red-200 px-4 py-3 text-sm font-medium text-red-950">
                {{ session('error') }}
            </div>
        @endif

        <div class="rounded-lg bg-slate-50 p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Fan/Mapel</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $assignment->subject->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Kelas</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $assignment->schoolClass->level->name }} {{ $assignment->schoolClass->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Tahun Ajaran</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $assignment->academicYear->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Semester</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $assignment->semester->name }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <form action="{{ route('teacher.attendances.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="teaching_assignment_id" value="{{ $assignment->id }}">

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="attendance_date" class="mb-2 block text-sm font-medium text-neutral-700">Tanggal Absensi <span class="text-red-700">*</span></label>
                        <input type="date" name="attendance_date" id="attendance_date" value="{{ old('attendance_date', date('Y-m-d')) }}"
                               class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                        @error('attendance_date') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="status" class="mb-2 block text-sm font-medium text-neutral-700">Status <span class="text-red-700">*</span></label>
                        <select name="status" id="status"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="submitted" {{ old('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                        </select>
                        @error('status') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                </div>

                <hr class="border-stone-300">

                <div>
                    <h3 class="mb-4 text-base font-semibold text-teal-950">Daftar Santri</h3>
                    @error('details') <p class="mb-3 text-sm text-red-700">{{ $message }}</p> @enderror

                    <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
                        <table class="min-w-full divide-y divide-stone-300">
                            <thead>
                                <tr>
                                    <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">No</th>
                                    <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Nama Santri</th>
                                    <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">NIS</th>
                                    <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Status Absensi <span class="text-red-700">*</span></th>
                                    <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-300">
                                @foreach ($students as $index => $enrollment)
                                    <tr>
                                        <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $index + 1 }}</td>
                                        <td class="border-t border-stone-300 px-4 py-3 text-base font-medium text-zinc-900">{{ $enrollment->student->name }}</td>
                                        <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $enrollment->student->nis }}</td>
                                        <td class="border-t border-stone-300 px-4 py-3">
                                            <input type="hidden" name="details[{{ $index }}][student_id]" value="{{ $enrollment->student_id }}">
                                            <div class="flex items-center gap-4">
                                                <label class="flex items-center gap-1 text-sm text-zinc-900">
                                                    <input type="radio" name="details[{{ $index }}][status]" value="present"
                                                           {{ old("details.$index.status", 'present') === 'present' ? 'checked' : '' }}
                                                           class="text-teal-950 focus:ring-teal-950/10">
                                                    Hadir
                                                </label>
                                                <label class="flex items-center gap-1 text-sm text-zinc-900">
                                                    <input type="radio" name="details[{{ $index }}][status]" value="permission"
                                                           {{ old("details.$index.status") === 'permission' ? 'checked' : '' }}
                                                           class="text-teal-950 focus:ring-teal-950/10">
                                                    Izin
                                                </label>
                                                <label class="flex items-center gap-1 text-sm text-zinc-900">
                                                    <input type="radio" name="details[{{ $index }}][status]" value="sick"
                                                           {{ old("details.$index.status") === 'sick' ? 'checked' : '' }}
                                                           class="text-teal-950 focus:ring-teal-950/10">
                                                    Sakit
                                                </label>
                                                <label class="flex items-center gap-1 text-sm text-zinc-900">
                                                    <input type="radio" name="details[{{ $index }}][status]" value="absent"
                                                           {{ old("details.$index.status") === 'absent' ? 'checked' : '' }}
                                                           class="text-teal-950 focus:ring-teal-950/10">
                                                    Alfa
                                                </label>
                                            </div>
                                            @error("details.$index.status") <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                                        </td>
                                        <td class="border-t border-stone-300 px-4 py-3">
                                            <input type="text" name="details[{{ $index }}][note]" value="{{ old("details.$index.note") }}" maxlength="255"
                                                   class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10"
                                                   placeholder="Opsional">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                        Simpan Absensi
                    </button>
                    <a href="{{ route('teacher.attendances.index', ['teaching_assignment_id' => $assignment->id]) }}"
                       class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
