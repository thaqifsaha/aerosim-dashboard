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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&family=Montserrat:wght@600;700;800&family=Orbitron:wght@500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            html,
            body,
            nav,
            header,
            main,
            div,
            section,
            table,
            input,
            select,
            button {
                transition:
                    background-color 0.35s ease,
                    color 0.35s ease,
                    border-color 0.35s ease,
                    box-shadow 0.35s ease;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-50 dark:bg-[#060e1a]">
        <div class="min-h-screen relative overflow-hidden bg-gradient-to-b from-slate-100 via-white to-white dark:from-[#0A1628] dark:via-[#0a1628] dark:to-[#060e1a]">

            <div class="pointer-events-none fixed inset-0 z-0 opacity-40 dark:opacity-20">
                <div class="absolute top-20 left-10 w-72 h-72 bg-white/50 dark:bg-blue-500/10 rounded-full blur-3xl"></div>
                <div class="absolute top-80 right-20 w-96 h-96 bg-blue-200/40 dark:bg-cyan-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 left-1/3 w-80 h-80 bg-sky-100/60 dark:bg-indigo-500/10 rounded-full blur-3xl"></div>
            </div>

            <div
                class="relative z-10"
                x-data="{
                    sidebarOpen: false,
                    sidebarStorageKey: 'sidebarOpen',
                    isDesktop() {
                        return window.innerWidth >= 640;
                    },
                    savedSidebarOpen() {
                        const saved = localStorage.getItem(this.sidebarStorageKey);

                        return saved === null ? true : saved === 'true';
                    },
                    init() {
                        this.sidebarOpen = this.isDesktop() ? this.savedSidebarOpen() : false;

                        window.addEventListener('resize', () => {
                            this.sidebarOpen = this.isDesktop() ? this.savedSidebarOpen() : false;
                        });
                    },
                    setSidebarOpen(open, persistDesktop = false) {
                        this.sidebarOpen = open;

                        if (persistDesktop && this.isDesktop()) {
                            localStorage.setItem(this.sidebarStorageKey, open ? 'true' : 'false');
                        }
                    },
                    toggleSidebar() {
                        this.setSidebarOpen(! this.sidebarOpen, true);
                    }
                }">
                @include('layouts.navigation')

                <div class="pt-16 transition-all duration-300" :class="sidebarOpen ? 'sm:ml-64' : 'sm:ml-20'">
                    @isset($header)
                        <header class="bg-white/70 dark:bg-[#0A1628]/60 backdrop-blur-md border-b border-slate-200/40 dark:border-white/10">
                            <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <main>
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const moonSvg = document.getElementById('theme-svg-moon');
        const sunSvg = document.getElementById('theme-svg-sun');

        function applyTheme(theme) {
            const isDark = theme === 'dark';

            document.documentElement.classList.toggle('dark', isDark);

            if (themeIcon) {
                themeIcon.classList.add('rotate-180', 'scale-75');

                setTimeout(() => {
                    if (moonSvg && sunSvg) {
                        moonSvg.classList.toggle('hidden', isDark);
                        sunSvg.classList.toggle('hidden', !isDark);
                    }
                    themeIcon.classList.remove('rotate-180', 'scale-75');
                    themeIcon.classList.add('rotate-0', 'scale-100');
                }, 150);
            }

            localStorage.setItem('theme', theme);
        }

        const savedTheme = localStorage.getItem('theme') || 'dark';
        applyTheme(savedTheme);

        if (themeToggle) {
            themeToggle.addEventListener('click', function () {
                const isDark = document.documentElement.classList.contains('dark');
                applyTheme(isDark ? 'light' : 'dark');
            });
        }
    });
    </script>
    </body>
</html>
