<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Detail Wali Santri</h2>
    </x-slot>

    <div class="space-y-6">
        {{-- Card Data Wali --}}
        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <h3 class="mb-4 text-lg font-semibold text-teal-950">Data Wali</h3>
            <dl class="grid grid-cols-1 gap-x-6 gap-y-4 md:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Nama Wali</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $guardian->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Status</dt>
                    <dd class="mt-1">
                        @if ($guardian->status === 'active')
                            <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Aktif</span>
                        @else
                            <span class="inline-flex rounded-full bg-zinc-200 px-3 py-1 text-xs font-semibold text-neutral-700">Nonaktif</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Hubungan</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $guardian->relationship ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Kontak</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">
                        @if ($guardian->phone)
                            <span class="block">{{ $guardian->phone }}</span>
                        @endif
                        @if ($guardian->email)
                            <span class="block text-neutral-500">{{ $guardian->email }}</span>
                        @endif
                        @if (!$guardian->phone && !$guardian->email)
                            -
                        @endif
                    </dd>
                </div>
                @if ($guardian->address)
                <div class="md:col-span-2">
                    <dt class="text-xs font-semibold uppercase text-neutral-500">Alamat</dt>
                    <dd class="mt-1 text-base font-normal text-zinc-900">{{ $guardian->address }}</dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Card Akun Login --}}
        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <h3 class="mb-4 text-lg font-semibold text-teal-950">Akun Login</h3>
            @if ($guardian->user)
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 md:grid-cols-3">
                    <div>
                        <dt class="text-xs font-semibold uppercase text-neutral-500">Username</dt>
                        <dd class="mt-1 text-base font-normal text-zinc-900">{{ $guardian->user->username }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-neutral-500">Role</dt>
                        <dd class="mt-1">
                            <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Wali Santri</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase text-neutral-500">Status Akun</dt>
                        <dd class="mt-1">
                            @if ($guardian->user->status === 'active')
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

        {{-- Card Santri Terhubung --}}
        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <h3 class="mb-4 text-lg font-semibold text-teal-950">Santri Terhubung</h3>
            @if ($guardian->students->isNotEmpty())
                <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <table class="min-w-full divide-y divide-stone-300">
                        <thead>
                            <tr>
                                <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">NIS</th>
                                <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Nama Santri</th>
                                <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Kelas Aktif</th>
                                <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Hubungan</th>
                                <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-300">
                            @foreach ($guardian->students as $student)
                                <tr>
                                    <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $student->nis }}</td>
                                    <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $student->name }}</td>
                                    <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $student->activeEnrollment?->schoolClass?->name ?? '-' }}</td>
                                    <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $student->pivot?->relationship ?? '-' }}</td>
                                    <td class="border-t border-stone-300 px-6 py-4">
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
            @else
                <p class="text-base font-normal text-neutral-500">Belum ada santri terhubung.</p>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.guardians.edit', $guardian) }}"
               class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                Edit Wali Santri
            </a>
            <a href="{{ route('admin.guardians.index') }}"
               class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                Kembali
            </a>
        </div>
    </div>
</x-app-layout>
