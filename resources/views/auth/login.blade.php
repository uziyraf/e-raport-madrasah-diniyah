<x-guest-layout>
    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-2 block text-sm font-medium text-neutral-700">{{ __('Email / Username') }}</label>
            <input id="email" class="block w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10" type="text" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            @error('email')
                <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="mb-2 block text-sm font-medium text-neutral-700">{{ __('Password') }}</label>
            <input id="password" class="block w-full rounded-sm border border-stone-300 bg-white px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-teal-950 focus:ring-2 focus:ring-teal-950/10" type="password" name="password" required autocomplete="current-password" />
            @error('password')
                <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center gap-2">
                <input id="remember_me" type="checkbox" class="rounded border-stone-300 text-teal-950 shadow-sm focus:ring-teal-950" name="remember">
                <span class="text-sm text-neutral-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end gap-3">
            @if (Route::has('password.request'))
                <a class="text-sm text-neutral-600 underline hover:text-teal-950 rounded-sm focus:outline-none focus:ring-2 focus:ring-teal-950 focus:ring-offset-2 transition" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <button type="submit" class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-teal-950 focus:ring-offset-2">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</x-guest-layout>
