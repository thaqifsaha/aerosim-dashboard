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
    <body 
        x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }"
        x-init="
            document.documentElement.classList.toggle('dark', darkMode);
        "
        class="font-sans m-0 text-gray-900 antialiased"
    >
        <div
            class="relative min-h-screen flex items-center justify-center
            bg-cover bg-center bg-no-repeat transition-all duration-500"
            :style="darkMode 
                ? `background-image: url('{{ asset('images/clouds-night.png') }}')`
                : `background-image: url('{{ asset('images/clouds-day.png') }}')`
            "
        >

            <div class="absolute inset-0 
                bg-white/40 dark:bg-black/50 
                backdrop-blur-[2px]">
            </div>

            {{-- Dark / Light Toggle --}}
            <button 
                type="button"
                @click="
                    darkMode = !darkMode;
                    localStorage.setItem('theme', darkMode ? 'dark' : 'light');
                    document.documentElement.classList.toggle('dark', darkMode);
                "
                class="absolute top-6 right-6 z-20
                    w-11 h-11 rounded-xl
                    bg-white/80 dark:bg-gray-800/80
                    backdrop-blur-md shadow-md
                    flex items-center justify-center
                    hover:scale-105 transition"
            >
                <span x-show="!darkMode">🌙</span>
                <span x-show="darkMode">☀️</span>
            </button>

            <img src="/images/plane-outline.png"
                class="absolute opacity-10 w-[500px] left-[-120px] top-[30%]
                pointer-events-none select-none">

            <div class="relative z-10 w-full sm:max-w-md px-6 py-5
                        rounded-xl
                        bg-white/70 dark:bg-white/5
                        backdrop-blur-md
                        border border-white/40 dark:border-white/10
                        shadow-lg
                        transition">

                <!-- LOGO INSIDE FORM -->
                <div class="flex justify-center mb-1">
                    <x-application-logo class="w-10 h-10 fill-current text-gray-500 dark:text-gray-300" />
                </div>

                {{ $slot }}
            </div>
        </div>
    </body>
</html>
