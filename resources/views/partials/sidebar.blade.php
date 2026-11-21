@php
    $userRole = auth()->user()->getRoleNames()->first();
    $navigation = config('navigation.' . $userRole, []);
    $currentRoute = Route::currentRouteName();

    function menuRouteExists($route)
    {
        return $route && Route::has($route);
    }

    function isMenuActive($item, $currentRoute)
    {
        if (isset($item['route']) && $item['route'] === $currentRoute) {
            return true;
        }
        if (isset($item['active_patterns'])) {
            foreach ($item['active_patterns'] as $pattern) {
                if (Str::is($pattern, $currentRoute)) {
                    return true;
                }
            }
        }
        if (isset($item['children'])) {
            foreach ($item['children'] as $child) {
                if (isMenuActive($child, $currentRoute)) {
                    return true;
                }
            }
        }
        return false;
    }

    function collectMenuRoutes($item)
    {
        $routes = [];
        if (isset($item['route']) && $item['route']) {
            $routes[] = $item['route'];
        }
        if (isset($item['children'])) {
            foreach ($item['children'] as $child) {
                $routes = array_merge($routes, collectMenuRoutes($child));
            }
        }
        return $routes;
    }

    function countVisibleChildren($item)
    {
        if (!isset($item['children'])) {
            return 0;
        }
        $count = 0;
        foreach ($item['children'] as $child) {
            $hasRoute = menuRouteExists($child['route'] ?? null);
            $hasChildren = isset($child['children']);
            if ($hasRoute || $hasChildren) {
                $count++;
            }
        }
        return $count;
    }
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
                    MISRIU
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
            <ul class="space-y-1">
                @forelse ($navigation as $key => $item)
                    @php
                        $isParent = isset($item['children']);
                        $isActive = isMenuActive($item, $currentRoute);
                        $visibleChildren = $isParent ? countVisibleChildren($item) : 0;
                        $hasVisibleContent = $visibleChildren > 0 || !$isParent;
                    @endphp

                    @if (!$hasVisibleContent)
                        @continue
                    @endif

                    @if ($isParent)
                        <li x-data="{ open: {{ $isActive ? 'true' : 'false' }} }">
                            <button type="button" @click="open = !open"
                                class="group flex w-full items-center rounded-lg border-l-4 px-3 py-3 text-sm font-medium transition
                                                            {{ $isActive ? 'border-orange-300 bg-emerald-900 text-white' : 'border-transparent text-slate-50 hover:border-orange-300 hover:bg-emerald-900 hover:text-white' }}">
                                <i class="{{ $item['icon'] ?? 'bx bx-circle' }} mr-3 text-lg"></i>
                                <span class="flex-1 whitespace-normal text-left leading-snug">
                                    {{ $item['label'] }}
                                </span>
                                <i class="bx bx-chevron-down text-lg transition-transform duration-200"
                                    :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <ul x-show="open" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="ml-4 mt-1 space-y-1 border-l border-teal-700 pl-3">
                                @foreach ($item['children'] as $childKey => $child)
                                    @php
                                        $childIsParent = isset($child['children']);
                                        $childIsActive = isMenuActive($child, $currentRoute);
                                        $childHasRoute = menuRouteExists($child['route'] ?? null);
                                        $childVisibleChildren = $childIsParent ? countVisibleChildren($child) : 0;
                                        $childHasVisibleContent = $childVisibleChildren > 0 || $childHasRoute;
                                    @endphp

                                    @if (!$childHasVisibleContent)
                                        @continue
                                    @endif

                                    @if ($childIsParent)
                                        <li x-data="{ childOpen: {{ $childIsActive ? 'true' : 'false' }} }">
                                            <button type="button" @click="childOpen = !childOpen"
                                                class="group flex w-full items-center rounded-lg px-3 py-2 text-sm font-medium transition
                                                                                                    {{ $childIsActive ? 'bg-emerald-900 text-white' : 'text-slate-300 hover:bg-emerald-900 hover:text-white' }}">
                                                <i class="{{ $child['icon'] ?? 'bx bx-circle' }} mr-2 text-base"></i>
                                                <span
                                                    class="flex-1 whitespace-normal text-left leading-snug">{{ $child['label'] }}</span>
                                                <i class="bx bx-chevron-down text-base transition-transform duration-200"
                                                    :class="childOpen ? 'rotate-180' : ''"></i>
                                            </button>

                                            <ul x-show="childOpen" x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 -translate-y-2"
                                                x-transition:enter-end="opacity-100 translate-y-0"
                                                class="ml-4 mt-1 space-y-1 border-l border-teal-700 pl-3">
                                                @foreach ($child['children'] as $grandchildKey => $grandchild)
                                                    @php
                                                        $gcRoute = $grandchild['route'] ?? null;
                                                        $gcRouteExists = menuRouteExists($gcRoute);
                                                        $gcIsActive = isMenuActive($grandchild, $currentRoute);
                                                    @endphp

                                                    @if (!$gcRouteExists)
                                                        @continue
                                                    @endif

                                                    <li>
                                                        <a href="{{ route($gcRoute) }}" @click="sidebarOpen = false"
                                                            class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium transition
                                                                                                                            {{ $gcIsActive ? 'bg-teal-800/60 text-orange-200' : 'text-slate-400 hover:bg-teal-800/60 hover:text-white' }}">
                                                            <i class="{{ $grandchild['icon'] ?? 'bx bx-circle' }} mr-2 text-base"></i>
                                                            <span class="whitespace-normal leading-snug">{{ $grandchild['label'] }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @else
                                        @php
                                            $childRoute = $child['route'] ?? null;
                                            $childRouteExists = menuRouteExists($childRoute);
                                            $childHref = $childRouteExists ? route($childRoute) : '#';
                                        @endphp

                                        <li>
                                            <a href="{{ $childHref }}" @if ($childRouteExists) @click="sidebarOpen = false" @endif
                                                class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium transition
                                                                                                    {{ $childIsActive ? 'bg-emerald-900 text-white' : 'text-slate-300 hover:bg-emerald-900 hover:text-white' }}
                                                                                                    {{ !$childRouteExists ? 'cursor-not-allowed opacity-50' : '' }}">
                                                <i class="{{ $child['icon'] ?? 'bx bx-circle' }} mr-2 text-base"></i>
                                                <span class="whitespace-normal leading-snug">{{ $child['label'] }}</span>
                                                @if (!$childRouteExists)
                                                    <span class="ml-auto text-xs text-slate-500">(segera)</span>
                                                @endif
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @else
                        @php
                            $routeName = $item['route'] ?? null;
                            $routeExists = menuRouteExists($routeName);
                            $href = $routeExists ? route($routeName) : '#';
                            $isActive = isMenuActive($item, $currentRoute);

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
                    @endif
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