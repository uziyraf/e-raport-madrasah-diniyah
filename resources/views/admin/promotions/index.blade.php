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

    <div class="space-y-6">
        <form method="GET" action="{{ route('admin.promotions.index') }}">
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <h3 class="mb-4 text-xl font-bold text-teal-950">Periode Sumber</h3>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div>
                        <label for="source_academic_year_id" class="mb-2 block text-sm font-medium text-neutral-700">Tahun Ajaran Asal</label>
                        <select name="source_academic_year_id" id="source_academic_year_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10 @error('source_academic_year_id') border-red-500 @enderror">
                            <option value="">Pilih Tahun Ajaran</option>
                            @foreach ($years as $year)
                                <option value="{{ $year->id }}" @selected(request('source_academic_year_id') == $year->id)>{{ $year->name }}</option>
                            @endforeach
                        </select>
                        @error('source_academic_year_id')
                            <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="source_semester_id" class="mb-2 block text-sm font-medium text-neutral-700">Semester Asal</label>
                        <select name="source_semester_id" id="source_semester_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10 @error('source_semester_id') border-red-500 @enderror">
                            <option value="">Pilih Semester</option>
                            @foreach ($semesters as $semester)
                                <option value="{{ $semester->id }}" @selected(request('source_semester_id') == $semester->id)>{{ $semester->name }}</option>
                            @endforeach
                        </select>
                        @error('source_semester_id')
                            <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="source_school_class_id" class="mb-2 block text-sm font-medium text-neutral-700">Kelas Asal</label>
                        <select name="source_school_class_id" id="source_school_class_id"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10 @error('source_school_class_id') border-red-500 @enderror">
                            <option value="">Pilih Kelas</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" @selected(request('source_school_class_id') == $class->id)>{{ $class->level->name }} - {{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('source_school_class_id')
                            <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                                class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                            Tampilkan Santri
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <h3 class="mb-4 text-xl font-bold text-teal-950">Metode Seleksi &amp; Target Penempatan</h3>

            @if ($errors->preview->any())
                <div class="mb-4 rounded-lg bg-red-200 p-4 text-sm font-medium text-red-950">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->preview->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if ($errors->store->any())
                <div class="mb-4 rounded-lg bg-red-200 p-4 text-sm font-medium text-red-950">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->store->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if ($errors->import->any())
                <div class="mb-4 rounded-lg bg-red-200 p-4 text-sm font-medium text-red-950">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->import->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div x-data="{ tab: 'class' }" class="space-y-4">
                <div class="flex flex-wrap gap-2 border-b border-stone-300 pb-2">
                    <button @click="tab = 'class'" :class="tab === 'class' ? 'border-b-2 border-teal-950 text-teal-950' : 'text-neutral-500'" class="px-4 py-2 text-sm font-medium transition hover:text-teal-950" type="button">
                        Dari Kelas Asal
                    </button>
                    <button @click="tab = 'bulk'" :class="tab === 'bulk' ? 'border-b-2 border-teal-950 text-teal-950' : 'text-neutral-500'" class="px-4 py-2 text-sm font-medium transition hover:text-teal-950" type="button">
                        Paste / Cari NIS Massal
                    </button>
                    <button @click="tab = 'import'" :class="tab === 'import' ? 'border-b-2 border-teal-950 text-teal-950' : 'text-neutral-500'" class="px-4 py-2 text-sm font-medium transition hover:text-teal-950" type="button">
                        Import Excel/CSV
                    </button>
                </div>

                <div x-show="tab === 'class'" x-cloak>
                    @if ($students->isNotEmpty())
                        <form method="POST" action="{{ route('admin.promotions.preview') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="mode" value="class">
                            <input type="hidden" name="source_academic_year_id" value="{{ request('source_academic_year_id') }}">
                            <input type="hidden" name="source_semester_id" value="{{ request('source_semester_id') }}">
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
                </div>

                <div x-show="tab === 'bulk'" x-cloak>
                    <form method="POST" action="{{ route('admin.promotions.preview') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="mode" value="bulk">

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                            <div>
                                <label for="bulk_target_academic_year_id" class="mb-2 block text-sm font-medium text-neutral-700">Tahun Ajaran Tujuan</label>
                                <select name="target_academic_year_id" id="bulk_target_academic_year_id" required
                                        class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                    <option value="">Pilih Tahun Ajaran</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="bulk_target_semester_id" class="mb-2 block text-sm font-medium text-neutral-700">Semester Tujuan</label>
                                <select name="target_semester_id" id="bulk_target_semester_id" required
                                        class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                    <option value="">Pilih Semester</option>
                                    @foreach ($semesters as $semester)
                                        <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="bulk_target_school_class_id" class="mb-2 block text-sm font-medium text-neutral-700">Kelas Tujuan</label>
                                <select name="target_school_class_id" id="bulk_target_school_class_id"
                                        class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                    <option value="">Pilih Kelas (opsional untuk Lulus/Keluar)</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->level->name }} - {{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="bulk_placement_status" class="mb-2 block text-sm font-medium text-neutral-700">Status Penempatan</label>
                                <select name="placement_status" id="bulk_placement_status" required
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

                        <div>
                            <label for="bulk_input" class="mb-2 block text-sm font-medium text-neutral-700">Masukkan NIS atau Nama Santri</label>
                            <textarea name="bulk_input" id="bulk_input" rows="8" class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10" placeholder="001&#10;002&#10;003&#10;Ahmad&#10;Siti Aisyah"></textarea>
                            <p class="mt-1 text-xs text-neutral-500">Pisahkan dengan baris baru atau koma. Sistem akan mencari berdasarkan NIS, lalu berdasarkan Nama.</p>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-6 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                                Preview Penempatan
                            </button>
                        </div>
                    </form>
                </div>

                <div x-show="tab === 'import'" x-cloak>
                    <div class="mb-4">
                        <a href="{{ route('admin.promotions.template') }}" class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                            Download Template CSV
                        </a>
                    </div>
                    <form method="POST" action="{{ route('admin.promotions.import-preview') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                            <div>
                                <label for="import_target_academic_year_id" class="mb-2 block text-sm font-medium text-neutral-700">Tahun Ajaran Tujuan</label>
                                <select name="target_academic_year_id" id="import_target_academic_year_id" required
                                        class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                    <option value="">Pilih Tahun Ajaran</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="import_target_semester_id" class="mb-2 block text-sm font-medium text-neutral-700">Semester Tujuan</label>
                                <select name="target_semester_id" id="import_target_semester_id" required
                                        class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                    <option value="">Pilih Semester</option>
                                    @foreach ($semesters as $semester)
                                        <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="import_target_school_class_id" class="mb-2 block text-sm font-medium text-neutral-700">Kelas Tujuan</label>
                                <select name="target_school_class_id" id="import_target_school_class_id"
                                        class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                    <option value="">Pilih Kelas (opsional untuk Lulus/Keluar)</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->level->name }} - {{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="import_placement_status" class="mb-2 block text-sm font-medium text-neutral-700">Status Penempatan</label>
                                <select name="placement_status" id="import_placement_status" required
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

                        <div>
                            <label for="import_file" class="mb-2 block text-sm font-medium text-neutral-700">Upload File CSV</label>
                            <input type="file" name="import_file" id="import_file" accept=".csv,.txt" class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10 file:mr-4 file:rounded-sm file:border-0 file:bg-teal-950 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white">
                            <p class="mt-1 text-xs text-neutral-500">Format CSV dengan delimiter semicolon (;). Maksimal 2MB.</p>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-6 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                                Preview Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        var selectAll = document.getElementById('select-all-students');

        function updateSelectAll() {
            var checkboxes = document.querySelectorAll('.student-checkbox');
            var checked = document.querySelectorAll('.student-checkbox:checked');
            selectAll.checked = checkboxes.length > 0 && checked.length === checkboxes.length;
        }

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                document.querySelectorAll('.student-checkbox').forEach(function (cb) {
                    cb.checked = selectAll.checked;
                });
            });
        }

        document.querySelectorAll('.student-checkbox').forEach(function (cb) {
            cb.addEventListener('change', updateSelectAll);
        });

        updateSelectAll();
    </script>
    @endpush

    @push('styles')
    <style>[x-cloak] { display: none !important; }</style>
    @endpush
</x-app-layout>
