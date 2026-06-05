<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('guardian.students.index') }}"
               class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-3 py-2 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                &larr; Kembali
            </a>
            <h2 class="text-xl font-bold text-teal-950">Detail Santri</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="flex items-start gap-6 rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            @if ($student->photo_path)
                <img src="{{ asset('storage/' . $student->photo_path) }}" alt="{{ $student->name }}"
                     class="h-24 w-24 rounded-full object-cover outline outline-1 outline-stone-300">
            @else
                <div class="flex h-24 w-24 items-center justify-center rounded-full bg-teal-100 text-2xl font-bold text-teal-950 outline outline-1 outline-stone-300">
                    {{ strtoupper(substr($student->name, 0, 2)) }}
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <h3 class="text-xl font-bold text-zinc-900">{{ $student->name }}</h3>
                <p class="text-sm text-neutral-500">NIS: {{ $student->nis }}</p>
                @if ($student->activeEnrollment?->schoolClass)
                    <p class="mt-1 text-sm text-neutral-500">
                        {{ $student->activeEnrollment->schoolClass->level->name ?? '' }} - {{ $student->activeEnrollment->schoolClass->name }}
                    </p>
                @endif
                <div class="mt-2">
                    @if ($student->status === 'active')
                        <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Aktif</span>
                    @else
                        <span class="inline-flex rounded-full bg-zinc-200 px-3 py-1 text-xs font-semibold text-neutral-700">Nonaktif</span>
                    @endif
                </div>
            </div>
            <div class="flex flex-col gap-2">
                @php
                    $attendanceUrl = route('guardian.attendances.index', ['view' => 'calendar', 'student_id' => $student->id, 'month' => now()->month, 'year' => now()->year]);
                @endphp
                <a href="{{ $attendanceUrl }}"
                   class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                    Lihat Absensi
                </a>
            </div>
        </div>

        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <h3 class="mb-4 text-lg font-semibold text-teal-950">Identitas Santri</h3>
            <dl class="grid grid-cols-1 gap-x-6 gap-y-4 md:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">NIS</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->nis }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Nama Santri</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Jenis Kelamin</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->gender === 'male' ? 'Laki-laki' : ($student->gender === 'female' ? 'Perempuan' : '-') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Status</dt>
                    <dd class="mt-1">
                        @if ($student->status === 'active')
                            <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Aktif</span>
                        @else
                            <span class="inline-flex rounded-full bg-zinc-200 px-3 py-1 text-xs font-semibold text-neutral-700">Nonaktif</span>
                        @endif
                    </dd>
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
                @if ($student->address)
                <div class="md:col-span-2">
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Alamat</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->address }}</dd>
                </div>
                @endif
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Nama Ayah</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->father_name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Nama Ibu</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->mother_name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Nama Wali Santri</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->guardian_name ?? ($guardian->name ?? '-') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">No. Telepon Wali</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->guardian_phone ?? ($guardian->phone ?? '-') }}</dd>
                </div>
            </dl>
        </div>

        @if ($student->activeEnrollment)
        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <h3 class="mb-4 text-lg font-semibold text-teal-950">Kelas Aktif</h3>
            <dl class="grid grid-cols-1 gap-x-6 gap-y-4 md:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Jenjang</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->activeEnrollment->schoolClass->level->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Kelas</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->activeEnrollment->schoolClass->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Tahun Ajaran</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->activeEnrollment->academicYear->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Semester</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $student->activeEnrollment->semester->name ?? '-' }}</dd>
                </div>
            </dl>
        </div>
        @endif

        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <h3 class="mb-4 text-lg font-semibold text-teal-950">Ringkasan Absensi</h3>
            @if ($activeYear && $activeSemester)
                <p class="mb-4 text-sm text-neutral-500">
                    Tahun Ajaran {{ $activeYear->name }} / {{ $activeSemester->name }}
                </p>
            @endif
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <div class="rounded-sm bg-emerald-200 px-4 py-3 text-center">
                    <span class="block text-lg font-bold text-green-950">{{ $attendanceSummary['present'] ?? 0 }}</span>
                    <span class="text-xs font-semibold uppercase text-green-950">Hadir</span>
                </div>
                <div class="rounded-sm bg-yellow-100 px-4 py-3 text-center">
                    <span class="block text-lg font-bold text-yellow-900">{{ $attendanceSummary['sick'] ?? 0 }}</span>
                    <span class="text-xs font-semibold uppercase text-yellow-900">Sakit</span>
                </div>
                <div class="rounded-sm bg-sky-200 px-4 py-3 text-center">
                    <span class="block text-lg font-bold text-sky-950">{{ $attendanceSummary['permission'] ?? 0 }}</span>
                    <span class="text-xs font-semibold uppercase text-sky-950">Izin</span>
                </div>
                <div class="rounded-sm bg-red-200 px-4 py-3 text-center">
                    <span class="block text-lg font-bold text-red-950">{{ $attendanceSummary['absent'] ?? 0 }}</span>
                    <span class="text-xs font-semibold uppercase text-red-950">Alfa</span>
                </div>
            </div>
        </div>

        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300 opacity-50">
            <h3 class="mb-4 text-lg font-semibold text-teal-950">Raport</h3>
            <p class="text-sm text-neutral-500">Fitur raport belum tersedia.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('guardian.students.index') }}"
               class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                Kembali
            </a>
        </div>
    </div>
</x-app-layout>
