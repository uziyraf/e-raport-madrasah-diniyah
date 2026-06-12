<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Export Rekap</h2>
    </x-slot>

    <div class="space-y-6">
        <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
            <p class="text-sm text-neutral-600">
                Kelas: <span class="font-semibold text-teal-950">{{ $homeroom->schoolClass->level->name ?? '' }} {{ $homeroom->schoolClass->name }}</span>
                &mdash; {{ $homeroom->academicYear->name ?? '-' }} / {{ $homeroom->semester->name ?? '-' }}
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <h3 class="text-base font-semibold text-zinc-900">Data Santri Kelas</h3>
                <p class="mt-1 text-sm text-neutral-500">Ekspor data santri di kelas wali.</p>
                <a href="{{ route('homeroom.exports.students') }}"
                   class="mt-4 inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                    <i class="bx bx-download mr-1"></i> Download CSV
                </a>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <h3 class="text-base font-semibold text-zinc-900">Rekap Absensi Kelas</h3>
                <p class="mt-1 text-sm text-neutral-500">Ekspor rekap absensi kelas wali.</p>
                <form action="{{ route('homeroom.exports.attendances') }}" method="GET" class="mt-4 space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Tanggal Dari</label>
                            <input type="date" name="date_from" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Tanggal Sampai</label>
                            <input type="date" name="date_to" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                        </div>
                    </div>
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                        <i class="bx bx-download mr-1"></i> Download CSV
                    </button>
                </form>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <h3 class="text-base font-semibold text-zinc-900">Rekap Nilai Kelas</h3>
                <p class="mt-1 text-sm text-neutral-500">Ekspor rekap nilai kelas wali.</p>
                <a href="{{ route('homeroom.exports.grades') }}"
                   class="mt-4 inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                    <i class="bx bx-download mr-1"></i> Download CSV
                </a>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <h3 class="text-base font-semibold text-zinc-900">Rekap Sikap</h3>
                <p class="mt-1 text-sm text-neutral-500">Ekspor rekap sikap kelas wali.</p>
                <a href="{{ route('homeroom.exports.attitudes') }}"
                   class="mt-4 inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                    <i class="bx bx-download mr-1"></i> Download CSV
                </a>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <h3 class="text-base font-semibold text-zinc-900">Rekap Jurnal Kelas</h3>
                <p class="mt-1 text-sm text-neutral-500">Ekspor rekap jurnal kelas wali.</p>
                <form action="{{ route('homeroom.exports.journals') }}" method="GET" class="mt-4 space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Tanggal Dari</label>
                            <input type="date" name="date_from" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase text-neutral-500">Tanggal Sampai</label>
                            <input type="date" name="date_to" class="w-full rounded-sm border border-stone-300 bg-white px-3 py-2 text-sm text-zinc-900">
                        </div>
                    </div>
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-900">
                        <i class="bx bx-download mr-1"></i> Download CSV
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
