<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Absensi Mengajar</h2>
    </x-slot>

    <div class="space-y-5">
        @if (session('success'))
            <div class="rounded-sm bg-emerald-200 px-4 py-3 text-sm font-medium text-green-950">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-sm bg-red-200 px-4 py-3 text-sm font-medium text-red-950">
                {{ session('error') }}
            </div>
        @endif

        @if ($assignments->isEmpty())
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <p class="text-center text-sm text-neutral-500">
                    Anda belum memiliki penugasan mengajar untuk tahun ajaran dan semester aktif.
                </p>
            </div>
        @elseif (!$selectedAssignment)
            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <form method="GET" action="{{ route('teacher.attendances.index') }}" class="space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-700">Pilih Kelas / Fan yang Diajar</label>
                        <select name="teaching_assignment_id" onchange="this.form.submit()"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">-- Pilih Kelas / Fan --</option>
                            @foreach ($assignments as $ta)
                                <option value="{{ $ta->id }}" {{ request('teaching_assignment_id') == $ta->id ? 'selected' : '' }}>
                                    {{ $ta->subject->name }} - {{ $ta->schoolClass->level->name }} {{ $ta->schoolClass->name }}
                                    ({{ $ta->academicYear->name }} / {{ $ta->semester->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        @else
            <div class="rounded-lg bg-slate-50 p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                    <div>
                        <p class="text-xs font-semibold uppercase text-neutral-500">Fan/Mapel</p>
                        <p class="mt-1 text-base font-medium text-zinc-900">{{ $selectedAssignment->subject->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-neutral-500">Kelas</p>
                        <p class="mt-1 text-base font-medium text-zinc-900">{{ $selectedAssignment->schoolClass->level->name }} {{ $selectedAssignment->schoolClass->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-neutral-500">Tahun Ajaran</p>
                        <p class="mt-1 text-base font-medium text-zinc-900">{{ $selectedAssignment->academicYear->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase text-neutral-500">Semester</p>
                        <p class="mt-1 text-base font-medium text-zinc-900">{{ $selectedAssignment->semester->name }}</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div></div>
                <a href="{{ route('teacher.attendances.create', ['teaching_assignment_id' => $selectedAssignment->id]) }}"
                   class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                    + Input Absensi Baru
                </a>
            </div>

            <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                <form method="GET" action="{{ route('teacher.attendances.index') }}" class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <input type="hidden" name="teaching_assignment_id" value="{{ $selectedAssignment->id }}">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-700">Status</label>
                        <select name="status"
                                class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-700">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                               class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-neutral-700">Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                               class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                    </div>
                    <div class="flex items-end gap-2 md:col-span-3">
                        <button type="submit"
                                class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                            Filter
                        </button>
                        <a href="{{ route('teacher.attendances.index', ['teaching_assignment_id' => $selectedAssignment->id]) }}"
                           class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            @if ($sessions->isEmpty())
                <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <p class="text-center text-sm text-neutral-500">Belum ada sesi absensi.</p>
                </div>
            @else
                <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
                    <table class="min-w-full divide-y divide-stone-300">
                        <thead>
                            <tr>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Tanggal</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Hadir</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Izin</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Sakit</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Alfa</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Status</th>
                                <th class="border-b border-stone-300 bg-white px-4 py-4 text-left text-sm font-medium text-neutral-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-300">
                            @foreach ($sessions as $session)
                                @php
                                    $presentCount = $session->details->where('status', 'present')->count();
                                    $permissionCount = $session->details->where('status', 'permission')->count();
                                    $sickCount = $session->details->where('status', 'sick')->count();
                                    $absentCount = $session->details->where('status', 'absent')->count();
                                @endphp
                                <tr>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $session->attendance_date->format('d/m/Y') }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $presentCount }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $permissionCount }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $sickCount }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3 text-base text-zinc-900">{{ $absentCount }}</td>
                                    <td class="border-t border-stone-300 px-4 py-3">
                                        @if ($session->status === 'draft')
                                            <span class="inline-flex rounded-full bg-orange-300 px-3 py-1 text-xs font-semibold text-orange-950">Draft</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-green-950">Submitted</span>
                                        @endif
                                    </td>
                                    <td class="border-t border-stone-300 px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('teacher.attendances.show', $session) }}"
                                               class="text-sm font-medium text-teal-950 underline transition hover:text-emerald-900">Detail</a>
                                            <a href="{{ route('teacher.attendances.edit', $session) }}"
                                               class="text-sm font-medium text-teal-950 underline transition hover:text-emerald-900">Edit</a>
                                            <form action="{{ route('teacher.attendances.destroy', $session) }}" method="POST"
                                                  onsubmit="return confirm('Hapus sesi absensi ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        class="text-sm font-medium text-red-700 underline transition hover:text-red-900">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $sessions->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
