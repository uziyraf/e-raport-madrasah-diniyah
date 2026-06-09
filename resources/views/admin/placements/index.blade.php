<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-4xl font-bold text-teal-950">Kenaikan/Penempatan Santri</h2>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-emerald-200 p-4 text-sm font-medium text-green-950">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-lg bg-red-200 p-4 text-sm font-medium text-red-950">
            {{ session('error') }}
        </div>
    @endif

    @if (session('warning_details'))
        <div class="mb-6 rounded-lg bg-yellow-200 p-4 text-sm font-medium text-yellow-950">
            <ul class="list-inside list-disc">
                @foreach (session('warning_details') as $warning)
                    <li>{{ $warning }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- LEFT PANEL --}}
        <div class="space-y-6">
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <h3 class="mb-4 text-lg font-bold text-teal-950">Pencarian Santri</h3>

                <form method="GET" action="{{ route('admin.placements.index') }}" class="space-y-4">
                    <div>
                        <label for="keyword" class="mb-2 block text-sm font-medium text-neutral-700">NIS atau Nama Santri</label>
                        <input type="text" name="keyword" id="keyword" value="{{ $keyword }}"
                               placeholder="Cari NIS atau Nama..."
                               class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                    </div>

                    <div>
                        <label for="filter_type" class="mb-2 block text-sm font-medium text-neutral-700">Filter Status</label>
                        <select name="filter_type" id="filter_type"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="all" @selected($filterType === 'all')>Semua Santri</option>
                            <option value="no_class" @selected($filterType === 'no_class')>Belum Punya Kelas</option>
                            <option value="has_class" @selected($filterType === 'has_class')>Sudah Punya Kelas</option>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-6 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
                <div class="flex items-center justify-between border-b border-stone-300 bg-white px-6 py-4">
                    <h4 class="text-base font-bold text-teal-950">Hasil Pencarian</h4>
                    <span class="text-xs font-semibold uppercase text-neutral-500">
                        {{ $students->total() }} santri
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="border-b border-stone-300 bg-white px-4 py-3 text-left text-sm font-medium text-neutral-700">NIS</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-3 text-left text-sm font-medium text-neutral-700">Nama Santri</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-3 text-left text-sm font-medium text-neutral-700">Kelas Saat Ini</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-3 text-center text-sm font-medium text-neutral-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($results as $item)
                                <tr class="border-t border-stone-300">
                                    <td class="px-4 py-3 text-sm font-normal text-zinc-900">{{ $item->nis }}</td>
                                    <td class="px-4 py-3 text-sm font-normal text-zinc-900">{{ $item->student_name }}</td>
                                    <td class="px-4 py-3 text-sm font-normal text-zinc-900">{{ $item->current_class_label }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <form method="POST" action="{{ route('admin.placements.add-student') }}" class="inline">
                                                @csrf
                                                <input type="hidden" name="student_id" value="{{ $item->student->id }}">
                                                <input type="hidden" name="keyword" value="{{ $keyword }}">
                                                <input type="hidden" name="filter_type" value="{{ $filterType }}">
                                                <input type="hidden" name="page" value="{{ $students->currentPage() }}">
                                                <button type="submit"
                                                        class="inline-flex items-center justify-center rounded-sm bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-emerald-700"
                                                        title="Tambahkan ke target penempatan">
                                                    +
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-10 text-center text-sm text-neutral-500">
                                        @if ($keyword)
                                            Tidak ada santri ditemukan.
                                        @else
                                            Gunakan form pencarian di atas atau biarkan kosong untuk menampilkan semua santri.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($students->hasPages())
                    <div class="border-t border-stone-300 px-6 py-4">
                        {{ $students->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="space-y-6">
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <h3 class="mb-4 text-lg font-bold text-teal-950">Target Penempatan</h3>

                {{-- STORE FORM: target fields + hidden students[] + Simpan button --}}
                <form id="form-store" method="POST" action="{{ route('admin.placements.store') }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="target_academic_year_id" class="mb-2 block text-sm font-medium text-neutral-700">Tahun Ajaran Tujuan</label>
                            <select name="target_academic_year_id" id="target_academic_year_id" required
                                    class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10 @error('target_academic_year_id') border-red-400 @enderror">
                                <option value="">Pilih Tahun Ajaran</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year->id }}" @selected(old('target_academic_year_id') == $year->id)>{{ $year->name }}</option>
                                @endforeach
                            </select>
                            @error('target_academic_year_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="target_semester_id" class="mb-2 block text-sm font-medium text-neutral-700">Semester Tujuan</label>
                            <select name="target_semester_id" id="target_semester_id" required
                                    class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10 @error('target_semester_id') border-red-400 @enderror">
                                <option value="">Pilih Semester</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->id }}" @selected(old('target_semester_id') == $semester->id)>{{ $semester->name }}</option>
                                @endforeach
                            </select>
                            @error('target_semester_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="target_school_class_id" class="mb-2 block text-sm font-medium text-neutral-700">Kelas Tujuan</label>
                            <select name="target_school_class_id" id="target_school_class_id"
                                    class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10 @error('target_school_class_id') border-red-400 @enderror">
                                <option value="">Pilih Kelas (opsional untuk Lulus/Keluar)</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}" @selected(old('target_school_class_id') == $class->id)>{{ $class->level->name }} - {{ $class->name }}</option>
                                @endforeach
                            </select>
                            @error('target_school_class_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="placement_status" class="mb-2 block text-sm font-medium text-neutral-700">Status Penempatan</label>
                            <select name="placement_status" id="placement_status" required
                                    class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10 @error('placement_status') border-red-400 @enderror">
                                <option value="naik" @selected(old('placement_status') === 'naik')>Naik</option>
                                <option value="tetap" @selected(old('placement_status') === 'tetap')>Tetap</option>
                                <option value="pindah" @selected(old('placement_status') === 'pindah')>Pindah</option>
                                <option value="lulus" @selected(old('placement_status') === 'lulus')>Lulus</option>
                                <option value="keluar" @selected(old('placement_status') === 'keluar')>Keluar</option>
                            </select>
                            @error('placement_status')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-neutral-500">Kelas Tujuan wajib untuk status Naik, Tetap, dan Pindah.</p>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="rounded-lg bg-red-200 p-4 text-sm font-medium text-red-950">
                            <ul class="list-inside list-disc">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- hidden students[] inputs for store form --}}
                    @foreach ($selectedStudents as $item)
                        <input type="hidden" name="students[]" value="{{ $item->student->id }}">
                    @endforeach
                </form>

                {{-- SELECTED STUDENTS DISPLAY (separate from store form to avoid nested forms) --}}
                <div class="mt-4 overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <div class="flex items-center justify-between border-b border-stone-300 bg-white px-4 py-3">
                        <h4 class="text-sm font-bold text-teal-950">Santri Terpilih</h4>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold uppercase text-neutral-500">
                                {{ $selectedStudents->count() }} santri
                            </span>
                            @if ($selectedStudents->isNotEmpty())
                                <form method="POST" action="{{ route('admin.placements.clear-students') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="keyword" value="{{ $keyword }}">
                                    <input type="hidden" name="filter_type" value="{{ $filterType }}">
                                    <input type="hidden" name="page" value="{{ $students->currentPage() }}">
                                    <button type="submit"
                                            class="inline-flex items-center justify-center rounded-sm bg-red-100 px-2 py-1 text-xs font-medium text-red-700 transition hover:bg-red-200"
                                            onclick="return confirm('Kosongkan semua santri terpilih?')">
                                        Kosongkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="border-b border-stone-300 bg-white px-4 py-3 text-left text-sm font-medium text-neutral-700">NIS</th>
                                    <th class="border-b border-stone-300 bg-white px-4 py-3 text-left text-sm font-medium text-neutral-700">Nama Santri</th>
                                    <th class="border-b border-stone-300 bg-white px-4 py-3 text-left text-sm font-medium text-neutral-700">Kelas Saat Ini</th>
                                    <th class="border-b border-stone-300 bg-white px-4 py-3 text-center text-sm font-medium text-neutral-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($selectedStudents as $item)
                                    <tr class="border-t border-stone-300">
                                        <td class="px-4 py-3 text-sm font-normal text-zinc-900">{{ $item->nis }}</td>
                                        <td class="px-4 py-3 text-sm font-normal text-zinc-900">{{ $item->student_name }}</td>
                                        <td class="px-4 py-3 text-sm font-normal text-zinc-900">{{ $item->current_class_label }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <form method="POST" action="{{ route('admin.placements.remove-student') }}" class="inline">
                                                @csrf
                                                <input type="hidden" name="student_id" value="{{ $item->student->id }}">
                                                <input type="hidden" name="keyword" value="{{ $keyword }}">
                                                <input type="hidden" name="filter_type" value="{{ $filterType }}">
                                                <input type="hidden" name="page" value="{{ $students->currentPage() }}">
                                                <button type="submit"
                                                        class="inline-flex items-center justify-center rounded-sm bg-red-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-red-700"
                                                        title="Hapus dari target">
                                                    -
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-10 text-center text-sm text-neutral-500">
                                            Belum ada santri dipilih. Klik tombol (+) pada hasil pencarian untuk menambahkan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($selectedStudents->isNotEmpty())
                    <div class="flex justify-end">
                        <button type="submit" form="form-store"
                                class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-6 py-3 text-sm font-medium text-white transition hover:bg-emerald-900"
                                onclick="return confirm('Simpan penempatan untuk {{ $selectedStudents->count() }} santri?')">
                            Simpan Penempatan
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
