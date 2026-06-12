<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-4xl font-bold text-teal-950">Preview Penempatan</h2>
        </div>
    </x-slot>

    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-200 p-4 text-sm font-medium text-red-950">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <h3 class="mb-3 text-sm font-semibold uppercase text-neutral-500">Konteks Sumber</h3>
            <p class="text-lg font-bold text-teal-950">{{ $sourceClass?->level?->name }} {{ $sourceClass?->name ?? '-' }}</p>
            <p class="mt-1 text-sm text-neutral-600">{{ $sourceYear?->name ?? '-' }} / {{ $sourceSemester?->name ?? '-' }}</p>
        </div>
        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <h3 class="mb-3 text-sm font-semibold uppercase text-neutral-500">Konteks Tujuan</h3>
            <p class="text-lg font-bold text-teal-950">{{ $targetClass?->level?->name }} {{ $targetClass?->name ?? ($targetYear?->name ? 'Tanpa kelas (Lulus/Keluar)' : '-') }}</p>
            <p class="mt-1 text-sm text-neutral-600">{{ $targetYear?->name ?? '-' }} / {{ $targetSemester?->name ?? '-' }}</p>
            @php
                $statusLabels = ['naik' => 'Naik', 'tetap' => 'Tetap', 'pindah' => 'Pindah', 'lulus' => 'Lulus', 'keluar' => 'Keluar'];
                $currentPlacementStatus = request('placement_status', 'naik');
            @endphp
            <p class="mt-1 text-sm text-neutral-600">Status: <span class="font-medium text-teal-950">{{ $statusLabels[$currentPlacementStatus] ?? $currentPlacementStatus }}</span></p>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="rounded-lg bg-white p-4 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <p class="text-xs font-semibold uppercase text-neutral-500">Total</p>
            <p class="text-2xl font-bold text-teal-950">{{ $items->count() }}</p>
        </div>
        <div class="rounded-lg bg-white p-4 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <p class="text-xs font-semibold uppercase text-neutral-500">Valid</p>
            <p class="text-2xl font-bold text-green-700">{{ $validCount }}</p>
        </div>
        <div class="rounded-lg bg-white p-4 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <p class="text-xs font-semibold uppercase text-neutral-500">Perlu Cek / Error</p>
            <p class="text-2xl font-bold text-red-700">{{ $warningCount + $errorCount }}</p>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
        <div class="flex items-center justify-between border-b border-stone-300 bg-white px-6 py-4">
            <h3 class="text-xl font-bold text-teal-950">Hasil Validasi ({{ $items->count() }})</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Status</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">NIS</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Nama Santri</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Kelas Lama</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Kelas Tujuan</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Status Penempatan</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr class="border-t border-stone-300">
                            <td class="px-6 py-4">
                                @if ($item->validation_status === 'valid')
                                    <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Valid</span>
                                @elseif ($item->validation_status === 'warning')
                                    <span class="inline-flex rounded-full bg-orange-300 px-3 py-1 text-xs font-semibold text-orange-950">Perlu Cek</span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-200 px-3 py-1 text-xs font-semibold text-red-950">Error</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-base font-normal text-zinc-900">{{ $item->nis }}</td>
                            <td class="px-6 py-4 text-base font-normal text-zinc-900">{{ $item->student_name }}</td>
                            <td class="px-6 py-4 text-base font-normal text-zinc-900">{{ $item->current_class_label }}</td>
                            <td class="px-6 py-4 text-base font-normal text-zinc-900">{{ $item->target_class_label }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $psLabels = ['naik' => 'Naik', 'tetap' => 'Tetap', 'pindah' => 'Pindah', 'lulus' => 'Lulus', 'keluar' => 'Keluar'];
                                @endphp
                                <span class="inline-flex rounded-full bg-zinc-200 px-3 py-1 text-xs font-semibold text-neutral-700">{{ $psLabels[$item->placement_status] ?? $item->placement_status }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-600">{{ $item->message }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-sm text-neutral-500">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($items->isNotEmpty() && $validCount > 0)
        <form method="POST" action="{{ route('admin.promotions.store') }}" class="mt-6">
            @csrf
            <input type="hidden" name="target_academic_year_id" value="{{ $targetYear->id }}">
            <input type="hidden" name="target_semester_id" value="{{ $targetSemester->id }}">
            @if ($targetClass)
                <input type="hidden" name="target_school_class_id" value="{{ $targetClass->id }}">
            @endif
            <input type="hidden" name="placement_status" value="{{ $currentPlacementStatus ?? request('placement_status', 'naik') }}">
            @foreach ($items as $item)
                @if ($item->validation_status !== 'error' && $item->student)
                    <input type="hidden" name="students[]" value="{{ $item->student->id }}">
                @endif
            @endforeach
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.promotions.index', array_filter([
                    'source_academic_year_id' => request('source_academic_year_id'),
                    'source_semester_id' => request('source_semester_id'),
                    'source_school_class_id' => request('source_school_class_id'),
                ])) }}" class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                    Kembali
                </a>
                <button type="submit" class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-6 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                    Proses Penempatan ({{ $validCount }} santri)
                </button>
            </div>
        </form>
    @elseif ($items->isNotEmpty())
        <div class="mt-6 flex items-center justify-between">
            <a href="{{ route('admin.promotions.index', array_filter([
                'source_academic_year_id' => request('source_academic_year_id'),
                'source_semester_id' => request('source_semester_id'),
                'source_school_class_id' => request('source_school_class_id'),
            ])) }}" class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                Kembali
            </a>
            <p class="text-sm font-medium text-red-700">Tidak ada data valid untuk diproses.</p>
        </div>
    @endif
</x-app-layout>
