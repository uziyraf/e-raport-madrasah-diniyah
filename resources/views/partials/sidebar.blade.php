@php
    $userRole = auth()->user()->getRoleNames()->first();
    $navigation = config('navigation.' . $userRole, []);
    $currentRoute = Route::currentRouteName();
@endphp

{{-- Mobile overlay --}}
<div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-30 bg-black/40 xl:hidden"
    @click="sidebarOpen = false"></div>

<aside id="sidebar"
    class="fixed left-0 top-0 z-40 h-screen w-72 border-r border-stone-300 bg-teal-950 transition-transform duration-300 xl:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full xl:translate-x-0'" aria-label="Sidebar">
    <div class="flex h-full flex-col bg-teal-950 px-3 py-5">
        <div class="mb-6 flex items-start justify-between px-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-orange-300">
                    SIRAFAH
                </p>

                <h1 class="mt-1 text-xl font-bold text-white">
                    E-Raport Madrasah
                </h1>

                <p class="mt-1 text-sm text-slate-300">
                    Sistem Informasi Madrasah Diniyah
                </p>
            </div>

            {{-- Close button mobile --}}
            <button type="button" class="rounded-lg p-2 text-slate-200 hover:bg-emerald-900 xl:hidden"
                @click="sidebarOpen = false">
                <span class="sr-only">Tutup sidebar</span>
                ✕
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto">
            <ul class="space-y-2">
                @forelse ($navigation as $key => $item)
                    @php
                        $routeName = $item['route'] ?? null;
                        $routeExists = $routeName && Route::has($routeName);
                        $href = $routeExists ? route($routeName) : '#';
                        $isActive = $routeExists && $currentRoute === $routeName;

                        $linkClasses = 'group flex items-center rounded-lg border-l-4 px-3 py-3 text-sm font-medium transition';

                        if ($isActive) {
                            $linkClasses .= ' border-orange-300 bg-emerald-900 text-white';
                        } else {
                            $linkClasses .= ' border-transparent text-slate-50 hover:border-orange-300 hover:bg-emerald-900 hover:text-white';
                        }

                        if (!$routeExists) {
                            $linkClasses .= ' cursor-not-allowed opacity-50';
                        }
                    @endphp

                    <li>
                        <a href="{{ $href }}" class="{{ $linkClasses }}" @if (!$routeExists) title="Menu ini belum tersedia"
                        @endif @if ($routeExists) @click="sidebarOpen = false" @endif>
                            <i class="{{ $item['icon'] ?? 'bx bx-circle' }} mr-3 text-lg"></i>

                            <span class="flex-1 whitespace-normal leading-snug">
                                {{ $item['label'] }}
                            </span>
                        </a>
                    </li>
                @empty
                    <li>
                        <div class="rounded-lg bg-red-200 px-3 py-3 text-sm font-medium text-red-950">
                            Menu role belum tersedia.
                        </div>
                    </li>
                @endforelse
            </ul>
        </nav>

        <div class="mt-6 border-t border-teal-800 px-3 pt-4">
            <p class="text-xs font-semibold uppercase text-slate-400">
                Login sebagai
            </p>

            <p class="mt-1 text-sm font-medium text-white">
                {{ auth()->user()->name }}
            </p>

            <p class="text-xs text-slate-300">
                {{ str_replace('_', ' ', $userRole ?? 'user') }}
            </p>
        </div>
    </div>
</aside>