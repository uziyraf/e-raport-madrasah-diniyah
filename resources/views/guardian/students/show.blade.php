<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Detail Santri</h2>
    </x-slot>

    <div class="space-y-6">
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
                @if ($student->activeEnrollment?->schoolClass)
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Kelas Aktif</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">
                        {{ $student->activeEnrollment->schoolClass->level->name ?? '' }} - {{ $student->activeEnrollment->schoolClass->name }}
                    </dd>
                </div>
                @endif
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
            </dl>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('guardian.students.index') }}"
               class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                Kembali
            </a>
        </div>
    </div>
</x-app-layout>
