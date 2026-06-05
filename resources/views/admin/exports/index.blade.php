<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Export Data</h2>
    </x-slot>

    <div class="space-y-6">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <h3 class="text-base font-semibold text-zinc-900">Data Santri</h3>
                <p class="mt-1 text-sm text-neutral-500">Ekspor semua data santri.</p>
                <a href="{{ route('admin.exports.students') }}"
                   class="mt-4 inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                    <i class="bx bx-download mr-1"></i> Download CSV
                </a>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <h3 class="text-base font-semibold text-zinc-900">Data Guru</h3>
                <p class="mt-1 text-sm text-neutral-500">Ekspor semua data guru.</p>
                <a href="{{ route('admin.exports.teachers') }}"
                   class="mt-4 inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                    <i class="bx bx-download mr-1"></i> Download CSV
                </a>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <h3 class="text-base font-semibold text-zinc-900">Data Wali Santri</h3>
                <p class="mt-1 text-sm text-neutral-500">Ekspor semua data wali santri.</p>
                <a href="{{ route('admin.exports.guardians') }}"
                   class="mt-4 inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                    <i class="bx bx-download mr-1"></i> Download CSV
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-2">
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <h3 class="text-base font-semibold text-zinc-900">Rekap Absensi</h3>
                <p class="mt-1 text-sm text-neutral-500">Ekspor rekap absensi dengan filter.</p>
                <form action="{{ route('admin.exports.attendances') }}" method="GET" class="mt-4 space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Tahun Ajaran</label>
                            <select name="academic_year_id" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                                <option value="">Semua</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year->id }}" {{ $year->is_active ? 'selected' : '' }}>{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Semester</label>
                            <select name="semester_id" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                                <option value="">Semua</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->id }}" {{ $semester->is_active ? 'selected' : '' }}>{{ $semester->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Kelas</label>
                        <select name="school_class_id" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                            <option value="">Semua Kelas</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->level->name ?? '' }} {{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Tanggal Dari</label>
                            <input type="date" name="date_from" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Tanggal Sampai</label>
                            <input type="date" name="date_to" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                        </div>
                    </div>
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                        <i class="bx bx-download mr-1"></i> Download CSV
                    </button>
                </form>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <h3 class="text-base font-semibold text-zinc-900">Rekap Nilai</h3>
                <p class="mt-1 text-sm text-neutral-500">Ekspor rekap nilai dengan filter.</p>
                <form action="{{ route('admin.exports.grades') }}" method="GET" class="mt-4 space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Tahun Ajaran</label>
                            <select name="academic_year_id" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                                <option value="">Semua</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year->id }}" {{ $year->is_active ? 'selected' : '' }}>{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Semester</label>
                            <select name="semester_id" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                                <option value="">Semua</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->id }}" {{ $semester->is_active ? 'selected' : '' }}>{{ $semester->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Kelas</label>
                        <select name="school_class_id" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                            <option value="">Semua Kelas</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->level->name ?? '' }} {{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                        <i class="bx bx-download mr-1"></i> Download CSV
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <h3 class="text-base font-semibold text-zinc-900">Rekap Sikap</h3>
                <p class="mt-1 text-sm text-neutral-500">Ekspor rekap sikap dengan filter.</p>
                <form action="{{ route('admin.exports.attitudes') }}" method="GET" class="mt-4 space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Tahun Ajaran</label>
                            <select name="academic_year_id" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                                <option value="">Semua</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year->id }}" {{ $year->is_active ? 'selected' : '' }}>{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Semester</label>
                            <select name="semester_id" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                                <option value="">Semua</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->id }}" {{ $semester->is_active ? 'selected' : '' }}>{{ $semester->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Kelas</label>
                        <select name="school_class_id" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                            <option value="">Semua Kelas</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->level->name ?? '' }} {{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                        <i class="bx bx-download mr-1"></i> Download CSV
                    </button>
                </form>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <h3 class="text-base font-semibold text-zinc-900">Rekap Jurnal</h3>
                <p class="mt-1 text-sm text-neutral-500">Ekspor rekap jurnal dengan filter.</p>
                <form action="{{ route('admin.exports.journals') }}" method="GET" class="mt-4 space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Tahun Ajaran</label>
                            <select name="academic_year_id" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                                <option value="">Semua</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year->id }}" {{ $year->is_active ? 'selected' : '' }}>{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Semester</label>
                            <select name="semester_id" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                                <option value="">Semua</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->id }}" {{ $semester->is_active ? 'selected' : '' }}>{{ $semester->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Kelas</label>
                        <select name="school_class_id" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                            <option value="">Semua Kelas</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->level->name ?? '' }} {{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Tanggal Dari</label>
                            <input type="date" name="date_from" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Tanggal Sampai</label>
                            <input type="date" name="date_to" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Status</label>
                        <select name="status" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                            <option value="">Semua Status</option>
                            <option value="draft">Draft</option>
                            <option value="final">Final</option>
                        </select>
                    </div>
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                        <i class="bx bx-download mr-1"></i> Download CSV
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
