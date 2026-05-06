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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&family=Orbitron:wght@500;600&display=swap" rel="stylesheet">

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
    <body class="font-sans antialiased bg-slate-100 dark:bg-gray-950">
        <div class="min-h-screen relative overflow-hidden bg-gradient-to-b from-blue-100 via-white to-white dark:from-[#0a192f] dark:via-[#0f172a] dark:to-black">

            <div class="pointer-events-none fixed inset-0 z-0 opacity-40 dark:opacity-20">
                <div class="absolute top-20 left-10 w-72 h-72 bg-white/50 dark:bg-blue-500/10 rounded-full blur-3xl"></div>
                <div class="absolute top-80 right-20 w-96 h-96 bg-blue-200/40 dark:bg-cyan-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 left-1/3 w-80 h-80 bg-sky-100/60 dark:bg-indigo-500/10 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10">
                @include('layouts.navigation')

                @isset($header)
                    <header class="bg-white/70 dark:bg-gray-900/40 backdrop-blur-md border-b border-gray-200/40 dark:border-white/10">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');

        function applyTheme(theme) {
            const isDark = theme === 'dark';

            document.documentElement.classList.toggle('dark', isDark);

            if (themeIcon) {
                themeIcon.classList.add('rotate-180', 'scale-75');

                setTimeout(() => {
                    themeIcon.textContent = isDark ? '☀️' : '🌙';
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
