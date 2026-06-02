<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Detail Guru</h2>
    </x-slot>

    <div class="space-y-6">
        {{-- Card Identitas --}}
        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <h3 class="mb-4 text-lg font-semibold text-teal-950">Identitas Guru</h3>
            <dl class="grid grid-cols-1 gap-x-6 gap-y-4 md:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Nama Guru</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $teacher->name }}</dd>
                </div>
                @if ($teacher->arabic_name)
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Nama Arab</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900" lang="ar" dir="rtl" class="font-arabic leading-loose">{{ $teacher->arabic_name }}</dd>
                </div>
                @endif
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Kode Guru</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $teacher->teacher_code ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Status Guru</dt>
                    <dd class="mt-1">
                        @if ($teacher->status === 'active')
                            <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Aktif</span>
                        @else
                            <span class="inline-flex rounded-full bg-zinc-200 px-3 py-1 text-xs font-semibold text-neutral-700">Nonaktif</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Jenis Kelamin</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $teacher->gender === 'male' ? 'Laki-laki' : ($teacher->gender === 'female' ? 'Perempuan' : '-') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Tempat, Tanggal Lahir</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">
                        @if ($teacher->birth_place || $teacher->birth_date)
                            {{ $teacher->birth_place ?? '-' }}{{ $teacher->birth_place && $teacher->birth_date ? ', ' : '' }}{{ $teacher->birth_date ? $teacher->birth_date->format('d/m/Y') : '' }}
                        @else
                            -
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Kontak</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">
                        @if ($teacher->phone)
                            <span class="block">{{ $teacher->phone }}</span>
                        @endif
                        @if ($teacher->email)
                            <span class="block">{{ $teacher->email }}</span>
                        @endif
                        @if (!$teacher->phone && !$teacher->email)
                            -
                        @endif
                    </dd>
                </div>
                @if ($teacher->address)
                <div class="md:col-span-2">
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Alamat</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $teacher->address }}</dd>
                </div>
                @endif
                @if ($teacher->signature_path)
                <div class="md:col-span-2">
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Tanda Tangan</dt>
                    <dd class="mt-1">
                        <img src="{{ Storage::url($teacher->signature_path) }}" alt="Tanda Tangan"
                             class="max-h-20 rounded-sm border border-stone-300">
                    </dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Card Akun --}}
        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <h3 class="mb-4 text-lg font-semibold text-teal-950">Akun Login</h3>
            @if ($teacher->user)
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 md:grid-cols-3">
                    <div>
                        <dt class="text-xs font-semibold uppercase text-neutral-500">Username</dt>
                        <dd class="mt-1 text-base font-normal text-zinc-900">{{ $teacher->user->username }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-neutral-500">Email Login</dt>
                        <dd class="mt-1 text-base font-normal text-zinc-900">{{ $teacher->user->email ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-neutral-500">Role Pengguna</dt>
                        <dd class="mt-1">
                            <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">{{ str_replace('_', ' ', ucwords($teacher->user->getRoleNames()->first() ?? '', '_')) }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-neutral-500">Status Akun</dt>
                        <dd class="mt-1">
                            @if ($teacher->user->status === 'active')
                                <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Aktif</span>
                            @else
                                <span class="inline-flex rounded-full bg-zinc-200 px-3 py-1 text-xs font-semibold text-neutral-700">Nonaktif</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            @else
                <p class="text-base font-normal text-neutral-500">Belum memiliki akun login.</p>
            @endif
        </div>

        {{-- Card Wali Kelas --}}
        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <h3 class="mb-4 text-lg font-semibold text-teal-950">Wali Kelas</h3>
            @if ($currentHomeroom)
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 md:grid-cols-3">
                    <div>
                        <dt class="text-xs font-semibold uppercase text-neutral-500">Kelas</dt>
                        <dd class="mt-1 text-base font-normal text-zinc-900">{{ $currentHomeroom->schoolClass->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-neutral-500">Jenjang</dt>
                        <dd class="mt-1 text-base font-normal text-zinc-900">{{ $currentHomeroom->schoolClass->level->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-neutral-500">Tahun Ajaran / Semester</dt>
                        <dd class="mt-1 text-base font-normal text-zinc-900">{{ $currentHomeroom->academicYear->name }} / {{ $currentHomeroom->semester->name }}</dd>
                    </div>
                </dl>
            @else
                <p class="text-base font-normal text-neutral-500">Belum menjadi wali kelas.</p>
            @endif
        </div>

        {{-- Card Guru Fan/Mapel --}}
        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <h3 class="mb-4 text-lg font-semibold text-teal-950">Guru Fan/Mapel</h3>
            @if ($currentTeaching->isNotEmpty())
                <ul class="divide-y divide-stone-200">
                    @foreach ($currentTeaching as $assignment)
                        <li class="flex items-center justify-between py-3">
                            <span class="text-base font-normal text-zinc-900">{{ $assignment->subject->name }}</span>
                            <span class="text-sm text-neutral-500">{{ $assignment->schoolClass->name }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-base font-normal text-neutral-500">Belum memiliki jadwal mengajar.</p>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.teachers.edit', $teacher) }}"
               class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                Edit Guru
            </a>
            <a href="{{ route('admin.teachers.index') }}"
               class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                Kembali
            </a>
        </div>
    </div>
</x-app-layout>
