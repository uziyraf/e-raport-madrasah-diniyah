<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIMADU') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 font-sans antialiased">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-slate-50">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Topbar -->
        @include('partials.topbar')

        <!-- Main Content -->
        <main class="min-h-screen bg-slate-50 pt-16 xl:ml-72">
            <div class="px-4 py-6 sm:px-6 lg:px-8">
                @isset($header)
                    <header class="mb-6">
                        {{ $header }}
                    </header>
                @endisset

                {{ $slot }}
            </div>
        </main>
    </div>
</body>

</html>