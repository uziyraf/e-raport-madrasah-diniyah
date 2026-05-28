<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Edit Jurnal</h2>
    </x-slot>

    <div class="space-y-5">
        <div class="rounded-lg bg-slate-50 p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Nama Santri</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $journal->student->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">NIS</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $journal->student->nis }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Kelas</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $journal->schoolClass->level->name }} {{ $journal->schoolClass->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Fan/Mapel</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $journal->teachingAssignment?->subject->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-neutral-500">Jenis Jurnal</p>
                    <p class="mt-1 text-base font-medium text-zinc-900">{{ $labels[$journal->journal_type] ?? $journal->journal_type }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <form action="{{ route('homeroom.journals.update', $journal) }}" method="POST" class="space-y-6">
                @csrf @method('PUT')
                <input type="hidden" name="student_id" value="{{ $journal->student_id }}">
                <input type="hidden" name="journal_type" value="{{ $journal->journal_type }}">

                <div>
                    <h3 class="mb-4 text-base font-semibold text-teal-950">Data Dasar</h3>
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700">Tanggal Jurnal <span class="text-red-700">*</span></label>
                            <input type="date" name="journal_date" value="{{ old('journal_date', $journal->journal_date->format('Y-m-d')) }}"
                                   class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            @error('journal_date') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700">Status <span class="text-red-700">*</span></label>
                            <select name="status"
                                    class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                <option value="draft" {{ old('status', $journal->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="submitted" {{ old('status', $journal->status) === 'submitted' ? 'selected' : '' }}>Submitted</option>
                            </select>
                            @error('status') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-stone-300">

                @if ($journal->journal_type === 'tamrinan')
                    <div>
                        <h3 class="mb-4 text-base font-semibold text-teal-950">Fan/Mapel yang Diujikan</h3>
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-1">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-neutral-700">Mapel/Fan <span class="text-red-700">*</span></label>
                                <select name="teaching_assignment_id"
                                        class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                    <option value="">-- Pilih Fan/Mapel --</option>
                                    @foreach ($teachingAssignments as $ta)
                                        <option value="{{ $ta->id }}" {{ old('teaching_assignment_id', $journal->teaching_assignment_id) == $ta->id ? 'selected' : '' }}>
                                            {{ $ta->subject->name }} - {{ $ta->schoolClass->level->name }} {{ $ta->schoolClass->name }}
                                            ({{ $ta->academicYear->name }} / {{ $ta->semester->name }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('teaching_assignment_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                    <hr class="border-stone-300">
                @endif

                @if ($journal->journal_type === 'hafalan')
                    <div>
                        <h3 class="mb-4 text-base font-semibold text-teal-950">Hafalan</h3>
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-neutral-700">Jenis Hafalan</label>
                                <input type="text" name="memorization_type" value="{{ old('memorization_type', $journal->memorization_type) }}" maxlength="100"
                                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-neutral-700">Target Hafalan</label>
                                <input type="text" name="memorization_target" value="{{ old('memorization_target', $journal->memorization_target) }}" maxlength="255"
                                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-neutral-700">Capaian Hafalan</label>
                                <input type="text" name="memorization_result" value="{{ old('memorization_result', $journal->memorization_result) }}" maxlength="255"
                                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-neutral-700">Predikat</label>
                                <input type="text" name="predicate" value="{{ old('predicate', $journal->predicate) }}" maxlength="50"
                                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-2 block text-sm font-medium text-neutral-700">Keterangan</label>
                                <textarea name="note" rows="3"
                                          class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">{{ old('note', $journal->note) }}</textarea>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($journal->journal_type === 'legalisir_kitab')
                    <div>
                        <h3 class="mb-4 text-base font-semibold text-teal-950">Legalisir Kitab</h3>
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-neutral-700">Nama Kitab</label>
                                <input type="text" name="kitab_name" value="{{ old('kitab_name', $journal->kitab_name) }}" maxlength="255"
                                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-neutral-700">Halaman</label>
                                <input type="text" name="kitab_page" value="{{ old('kitab_page', $journal->kitab_page) }}" maxlength="100"
                                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-neutral-700">Status Legalisir</label>
                                <select name="legalization_status"
                                        class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                    <option value="">-- Pilih --</option>
                                    <option value="Sudah" {{ old('legalization_status', $journal->legalization_status) === 'Sudah' ? 'selected' : '' }}>Sudah</option>
                                    <option value="Belum" {{ old('legalization_status', $journal->legalization_status) === 'Belum' ? 'selected' : '' }}>Belum</option>
                                    <option value="Dalam Proses" {{ old('legalization_status', $journal->legalization_status) === 'Dalam Proses' ? 'selected' : '' }}>Dalam Proses</option>
                                </select>
                            </div>
                            <div class="md:col-span-3">
                                <label class="mb-2 block text-sm font-medium text-neutral-700">Keterangan</label>
                                <textarea name="note" rows="3"
                                          class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">{{ old('note', $journal->note) }}</textarea>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($journal->journal_type === 'nilai_harian')
                    <div>
                        <h3 class="mb-4 text-base font-semibold text-teal-950">Nilai Harian</h3>
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-neutral-700">Nilai Harian</label>
                                <input type="number" name="daily_score" value="{{ old('daily_score', $journal->daily_score) }}" min="0" max="100" step="0.01"
                                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                @error('daily_score') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-neutral-700">Predikat</label>
                                <input type="text" name="predicate" value="{{ old('predicate', $journal->predicate) }}" maxlength="50"
                                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-neutral-700">Keterangan</label>
                                <textarea name="note" rows="3"
                                          class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">{{ old('note', $journal->note) }}</textarea>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($journal->journal_type === 'tamrinan')
                    <div>
                        <h3 class="mb-4 text-base font-semibold text-teal-950">Tamrinan</h3>
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-neutral-700">Nilai Ujian/Tamrinan</label>
                                <input type="number" name="exam_score" value="{{ old('exam_score', $journal->exam_score) }}" min="0" max="100" step="0.01"
                                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                @error('exam_score') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-neutral-700">Predikat</label>
                                <input type="text" name="predicate" value="{{ old('predicate', $journal->predicate) }}" maxlength="50"
                                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-neutral-700">Keterangan</label>
                                <textarea name="note" rows="3"
                                          class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">{{ old('note', $journal->note) }}</textarea>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($journal->journal_type === 'catatan')
                    <div>
                        <h3 class="mb-4 text-base font-semibold text-teal-950">Catatan</h3>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-neutral-700">Catatan / Keterangan</label>
                            <textarea name="note" rows="4"
                                      class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">{{ old('note', $journal->note) }}</textarea>
                            @error('note') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                        </div>
                    </div>
                @endif

                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                        Update Jurnal
                    </button>
                    <a href="{{ route('homeroom.journals.student', [
                        'journalType' => $journal->journal_type,
                        'student' => $journal->student_id,
                    ]) }}"
                       class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
