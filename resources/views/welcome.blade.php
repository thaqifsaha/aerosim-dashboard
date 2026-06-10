<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('theme') !== 'light' }"
      x-init="document.documentElement.classList.toggle('dark', darkMode)"
      :class="darkMode ? 'dark' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AeroSim — Flight Simulator Training</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Montserrat:wght@600;700;800&family=Orbitron:wght@500;600;700&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="m-0 font-sans antialiased">

    <div
        class="relative min-h-screen flex flex-col items-center justify-center bg-cover bg-center bg-no-repeat overflow-hidden transition-all duration-500"
        :style="darkMode
            ? `background-image: url('{{ asset('images/clouds-night.png') }}')`
            : `background-image: url('{{ asset('images/clouds-day.png') }}')`"
    >
        {{-- Overlay --}}
        <div class="absolute inset-0 bg-white/30 dark:bg-black/60 backdrop-blur-sm transition-all duration-500"></div>

        {{-- Decorative plane outline --}}
        <img src="/images/plane-outline.png"
            class="absolute opacity-[0.06] w-[600px] -left-32 top-1/4 pointer-events-none select-none"
            alt="">

        {{-- Theme toggle --}}
        <button
            type="button"
            @click="
                darkMode = !darkMode;
                localStorage.setItem('theme', darkMode ? 'dark' : 'light');
                document.documentElement.classList.toggle('dark', darkMode);
            "
            class="absolute top-5 right-5 z-20 w-11 h-11 rounded-xl
                bg-white/80 dark:bg-slate-800/80 backdrop-blur-md shadow-md
                flex items-center justify-center border border-white/40 dark:border-white/10
                hover:scale-105 transition cursor-pointer"
            aria-label="Toggle dark mode"
        >
            <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
            </svg>
            <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="5"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
            </svg>
        </button>

        {{-- Main content --}}
        <div class="relative z-10 flex flex-col items-center text-center px-6 py-12 max-w-2xl w-full">

            {{-- Logo --}}
            <div class="mb-6 flex items-center justify-center w-20 h-20 rounded-2xl
                bg-white/20 dark:bg-white/5 backdrop-blur-md
                border border-white/30 dark:border-white/10 shadow-lg">
                <img src="{{ asset('images/company_logo.png') }}" alt="AeroSim Logo" class="w-14 h-14 object-contain">
            </div>

            {{-- Brand name --}}
            <h1 style="font-family: 'Montserrat', sans-serif;"
                class="text-4xl sm:text-5xl font-extrabold tracking-widest uppercase
                text-slate-900 dark:text-white mb-2 drop-shadow-md">
                AeroSim
            </h1>
            <p style="font-family: 'Inter', sans-serif;"
                class="text-sm sm:text-base font-medium tracking-[0.2em] uppercase
                text-slate-600 dark:text-cyan-300/80 mb-10">
                Flight Simulator Training Management
            </p>

            {{-- Feature tags --}}
            <div class="flex flex-wrap justify-center gap-2 mb-10">
                @foreach(['DSS Evaluation', 'Performance Analytics', 'Flight Scheduling', 'PDF Reports'] as $tag)
                    <span class="px-3 py-1 rounded-full text-xs font-semibold tracking-wider uppercase
                        bg-white/40 dark:bg-white/5 border border-white/50 dark:border-cyan-500/30
                        text-slate-700 dark:text-cyan-300 backdrop-blur-sm shadow-sm">
                        {{ $tag }}
                    </span>
                @endforeach
            </div>

            {{-- CTA Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        style="font-family: 'Inter', sans-serif;"
                        class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl
                            font-semibold text-sm tracking-wide
                            bg-cyan-500 hover:bg-cyan-400 text-white
                            shadow-lg hover:shadow-cyan-400/40
                            transition-all duration-200 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        style="font-family: 'Inter', sans-serif;"
                        class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl
                            font-semibold text-sm tracking-wide
                            bg-cyan-500 hover:bg-cyan-400 text-white
                            shadow-lg hover:shadow-cyan-400/40
                            transition-all duration-200 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Sign In
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            style="font-family: 'Inter', sans-serif;"
                            class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl
                                font-semibold text-sm tracking-wide
                                bg-white/20 dark:bg-white/5 hover:bg-white/35 dark:hover:bg-white/10
                                border border-white/50 dark:border-white/20
                                text-slate-800 dark:text-white
                                backdrop-blur-sm shadow
                                transition-all duration-200 cursor-pointer">
                            Create Account
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Footer --}}
        <div class="absolute bottom-5 z-10 text-center w-full">
            <p style="font-family: 'Inter', sans-serif;"
                class="text-xs text-slate-500 dark:text-slate-400/60">
                &copy; {{ date('Y') }} AeroSim &middot; Flight Simulator Training System
            </p>
        </div>
    </div>

</body>
</html>
