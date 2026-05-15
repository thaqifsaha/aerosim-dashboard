<nav x-data="{ open: false }" class="relative z-50 bg-white/70 dark:bg-gray-900/40 backdrop-blur-md border-b border-gray-200/40 dark:border-white/10">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <img src="{{ asset('images/company_logo.png') }}"
                            alt="Company Logo"
                            class="h-28 w-auto object-contain opacity-90 hover:opacity-100 transition dark:brightness-90">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <x-nav-link :href="route('pilot-selection.index')" 
                                        :active="request()->routeIs('pilot-selection.*')">
                                {{ __('Select Pilot') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">

                <!-- 🌙 Theme Toggle Button -->    
                <button id="theme-toggle"
                    type="button"
                    class="mr-4 w-10 h-10 flex items-center justify-center rounded-lg 
                        bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100 
                        hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300">
                    <span id="theme-icon" class="inline-block transition-transform duration-500">🌙</span>
                </button>

                <x-dropdown align="right" width="w-80" contentClasses="py-0">
                    <x-slot name="trigger">
                        <button id="flight-session-notification-button"
                            type="button"
                            onclick="fetch('{{ route('notifications.flight-sessions.read') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                    'Accept': 'application/json'
                                }
                            }).then(() => {
                                const badge = document.getElementById('flight-session-notification-badge');
                                if (badge) {
                                    badge.textContent = '0';
                                    badge.classList.add('hidden');
                                }
                            });"
                            class="relative mr-4 w-10 h-10 flex items-center justify-center rounded-lg
                                bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100
                                hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none transition-all duration-300"
                            aria-label="Completed flight session notifications">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a3 3 0 11-5.714 0" />
                            </svg>

                            <span id="flight-session-notification-badge"
                                class="{{ $unreadFlightSessionNotificationCount > 0 ? '' : 'hidden' }} absolute -top-1 -right-1 min-w-5 h-5 px-1 rounded-full bg-red-600 text-white text-xs font-semibold leading-5 text-center shadow">
                                {{ $unreadFlightSessionNotificationCount }}
                            </span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="w-80 overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Completed Flight Sessions</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Latest session activity</p>
                            </div>

                            <div class="max-h-96 overflow-y-auto">
                                @forelse($flightSessionNotifications as $notification)
                                    @php
                                        $data = $notification->data;
                                        $sessionId = $data['flight_session_id'] ?? null;
                                        $completedAt = ! empty($data['completed_at'])
                                            ? \Carbon\Carbon::parse($data['completed_at'])->diffForHumans()
                                            : null;
                                    @endphp

                                    @if($sessionId)
                                        <a href="{{ route('flight-sessions.show', $sessionId) }}"
                                            class="block px-4 py-3 border-b border-gray-100 dark:border-gray-700/70 hover:bg-gray-100 dark:hover:bg-gray-700/70 transition">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                        {{ auth()->user()->role === 'admin' ? ($data['pilot_name'] ?? 'Unknown pilot') : 'Your flight session' }}
                                                    </p>
                                                    <p class="mt-1 text-xs text-gray-600 dark:text-gray-300">
                                                        Session #{{ $sessionId }}
                                                        @if(! empty($data['aircraft_type']))
                                                            · {{ $data['aircraft_type'] }}
                                                        @endif
                                                    </p>
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                        @if(isset($data['duration_sec']))
                                                            {{ $data['duration_sec'] }} sec
                                                        @endif
                                                        @if(! empty($data['pass_fail']))
                                                            · {{ $data['pass_fail'] }}
                                                        @endif
                                                        @if(isset($data['total_score']))
                                                            · Score {{ $data['total_score'] }}
                                                        @endif
                                                    </p>
                                                </div>

                                                <div class="shrink-0 text-right">
                                                    @if(is_null($notification->read_at))
                                                        <span class="inline-block h-2 w-2 rounded-full bg-sky-500"></span>
                                                    @endif
                                                    @if($completedAt)
                                                        <p class="mt-1 text-[11px] text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $completedAt }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                @empty
                                    <div class="px-4 py-6 text-center">
                                        <p class="text-sm text-gray-600 dark:text-gray-300">No completed flight sessions yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </x-slot>
                </x-dropdown>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="h-10 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md bg-gray-200 hover:bg-gray-300 text-gray-800 
                               dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
