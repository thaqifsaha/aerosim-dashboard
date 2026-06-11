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
    {{-- Idle timeout warning modal --}}
    @auth
    <div x-data="idleTimer()" x-init="start()" class="relative z-50">
        <div
            x-show="showWarning"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50"
            style="display: none;"
        >
            <div
                x-show="showWarning"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white dark:bg-[#0d1f3c] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-8 text-center"
            >
                {{-- Icon --}}
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-5 rounded-full bg-amber-100 dark:bg-amber-500/10">
                    <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>

                <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-1">Session Timeout Warning</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-5">You've been inactive. You will be logged out in</p>

                {{-- Countdown --}}
                <div class="flex items-center justify-center gap-1 mb-6">
                    <div class="bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-5 py-3 min-w-[4rem]">
                        <span class="text-3xl font-bold text-amber-500 tabular-nums" x-text="String(Math.floor(countdown / 60)).padStart(2, '0')">00</span>
                    </div>
                    <span class="text-2xl font-bold text-slate-400">:</span>
                    <div class="bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl px-5 py-3 min-w-[4rem]">
                        <span class="text-3xl font-bold text-amber-500 tabular-nums" x-text="String(countdown % 60).padStart(2, '0')">00</span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col gap-3">
                    <button
                        @click="stayLoggedIn()"
                        class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition-colors duration-200"
                    >
                        Stay Logged In
                    </button>
                    <button
                        @click="logout()"
                        class="w-full py-2.5 px-4 rounded-xl bg-transparent border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-white/5 text-sm font-medium transition-colors duration-200"
                    >
                        Logout Now
                    </button>
                </div>
            </div>
        </div>

        <form id="idle-logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
            @csrf
        </form>
    </div>
    @endauth

    <script>
    function idleTimer() {
        return {
            showWarning: false,
            countdown: 120,
            idleTimeoutHandle: null,
            countdownHandle: null,
            idleMinutes: 25,

            start() {
                this.resetTimer();
                ['mousemove', 'mousedown', 'keydown', 'touchstart', 'scroll', 'click'].forEach(event => {
                    document.addEventListener(event, () => this.onActivity(), { passive: true });
                });
            },

            onActivity() {
                if (this.showWarning) return;
                this.resetTimer();
            },

            resetTimer() {
                clearTimeout(this.idleTimeoutHandle);
                this.idleTimeoutHandle = setTimeout(() => this.showIdleWarning(), this.idleMinutes * 60 * 1000);
            },

            showIdleWarning() {
                this.showWarning = true;
                this.countdown = 120;
                this.countdownHandle = setInterval(() => {
                    this.countdown--;
                    if (this.countdown <= 0) {
                        this.logout();
                    }
                }, 1000);
            },

            stayLoggedIn() {
                this.showWarning = false;
                clearInterval(this.countdownHandle);
                this.resetTimer();
            },

            logout() {
                clearInterval(this.countdownHandle);
                document.getElementById('idle-logout-form').submit();
            }
        };
    }
    </script>

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
