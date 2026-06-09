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
        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <div x-data="{ tab: '{{ $activeTab }}' }" class="space-y-4">
                <div class="flex flex-wrap gap-2 border-b border-stone-300 pb-2">
                    <button @click="tab = 'search'" :class="tab === 'search' ? 'border-b-2 border-teal-950 text-teal-950' : 'text-neutral-500'" class="px-4 py-2 text-sm font-medium transition hover:text-teal-950" type="button">
                        Pencarian Santri
                    </button>
                    <button @click="tab = 'import'" :class="tab === 'import' ? 'border-b-2 border-teal-950 text-teal-950' : 'text-neutral-500'" class="px-4 py-2 text-sm font-medium transition hover:text-teal-950" type="button">
                        Import Excel/CSV
                    </button>
                </div>

                <div x-show="tab === 'search'" x-cloak>
                    <div class="space-y-6">
                        <form method="POST" action="{{ route('admin.promotions.search') }}" class="space-y-4">
                            @csrf

                            @if ($errors->search->any())
                                <div class="rounded-lg bg-red-200 p-4 text-sm font-medium text-red-950">
                                    <ul class="list-inside list-disc">
                                        @foreach ($errors->search->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                                <div>
                                    <label for="source_academic_year_id" class="mb-2 block text-sm font-medium text-neutral-700">Tahun Ajaran Asal <span class="text-xs text-neutral-400">(opsional)</span></label>
                                    <select name="source_academic_year_id" id="source_academic_year_id"
                                            class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                        <option value="">Pilih Tahun Ajaran</option>
                                        @foreach ($years as $year)
                                            <option value="{{ $year->id }}" @selected(request('source_academic_year_id') == $year->id)>{{ $year->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="source_semester_id" class="mb-2 block text-sm font-medium text-neutral-700">Semester Asal <span class="text-xs text-neutral-400">(opsional)</span></label>
                                    <select name="source_semester_id" id="source_semester_id"
                                            class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                        <option value="">Pilih Semester</option>
                                        @foreach ($semesters as $semester)
                                            <option value="{{ $semester->id }}" @selected(request('source_semester_id') == $semester->id)>{{ $semester->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="source_school_class_id" class="mb-2 block text-sm font-medium text-neutral-700">Kelas Asal <span class="text-xs text-neutral-400">(opsional)</span></label>
                                    <select name="source_school_class_id" id="source_school_class_id"
                                            class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                        <option value="">Pilih Kelas</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}" @selected(request('source_school_class_id') == $class->id)>{{ $class->level->name }} - {{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="keyword" class="mb-2 block text-sm font-medium text-neutral-700">Keyword <span class="text-xs text-neutral-400">(opsional)</span></label>
                                    <input type="text" name="keyword" id="keyword" value="{{ request('keyword') }}"
                                           placeholder="Cari NIS atau Nama"
                                           class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <p class="text-xs text-neutral-500">Isi minimal salah satu filter untuk mencari santri.</p>
                                <button type="submit"
                                        class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-6 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                                    Preview Santri
                                </button>
                            </div>
                        </form>

                        @if ($results !== null)
                            <form method="POST" action="{{ route('admin.promotions.preview') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="mode" value="class">
                                <input type="hidden" name="source_academic_year_id" value="{{ request('source_academic_year_id') }}">
                                <input type="hidden" name="source_semester_id" value="{{ request('source_semester_id') }}">
                                <input type="hidden" name="source_school_class_id" value="{{ request('source_school_class_id') }}">

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                                    <div>
                                        <label for="target_academic_year_id" class="mb-2 block text-sm font-medium text-neutral-700">Tahun Ajaran Tujuan</label>
                                        <select name="target_academic_year_id" id="target_academic_year_id" required
                                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                            <option value="">Pilih Tahun Ajaran</option>
                                            @foreach ($years as $year)
                                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="target_semester_id" class="mb-2 block text-sm font-medium text-neutral-700">Semester Tujuan</label>
                                        <select name="target_semester_id" id="target_semester_id" required
                                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                            <option value="">Pilih Semester</option>
                                            @foreach ($semesters as $semester)
                                                <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="target_school_class_id" class="mb-2 block text-sm font-medium text-neutral-700">Kelas Tujuan</label>
                                        <select name="target_school_class_id" id="target_school_class_id"
                                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                                            <option value="">Pilih Kelas (opsional untuk Lulus/Keluar)</option>
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->level->name }} - {{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="placement_status" class="mb-2 block text-sm font-medium text-neutral-700">Status Penempatan</label>
                                        <select name="placement_status" id="placement_status" required
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
                                        <h4 class="text-base font-bold text-teal-950">Hasil Pencarian</h4>
                                        <span class="text-xs font-semibold uppercase text-neutral-500">
                                            {{ $results->count() }} santri
                                        </span>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="w-full">
                                            <thead>
                                                <tr>
                                                    <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">
                                                        <input type="checkbox" id="select-all-results" class="rounded border-stone-300 text-teal-950 focus:ring-teal-950/10">
                                                    </th>
                                                    <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">NIS</th>
                                                    <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Nama Santri</th>
                                                    <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Kelas Saat Ini</th>
                                                    <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Tahun Ajaran</th>
                                                    <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Semester</th>
                                                    <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Status</th>
                                                    <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($results as $item)
                                                    <tr class="border-t border-stone-300">
                                                        <td class="px-6 py-4 text-base font-normal text-zinc-900">
                                                            <input type="checkbox" name="students[]" value="{{ $item->student->id }}" class="result-student-checkbox rounded border-stone-300 text-teal-950 focus:ring-teal-950/10">
                                                        </td>
                                                        <td class="px-6 py-4 text-base font-normal text-zinc-900">{{ $item->nis }}</td>
                                                        <td class="px-6 py-4 text-base font-normal text-zinc-900">{{ $item->student_name }}</td>
                                                        <td class="px-6 py-4 text-base font-normal text-zinc-900">{{ $item->current_class_label }}</td>
                                                        <td class="px-6 py-4 text-base font-normal text-zinc-900">{{ $item->current_academic_year }}</td>
                                                        <td class="px-6 py-4 text-base font-normal text-zinc-900">{{ $item->current_semester }}</td>
                                                        <td class="px-6 py-4">
                                                            <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Aktif</span>
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            <span class="inline-flex cursor-not-allowed items-center justify-center rounded-sm bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-400">
                                                                Detail
                                                            </span>
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
                        @elseif (request('keyword') || request('source_academic_year_id') || request('source_school_class_id'))
                            <p class="py-4 text-center text-sm text-neutral-500">Tidak ada santri ditemukan dengan filter yang dipilih.</p>
                        @endif
                    </div>
                </div>

                <div x-show="tab === 'import'" x-cloak>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div></div>
                            <a href="{{ route('admin.promotions.template') }}" class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                                Download Template CSV
                            </a>
                        </div>

                        <form method="POST" action="{{ route('admin.promotions.index') }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
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

                        @if ($importItems !== null)
                            <form method="POST" action="{{ route('admin.promotions.preview') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="mode" value="class">

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

                                <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
                                    <div class="flex items-center justify-between border-b border-stone-300 bg-white px-6 py-4">
                                        <h4 class="text-base font-bold text-teal-950">Hasil Import</h4>
                                        <span class="text-xs font-semibold uppercase text-neutral-500">
                                            {{ $importItems->count() }} data
                                        </span>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="w-full">
                                            <thead>
                                                <tr>
                                                    <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">
                                                        <input type="checkbox" id="select-all-import" class="rounded border-stone-300 text-teal-950 focus:ring-teal-950/10">
                                                    </th>
                                                    <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">NIS</th>
                                                    <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Nama Santri</th>
                                                    <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Kelas Saat Ini</th>
                                                    <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($importItems as $item)
                                                    <tr class="border-t border-stone-300">
                                                        <td class="px-6 py-4 text-base font-normal text-zinc-900">
                                                            @if ($item->validation_status !== 'error')
                                                                <input type="checkbox" name="students[]" value="{{ $item->student->id }}" class="import-student-checkbox rounded border-stone-300 text-teal-950 focus:ring-teal-950/10">
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-4 text-base font-normal text-zinc-900">{{ $item->nis }}</td>
                                                        <td class="px-6 py-4 text-base font-normal text-zinc-900">{{ $item->student_name }}</td>
                                                        <td class="px-6 py-4 text-base font-normal text-zinc-900">{{ $item->current_class_label }}</td>
                                                        <td class="px-6 py-4">
                                                            @if ($item->validation_status === 'valid')
                                                                <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Ditemukan</span>
                                                            @elseif ($item->validation_status === 'warning')
                                                                <span class="inline-flex rounded-full bg-yellow-200 px-3 py-1 text-xs font-semibold text-yellow-950">{{ $item->message }}</span>
                                                            @else
                                                                <span class="inline-flex rounded-full bg-red-200 px-3 py-1 text-xs font-semibold text-red-950">{{ $item->message }}</span>
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function initSelectAll(selectId, checkboxClass) {
            var selectAll = document.getElementById(selectId);
            if (!selectAll) return;

            function updateSelectAll() {
                var checkboxes = document.querySelectorAll('.' + checkboxClass);
                var checked = document.querySelectorAll('.' + checkboxClass + ':checked');
                selectAll.checked = checkboxes.length > 0 && checked.length === checkboxes.length;
            }

            selectAll.addEventListener('change', function () {
                document.querySelectorAll('.' + checkboxClass).forEach(function (cb) {
                    cb.checked = selectAll.checked;
                });
            });

            document.querySelectorAll('.' + checkboxClass).forEach(function (cb) {
                cb.addEventListener('change', updateSelectAll);
            });

            updateSelectAll();
        }

        initSelectAll('select-all-results', 'result-student-checkbox');
        initSelectAll('select-all-import', 'import-student-checkbox');
    </script>
    @endpush

    @push('styles')
    <style>[x-cloak] { display: none !important; }</style>
    @endpush
</x-app-layout>
