<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Data Santri</h2>
    </x-slot>

    <div class="space-y-5">
        @if (session('success'))
            <div class="rounded-sm bg-emerald-200 px-4 py-3 text-sm font-medium text-green-950">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center justify-between">
            <form method="GET" action="{{ route('admin.students.index') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIS..."
                       class="w-64 rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                    Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('admin.students.index') }}"
                       class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                        Reset
                    </a>
                @endif
            </form>
            <a href="{{ route('admin.students.create') }}"
               class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                + Tambah Santri
            </a>
        </div>

        <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
            <table class="min-w-full divide-y divide-stone-300">
                <thead>
                    <tr>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">NIS</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Nama</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Jenis Kelamin</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Kelas Aktif</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Kontak Wali</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Status</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-300">
                    @forelse ($students as $student)
                        <tr>
                            <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $student->nis }}</td>
                            <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $student->name }}</td>
                            <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $student->gender === 'male' ? 'Laki-laki' : ($student->gender === 'female' ? 'Perempuan' : '-') }}</td>
                            <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $student->activeEnrollment?->schoolClass?->name ?? '-' }}</td>
                            <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $student->guardian_phone ?? '-' }}</td>
                            <td class="border-t border-stone-300 px-6 py-4">
                                @if ($student->status === 'active')
                                    <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Aktif</span>
                                @else
                                    <span class="inline-flex rounded-full bg-zinc-200 px-3 py-1 text-xs font-semibold text-neutral-700">Nonaktif</span>
                                @endif
                            </td>
                            <td class="border-t border-stone-300 px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.students.show', $student) }}"
                                       class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-2 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                                        Detail
                                    </a>
                                    <a href="{{ route('admin.students.edit', $student) }}"
                                       class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-2 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST" onsubmit="return confirm('Nonaktifkan santri ini?')">
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
                            <td colspan="7" class="px-6 py-10 text-center text-sm text-neutral-500">Belum ada data santri.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $students->links() }}
        </div>
    </div>
</x-app-layout>
