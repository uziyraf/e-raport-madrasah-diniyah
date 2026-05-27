@php
    $t = $teacher ?? null;
    $selectedRole = old('account.role', $t?->user?->getRoleNames()?->first());
@endphp
<div class="space-y-5">
    {{-- Identitas Guru --}}
    <div>
        <h3 class="mb-4 text-lg font-semibold text-teal-950">Identitas Guru</h3>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-neutral-700">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $t->name ?? '') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="teacher_code" class="mb-2 block text-sm font-medium text-neutral-700">Kode Guru</label>
                <input type="text" name="teacher_code" id="teacher_code" value="{{ old('teacher_code', $t->teacher_code ?? '') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('teacher_code') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="gender" class="mb-2 block text-sm font-medium text-neutral-700">Jenis Kelamin</label>
                <select name="gender" id="gender"
                        class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="male" {{ old('gender', $t->gender ?? '') === 'male' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="female" {{ old('gender', $t->gender ?? '') === 'female' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('gender') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-neutral-700">Status Guru</label>
                <select name="status" id="status"
                        class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                    <option value="active" {{ old('status', $t->status ?? 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $t->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="birth_place" class="mb-2 block text-sm font-medium text-neutral-700">Tempat Lahir</label>
                <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place', $t->birth_place ?? '') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('birth_place') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="birth_date" class="mb-2 block text-sm font-medium text-neutral-700">Tanggal Lahir</label>
                <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $t?->birth_date?->format('Y-m-d') ?? '') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('birth_date') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Kontak --}}
    <div>
        <h3 class="mb-4 text-lg font-semibold text-teal-950">Kontak</h3>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label for="phone" class="mb-2 block text-sm font-medium text-neutral-700">Nomor HP / WhatsApp</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $t->phone ?? '') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('phone') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-neutral-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $t->email ?? '') }}"
                       class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                @error('email') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-5">
            <label for="address" class="mb-2 block text-sm font-medium text-neutral-700">Alamat</label>
            <textarea name="address" id="address" rows="3"
                      class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">{{ old('address', $t->address ?? '') }}</textarea>
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
                       {{ old('account.create_account', $t?->user_id ? true : false) ? 'checked' : '' }}
                       class="h-5 w-5 rounded-sm border-stone-300 text-teal-950 focus:ring-teal-950/10"
                       onchange="document.getElementById('account-fields').classList.toggle('hidden', !this.checked)">
                <span class="text-sm font-medium text-neutral-700">Buat akun login?</span>
            </label>
        </div>

        <div id="account-fields" class="{{ old('account.create_account', $t?->user_id ? true : false) ? '' : 'hidden' }} space-y-5">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div>
                    <label for="account_username" class="mb-2 block text-sm font-medium text-neutral-700">Username</label>
                    <input type="text" name="account[username]" id="account_username" value="{{ old('account.username', $t?->user?->username ?? '') }}"
                           class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                    @error('account.username') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="account_password" class="mb-2 block text-sm font-medium text-neutral-700">Password{{ $t?->user_id ? ' (biarkan kosong jika tidak diubah)' : '' }}</label>
                    <input type="password" name="account[password]" id="account_password"
                           class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                    @error('account.password') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="account_role" class="mb-2 block text-sm font-medium text-neutral-700">Role Pengguna</label>
                    <select name="account[role]" id="account_role"
                            class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
                        <option value="">Pilih Role</option>
                        @foreach ($roles as $roleId => $roleName)
                            <option value="{{ $roleName }}" {{ $selectedRole === $roleName ? 'selected' : '' }}>
                                {{ str_replace('_', ' ', ucwords($roleName, '_')) }}
                            </option>
                        @endforeach
                    </select>
                    @error('account.role') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Dokumen --}}
    <div>
        <h3 class="mb-4 text-lg font-semibold text-teal-950">Dokumen</h3>
        <div>
            <label for="signature" class="mb-2 block text-sm font-medium text-neutral-700">Tanda Tangan Digital</label>
            @if ($t?->signature_path)
                <div class="mb-3">
                    <img src="{{ Storage::url($t->signature_path) }}" alt="Tanda Tangan"
                         class="max-h-20 rounded-sm border border-stone-300">
                    <p class="mt-1 text-xs text-neutral-500">Tanda tangan saat ini. Upload ulang untuk mengganti.</p>
                </div>
            @endif
            <input type="file" name="signature" id="signature" accept="image/jpg,image/jpeg,image/png,image/webp"
                   class="w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition file:mr-4 file:rounded-sm file:border-0 file:bg-teal-950 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10">
            @error('signature') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
    </div>
</div>
