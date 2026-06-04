<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Data Santri</h2>
    </x-slot>

    <div class="space-y-5">
        @if (!$guardian || $students->isEmpty())
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <div class="py-10 text-center">
                    <p class="text-base font-normal text-neutral-500">
                        @if (!$guardian)
                            Profil wali santri belum terhubung dengan akun Anda. Silakan hubungi admin.
                        @else
                            Belum ada santri yang terhubung dengan akun Anda.
                        @endif
                    </p>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($students as $student)
                    <a href="{{ route('guardian.students.show', $student) }}"
                       class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300 transition hover:bg-slate-50">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-base font-semibold text-zinc-900">{{ $student->name }}</h3>
                                <p class="mt-1 text-sm text-neutral-500">NIS: {{ $student->nis }}</p>
                            </div>
                            @if ($student->status === 'active')
                                <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Aktif</span>
                            @else
                                <span class="inline-flex rounded-full bg-zinc-200 px-3 py-1 text-xs font-semibold text-neutral-700">Nonaktif</span>
                            @endif
                        </div>
                        @if ($student->activeEnrollment?->schoolClass)
                            <div class="mt-3 border-t border-stone-200 pt-3">
                                <span class="text-xs font-semibold uppercase text-neutral-500">Kelas Aktif</span>
                                <p class="mt-1 text-sm text-zinc-900">
                                    {{ $student->activeEnrollment->schoolClass->level->name ?? '' }} - {{ $student->activeEnrollment->schoolClass->name }}
                                </p>
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
