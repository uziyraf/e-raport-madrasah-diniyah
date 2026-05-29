<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Edit Absensi</h2>
    </x-slot>

    <div class="space-y-5">
        <div class="rounded-lg bg-slate-50 p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <div class="grid grid-cols-2 gap-4 md:grid-cols-3">
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Kelas</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $session->schoolClass->level->name }} {{ $session->schoolClass->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Tahun Ajaran</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $session->academicYear->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Semester</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $session->semester->name }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <form action="{{ route('homeroom.attendances.update', $session) }}" method="POST" class="space-y-6">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="attendance_date" class="mb-2 block text-sm font-medium text-neutral-700">Tanggal Absensi <span class="text-red-700">*</span></label>
                        <input type="date" name="attendance_date" id="attendance_date" value="{{ old('attendance_date', $session->attendance_date->format('Y-m-d')) }}"
                               class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                        @error('attendance_date') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="status" class="mb-2 block text-sm font-medium text-neutral-700">Status <span class="text-red-700">*</span></label>
                        <select name="status" id="status"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="draft" {{ old('status', $session->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="submitted" {{ old('status', $session->status) === 'submitted' ? 'selected' : '' }}>Submitted</option>
                        </select>
                        @error('status') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                </div>

                <hr class="border-stone-300">

                <div>
                    <h3 class="mb-4 text-base font-semibold text-teal-950">Daftar Santri</h3>
                    @error('details') <p class="mb-3 text-sm text-red-700">{{ $message }}</p> @enderror

                    @php
                        $existingDetails = $session->details->keyBy('student_id');
                    @endphp

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
                                    @php
                                        $existing = $existingDetails->get($enrollment->student_id);
                                    @endphp
                                    <tr>
                                        <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $index + 1 }}</td>
                                        <td class="border-t border-stone-300 px-4 py-3 text-base font-medium text-zinc-900">{{ $enrollment->student->name }}</td>
                                        <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $enrollment->student->nis }}</td>
                                        <td class="border-t border-stone-300 px-4 py-3">
                                            <input type="hidden" name="details[{{ $index }}][student_id]" value="{{ $enrollment->student_id }}">
                                            <div class="flex items-center gap-4">
                                                <label class="flex items-center gap-1 text-sm text-zinc-900">
                                                    <input type="radio" name="details[{{ $index }}][status]" value="present"
                                                           {{ old("details.$index.status", $existing?->status) === 'present' ? 'checked' : '' }}
                                                           class="text-teal-950 focus:ring-teal-950/10">
                                                    Hadir
                                                </label>
                                                <label class="flex items-center gap-1 text-sm text-zinc-900">
                                                    <input type="radio" name="details[{{ $index }}][status]" value="permission"
                                                           {{ old("details.$index.status", $existing?->status) === 'permission' ? 'checked' : '' }}
                                                           class="text-teal-950 focus:ring-teal-950/10">
                                                    Izin
                                                </label>
                                                <label class="flex items-center gap-1 text-sm text-zinc-900">
                                                    <input type="radio" name="details[{{ $index }}][status]" value="sick"
                                                           {{ old("details.$index.status", $existing?->status) === 'sick' ? 'checked' : '' }}
                                                           class="text-teal-950 focus:ring-teal-950/10">
                                                    Sakit
                                                </label>
                                                <label class="flex items-center gap-1 text-sm text-zinc-900">
                                                    <input type="radio" name="details[{{ $index }}][status]" value="absent"
                                                           {{ old("details.$index.status", $existing?->status) === 'absent' ? 'checked' : '' }}
                                                           class="text-teal-950 focus:ring-teal-950/10">
                                                    Alfa
                                                </label>
                                            </div>
                                            @error("details.$index.status") <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                                        </td>
                                        <td class="border-t border-stone-300 px-4 py-3">
                                            <input type="text" name="details[{{ $index }}][note]" value="{{ old("details.$index.note", $existing?->note) }}" maxlength="255"
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
                        Update Absensi
                    </button>
                    <a href="{{ route('homeroom.attendances.index') }}"
                       class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
