<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-serif text-zinc-900 antialiased bg-stone-100">
    <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md">
            <div class="text-center mb-8 pt-6">
                <a href="/" class="inline-flex flex-col items-center gap-2">
                    <x-application-logo class="w-16 h-16 text-teal-950" />
                    <h1 class="text-2xl font-bold text-teal-950">E-Raport Tsuroyya Al-Falah</h1>
                    <p class="text-sm text-neutral-600">Sistem Informasi Madrasah Diniyah</p>
                </a>
            </div>

            <div
                class="rounded-lg bg-white p-8 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>