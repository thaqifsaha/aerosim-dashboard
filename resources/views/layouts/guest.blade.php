<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body
        x-data="{ darkMode: localStorage.getItem('theme') !== 'light' }"
        x-init="document.documentElement.classList.toggle('dark', darkMode)"
        class="font-sans m-0 text-gray-900 antialiased"
    >
        <div
            class="relative min-h-screen flex items-center justify-center
            bg-cover bg-center bg-no-repeat transition-all duration-500"
            :style="darkMode
                ? `background-image: url('{{ asset('images/clouds-night.png') }}')`
                : `background-image: url('{{ asset('images/clouds-day.png') }}')`"
        >

            <div class="absolute inset-0
                bg-white/40 dark:bg-black/55
                backdrop-blur-[2px]">
            </div>

            {{-- Dark / Light Toggle --}}
            <button
                id="guest-theme-toggle"
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
                    hover:scale-105 transition cursor-pointer
                    border border-white/40 dark:border-white/10"
                aria-label="Toggle dark mode"
            >
                {{-- Moon icon (shown in light mode) --}}
                <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                </svg>
                {{-- Sun icon (shown in dark mode) --}}
                <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="5"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                </svg>
            </button>

            <img src="/images/plane-outline.png"
                class="absolute opacity-10 w-[500px] left-[-120px] top-[30%]
                pointer-events-none select-none"
                alt="">

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
