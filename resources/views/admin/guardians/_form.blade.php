@php
    $g = $guardian ?? null;
    $selectedStudentIds = old('students', $g?->students?->pluck('id')->toArray() ?? []);
@endphp
<div class="space-y-5">
    {{-- Data Wali --}}
    <div>
        <h3 class="mb-4 text-lg font-semibold text-teal-950">Data Wali</h3>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-neutral-700">Nama Wali</label>
                <input type="text" name="name" id="name" value="{{ old('name', $g->name ?? '') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="relationship" class="mb-2 block text-sm font-medium text-neutral-700">Hubungan dengan Santri</label>
                <input type="text" name="relationship" id="relationship" value="{{ old('relationship', $g->relationship ?? '') }}"
                       placeholder="Ayah, Ibu, Wali, dll."
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('relationship') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="phone" class="mb-2 block text-sm font-medium text-neutral-700">Nomor HP / WhatsApp</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $g->phone ?? '') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('phone') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-neutral-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $g->email ?? '') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('email') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-neutral-700">Status</label>
                <select name="status" id="status"
                        class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                    <option value="active" {{ old('status', $g->status ?? 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $g->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-5">
            <label for="address" class="mb-2 block text-sm font-medium text-neutral-700">Alamat</label>
            <textarea name="address" id="address" rows="3"
                      class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">{{ old('address', $g->address ?? '') }}</textarea>
            @error('address') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Akun Login --}}
    <div>
        <h3 class="mb-4 text-lg font-semibold text-teal-950">Akun Login</h3>
        <div class="mb-4">
            <label class="inline-flex items-center gap-3">
                <input type="hidden" name="account[create_account]" value="0">
                <input type="checkbox" name="account[create_account]" id="create_account" value="1"
                       {{ old('account.create_account', $g?->user_id ? true : false) ? 'checked' : '' }}
                       class="h-5 w-5 rounded-sm border-stone-300 text-teal-950 focus:ring-teal-950/10"
                       onchange="document.getElementById('account-fields').classList.toggle('hidden', !this.checked)">
                <span class="text-sm font-medium text-neutral-700">Buat akun login?</span>
            </label>
        </div>

        <div id="account-fields" class="{{ old('account.create_account', $g?->user_id ? true : false) ? '' : 'hidden' }} space-y-5">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div>
                    <label for="account_email" class="mb-2 block text-sm font-medium text-neutral-700">Email (opsional)</label>
                    <input type="email" name="account[email]" id="account_email" value="{{ old('account.email', $g?->user?->email ?? '') }}"
                           class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                    @error('account.email') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="account_username" class="mb-2 block text-sm font-medium text-neutral-700">Username</label>
                    <input type="text" name="account[username]" id="account_username" value="{{ old('account.username', $g?->user?->username ?? '') }}"
                           class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                    @error('account.username') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="account_password" class="mb-2 block text-sm font-medium text-neutral-700">Password{{ $g?->user_id ? ' (biarkan kosong jika tidak diubah)' : '' }}</label>
                    <input type="password" name="account[password]" id="account_password"
                           class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                    @error('account.password') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Santri Terhubung --}}
    <div>
        <h3 class="mb-4 text-lg font-semibold text-teal-950">Santri Terhubung</h3>
        <p class="mb-3 text-sm text-neutral-500">Pilih satu atau lebih santri yang terhubung dengan wali ini.</p>

        <div class="mb-4 flex flex-wrap items-end gap-3">
            <div>
                <label for="student_search" class="mb-1 block text-xs font-medium text-neutral-600">Cari Santri</label>
                <input type="text" id="student_search" value="{{ request('student_search') }}"
                       placeholder="NIS atau nama santri..."
                       class="w-56 rounded-sm border border-stone-300 bg-white px-3 py-2.5 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
            </div>
            <div>
                <label for="level_id" class="mb-1 block text-xs font-medium text-neutral-600">Jenjang</label>
                <select id="level_id"
                        class="w-44 rounded-sm border border-stone-300 bg-white px-3 py-2.5 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                    <option value="">Semua Jenjang</option>
                    @foreach ($levels ?? [] as $level)
                        <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                            {{ $level->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="school_class_id" class="mb-1 block text-xs font-medium text-neutral-600">Kelas</label>
                <select id="school_class_id"
                        class="w-44 rounded-sm border border-stone-300 bg-white px-3 py-2.5 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                    <option value="">Semua Kelas</option>
                    @foreach ($classes ?? [] as $class)
                        <option value="{{ $class->id }}" {{ request('school_class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->level->name ?? '' }} - {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="button" onclick="applyStudentFilter()"
                        class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-2.5 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                    Cari
                </button>
                @if (request('student_search') || request('level_id') || request('school_class_id'))
                    <a href="{{ url()->current() }}"
                       class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-2.5 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                        Reset
                    </a>
                @endif
            </div>
        </div>

        <div class="overflow-hidden rounded-lg bg-white outline outline-1 outline-offset-[-1px] outline-stone-300">
            <table class="min-w-full divide-y divide-stone-300">
                <thead>
                    <tr>
                        <th class="border-b border-stone-300 bg-white px-4 py-3 text-left text-sm font-medium text-neutral-700 w-12">Pilih</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-3 text-left text-sm font-medium text-neutral-700">NIS</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-3 text-left text-sm font-medium text-neutral-700">Nama Santri</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-3 text-left text-sm font-medium text-neutral-700">Kelas Aktif</th>
                        <th class="border-b border-stone-300 bg-white px-4 py-3 text-left text-sm font-medium text-neutral-700">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-300">
                    @forelse ($students ?? [] as $student)
                        <tr class="transition hover:bg-slate-50">
                            <td class="border-t border-stone-300 px-4 py-3">
                                <input type="checkbox" name="students[]" value="{{ $student->id }}"
                                       {{ in_array($student->id, $selectedStudentIds) ? 'checked' : '' }}
                                       class="h-4 w-4 rounded-sm border-stone-300 text-teal-950 focus:ring-teal-950/10"
                                       onchange="toggleSelectedCount()">
                            </td>
                            <td class="border-t border-stone-300 px-4 py-3 text-sm font-normal text-zinc-900">{{ $student->nis }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-sm font-normal text-zinc-900">{{ $student->name }}</td>
                            <td class="border-t border-stone-300 px-4 py-3 text-sm text-neutral-600">
                                {{ $student->activeEnrollment?->schoolClass?->name ?? '-' }}
                            </td>
                            <td class="border-t border-stone-300 px-4 py-3">
                                @if ($student->status === 'active')
                                    <span class="inline-flex rounded-full bg-emerald-200 px-2.5 py-0.5 text-xs font-semibold text-green-950">Aktif</span>
                                @else
                                    <span class="inline-flex rounded-full bg-zinc-200 px-2.5 py-0.5 text-xs font-semibold text-neutral-700">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-neutral-500">Tidak ada santri ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 flex items-center justify-between">
            <span id="selected-count" class="text-sm text-neutral-600">
                {{ count($selectedStudentIds) }} santri dipilih
            </span>
            <div class="text-sm">
                {{ ($students ?? collect())->appends(request()->query())->links() }}
            </div>
        </div>

        @error('students') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        @error('students.*') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
    </div>
</div>

<script>
    function applyStudentFilter() {
        const search = document.getElementById('student_search').value;
        const levelId = document.getElementById('level_id').value;
        const classId = document.getElementById('school_class_id').value;
        const url = new URL(window.location.href);
        url.searchParams.set('student_search', search);
        url.searchParams.set('level_id', levelId);
        url.searchParams.set('school_class_id', classId);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

    function toggleSelectedCount() {
        const checked = document.querySelectorAll('input[name="students[]"]:checked').length;
        document.getElementById('selected-count').textContent = checked + ' santri dipilih';
    }
</script>
