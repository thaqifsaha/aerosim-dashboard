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
        x-init="document.documentElement.classList.remove('dark')"
        class="font-sans m-0 text-gray-900 antialiased"
    >
        <div
            class="relative min-h-screen flex items-center justify-center
            bg-cover bg-center bg-no-repeat"
            style="background-image: url('{{ asset('images/login-bg.jpg') }}')"
        >

            {{-- Navy gradient overlay: same on all guest auth pages --}}
            <div class="absolute inset-0 bg-gradient-to-b from-[#0A1628]/65 via-[#0A1628]/35 to-[#0A1628]/80 pointer-events-none"></div>

            @if(request()->routeIs('login'))
                {{-- ── TAKEOFF TRANSITION OVERLAY ── --}}
                <div id="takeoff-overlay"
                     class="fixed inset-0 pointer-events-none"
                     style="z-index: 9000; background: #0A1628; opacity: 0;
                            transition: opacity 0.45s ease-in; will-change: opacity;">
                    {{-- Plane + trailing light streak --}}
                    <div id="takeoff-plane"
                         style="position: absolute; top: 48%; left: -100px;
                                opacity: 1; will-change: transform, opacity;">
                        <div id="plane-trail"
                             style="position: absolute; right: 100%; top: 50%;
                                    transform: translateY(-50%);
                                    width: 0; height: 3px;
                                    background: linear-gradient(to left, rgba(0,191,255,0.75), transparent);
                                    border-radius: 999px; opacity: 0;
                                    transition: width 0.6s ease-out, opacity 0.15s ease-in;">
                        </div>
                        {{-- Side-view airplane SVG --}}
                        <svg viewBox="0 0 120 60" width="90" height="45"
                             fill="white" xmlns="http://www.w3.org/2000/svg"
                             style="filter: drop-shadow(0 0 8px rgba(0,191,255,0.45));">
                            <path d="M5 32 Q45 27 100 25 Q112 24.5 118 27 Q112 29.5 100 30.5 Q45 33.5 5 37 Z"/>
                            <path d="M52 28 L74 8 L82 28 Z"/>
                            <path d="M14 33 L26 26 L30 33 Z"/>
                            <path d="M11 31.5 L19 18 L23 31.5 Z"/>
                        </svg>
                    </div>
                </div>

                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var form = document.querySelector('form[method="POST"]');
                    if (!form) return;

                    var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                    form.addEventListener('submit', function () {
                        var overlay = document.getElementById('takeoff-overlay');
                        var plane   = document.getElementById('takeoff-plane');
                        var trail   = document.getElementById('plane-trail');
                        var card    = document.getElementById('login-card');

                        sessionStorage.setItem('fromLogin', '1');

                        if (reduced) {
                            if (overlay) { overlay.style.opacity = '1'; overlay.style.pointerEvents = 'all'; }
                            return;
                        }

                        // Card scales down and fades out
                        if (card) {
                            card.style.transition = 'transform 0.3s ease-in, opacity 0.3s ease-in';
                            card.style.transform  = 'scale(0.95) translateY(-8px)';
                            card.style.opacity    = '0';
                        }

                        // Plane enters from the left edge, flies through the form, exits right
                        if (plane) {
                            plane.animate([
                                { transform: 'translateX(0)     translateY(0)     scale(1)',    opacity: 1 },
                                { transform: 'translateX(200vw) translateY(-150px) scale(1.05)', opacity: 1 }
                            ], { duration: 4000, easing: 'cubic-bezier(0.15, 0.6, 0.4, 1)', fill: 'forwards' });
                        }
                        if (trail) { trail.style.width = '150px'; trail.style.opacity = '0.85'; }

                        // Navy overlay fades in after the plane has crossed the form
                        setTimeout(function () {
                            if (overlay) {
                                overlay.style.opacity       = '1';
                                overlay.style.pointerEvents = 'all';
                            }
                        }, 250);
                    });
                });
                </script>
            @endif

            <img src="/images/plane-outline.png"
                class="absolute opacity-10 w-[500px] left-[-120px] top-[30%]
                pointer-events-none select-none"
                alt="">

            <div id="login-card"
                 class="relative z-10 w-full sm:max-w-md px-6 py-5
                        rounded-xl
                        bg-sky-50 border border-sky-200
                        backdrop-blur-md
                        shadow-[0_20px_60px_-5px_rgba(0,0,0,0.5),0_8px_20px_-8px_rgba(0,0,0,0.4)]
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
