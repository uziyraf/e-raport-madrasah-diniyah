<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Detail Santri</h2>
    </x-slot>

    <div class="space-y-6">
        {{-- Card Identitas --}}
        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <h3 class="mb-4 text-lg font-semibold text-teal-950">Identitas Santri</h3>
            <dl class="grid grid-cols-1 gap-x-6 gap-y-4 md:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">NIS</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->nis }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Nama Lengkap</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->name }}</dd>
                </div>
                @if ($student->arabic_name)
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Nama Arab</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900" lang="ar" dir="rtl" class="font-arabic leading-loose">{{ $student->arabic_name }}</dd>
                </div>
                @endif
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Jenis Kelamin</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->gender === 'male' ? 'Laki-laki' : ($student->gender === 'female' ? 'Perempuan' : '-') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Tempat, Tanggal Lahir</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">
                        @if ($student->birth_place || $student->birth_date)
                            {{ $student->birth_place ?? '-' }}{{ $student->birth_place && $student->birth_date ? ', ' : '' }}{{ $student->birth_date ? $student->birth_date->format('d/m/Y') : '' }}
                        @else
                            -
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Status Santri</dt>
                    <dd class="mt-1">
                        @if ($student->status === 'active')
                            <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Aktif</span>
                        @else
                            <span class="inline-flex rounded-full bg-zinc-200 px-3 py-1 text-xs font-semibold text-neutral-700">Nonaktif</span>
                        @endif
                    </dd>
                </div>
                @if ($student->address)
                <div class="md:col-span-2">
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Alamat</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->address }}</dd>
                </div>
                @endif
                @if ($student->photo_path)
                <div class="md:col-span-2">
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Foto</dt>
                    <dd class="mt-1">
                        <img src="{{ Storage::url($student->photo_path) }}" alt="Foto Santri"
                             class="max-h-40 rounded-sm border border-stone-300">
                    </dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Card Orang Tua / Wali --}}
        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <h3 class="mb-4 text-lg font-semibold text-teal-950">Orang Tua / Wali</h3>
            <dl class="grid grid-cols-1 gap-x-6 gap-y-4 md:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Nama Ayah</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->father_name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Nama Ibu</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->mother_name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Nama Wali</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->guardian_name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">No. HP Wali</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->guardian_phone ?? '-' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Card Kelas Aktif --}}
        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <h3 class="mb-4 text-lg font-semibold text-teal-950">Kelas Aktif</h3>
            @if ($student->activeEnrollment)
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 md:grid-cols-3">
                    <div>
                        <dt class="text-xs font-semibold uppercase text-neutral-500">Kelas</dt>
                        <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->activeEnrollment->schoolClass->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-neutral-500">Jenjang</dt>
                        <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->activeEnrollment->schoolClass->level->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-neutral-500">Tahun Ajaran / Semester</dt>
                        <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->activeEnrollment->academicYear->name }} / {{ $student->activeEnrollment->semester->name }}</dd>
                    </div>
                </dl>
            @else
                <p class="text-base font-normal text-neutral-500">Belum ditempatkan di kelas.</p>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.students.edit', $student) }}"
               class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                Edit Santri
            </a>
            <a href="{{ route('admin.students.index') }}"
               class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                Kembali
            </a>
        </div>
    </div>
</x-app-layout>
