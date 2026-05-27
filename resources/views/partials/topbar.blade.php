<!-- resources/views/partials/topbar.blade.php -->
<nav
    class="fixed left-0 right-0 top-0 z-30 flex h-16 items-center border-b border-stone-300 bg-white px-4 py-3 shadow-sm xl:left-72 xl:px-6">
    <!-- Left side -->
    <div class="flex items-center gap-3">
        <!-- Mobile hamburger -->
        <button type="button"
            class="inline-flex items-center justify-center rounded-lg p-2 text-teal-950 hover:bg-slate-100 xl:hidden"
            @click="sidebarOpen = true">
            <span class="sr-only">✨</span>
            <i class="bx bx-menu text-2xl"></i>
        </button>

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-orange-700">
                SIRAFAH
            </p>

            <span class="block text-sm font-semibold text-teal-950 md:text-lg">
                E-Raport Madrasah Diniyah Tsuroyya Al-Falah
            </span>
        </div>
    </div>

    <!-- Right side -->
    <div class="ml-auto flex items-center">
        <div class="relative ml-3">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button type="button"
                        class="flex items-center gap-3 rounded-full focus:outline-none focus:ring-2 focus:ring-teal-950 focus:ring-offset-2 transition duration-150 ease-in-out"
                        id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                        <div class="hidden text-right sm:block">
                            <p class="text-sm font-semibold text-zinc-900">
                                Hi, {{ auth()->user()->name }}
                            </p>

                            <p class="text-xs text-neutral-600">
                                {{ str_replace('_', ' ', auth()->user()->getRoleNames()->first() ?? 'user') }}
                            </p>
                        </div>

                        <img class="h-9 w-9 rounded-full object-cover ring-2 ring-stone-300"
                            src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=0f766e&color=fff' }}"
                            alt="User Avatar">
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="block border-b border-gray-100 px-4 py-2">
                        <p class="text-sm font-semibold text-zinc-900">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-xs text-gray-500">
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    <x-dropdown-link :href="route('profile.edit')" class="font-serif text-neutral-700">
                        {{ __('Settings & Profile') }}
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="font-serif text-red-600 hover:bg-red-50 hover:text-red-700">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</nav>