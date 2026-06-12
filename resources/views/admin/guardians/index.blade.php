<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Data Wali Santri</h2>
    </x-slot>

    <div class="space-y-5">
        @if (session('success'))
            <div class="rounded-sm bg-emerald-200 px-4 py-3 text-sm font-medium text-green-950">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center justify-between">
            <form method="GET" action="{{ route('admin.guardians.index') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, HP, email, santri..."
                       class="w-80 rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                    Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('admin.guardians.index') }}"
                       class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                        Reset
                    </a>
                @endif
            </form>
            <a href="{{ route('admin.guardians.create') }}"
               class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                + Tambah Wali Santri
            </a>
        </div>

        <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
            <table class="min-w-full divide-y divide-stone-300">
                <thead>
                    <tr>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Nama Wali</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Kontak</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Santri Terhubung</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Akun</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Status</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-300">
                    @forelse ($guardians as $guardian)
                        <tr>
                            <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $guardian->name }}</td>
                            <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">
                                @if ($guardian->phone)
                                    <span class="block text-sm">{{ $guardian->phone }}</span>
                                @endif
                                @if ($guardian->email)
                                    <span class="block text-sm text-neutral-500">{{ $guardian->email }}</span>
                                @endif
                                @if (!$guardian->phone && !$guardian->email)
                                    -
                                @endif
                            </td>
                            <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">
                                {{ $guardian->students_count ?? $guardian->students->count() }} santri
                            </td>
                            <td class="border-t border-stone-300 px-6 py-4">
                                @if ($guardian->user_id)
                                    <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Ada</span>
                                @else
                                    <span class="inline-flex rounded-full bg-zinc-200 px-3 py-1 text-xs font-semibold text-neutral-700">Tidak</span>
                                @endif
                            </td>
                            <td class="border-t border-stone-300 px-6 py-4">
                                @if ($guardian->status === 'active')
                                    <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Aktif</span>
                                @else
                                    <span class="inline-flex rounded-full bg-zinc-200 px-3 py-1 text-xs font-semibold text-neutral-700">Nonaktif</span>
                                @endif
                            </td>
                            <td class="border-t border-stone-300 px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.guardians.show', $guardian) }}"
                                       class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-2 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.guardians.edit', $guardian) }}"
                                       class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-2 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.guardians.destroy', $guardian) }}" method="POST" onsubmit="return confirm('Nonaktifkan wali santri ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center rounded-sm bg-red-200 px-4 py-2 text-sm font-medium text-red-950 transition hover:bg-red-300">
                                            Nonaktifkan
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-neutral-500">Belum ada data wali santri.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $guardians->links() }}
        </div>
    </div>
</x-app-layout>
