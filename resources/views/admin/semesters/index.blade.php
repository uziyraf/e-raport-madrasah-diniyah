<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Semester</h2>
    </x-slot>

    <div class="space-y-5">
        @if (session('success'))
            <div class="rounded-sm bg-emerald-200 px-4 py-3 text-sm font-medium text-green-950">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center justify-between">
            <div></div>
            <a href="{{ route('admin.semesters.create') }}"
               class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                + Tambah Semester
            </a>
        </div>

        <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
            <table class="min-w-full divide-y divide-stone-300">
                <thead>
                    <tr>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Nama</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Tahun Ajaran</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Tanggal Mulai</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Tanggal Selesai</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Status</th>
                        <th class="border-b border-stone-300 bg-white px-6 py-4 text-left text-sm font-medium text-neutral-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-300">
                    @forelse ($semesters as $semester)
                        <tr>
                            <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $semester->name }}</td>
                            <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $semester->academicYear->name }}</td>
                            <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $semester->start_date->format('d/m/Y') }}</td>
                            <td class="border-t border-stone-300 px-6 py-4 text-base font-normal text-zinc-900">{{ $semester->end_date->format('d/m/Y') }}</td>
                            <td class="border-t border-stone-300 px-6 py-4">
                                @if ($semester->is_active)
                                    <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Aktif</span>
                                @else
                                    <span class="inline-flex rounded-full bg-zinc-200 px-3 py-1 text-xs font-semibold text-neutral-700">Nonaktif</span>
                                @endif
                            </td>
                            <td class="border-t border-stone-300 px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.semesters.edit', $semester) }}"
                                       class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-2 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.semesters.destroy', $semester) }}" method="POST" onsubmit="return confirm('Nonaktifkan semester ini?')">
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
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-neutral-500">Belum ada data semester.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $semesters->links() }}
        </div>
    </div>
</x-app-layout>
