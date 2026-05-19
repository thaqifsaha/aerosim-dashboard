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
                        <x-nav-link :href="route('flight-schedules.index')"
                                    :active="request()->routeIs('flight-schedules.*')">
                            {{ auth()->user()->role === 'admin' ? __('Manage Schedule') : __('Book Flight Session') }}
                        </x-nav-link>
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
                            aria-label="Flight session notifications">
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
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Flight Session Notifications</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Upcoming reminders and latest completed activity</p>
                            </div>

                            <div class="max-h-96 overflow-y-auto">
                                <div class="px-4 py-2 bg-gray-50 dark:bg-gray-800/70 border-b border-gray-100 dark:border-gray-700/70">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Upcoming Flight Sessions</p>
                                </div>

                                @forelse($upcomingScheduleReminders as $schedule)
                                    <a href="{{ route('flight-schedules.index') }}"
                                        class="block px-4 py-3 border-b border-gray-100 dark:border-gray-700/70 hover:bg-gray-100 dark:hover:bg-gray-700/70 transition">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                    {{ auth()->user()->role === 'admin' ? ($schedule->user?->name ?? 'Unknown pilot') : 'Your booked session' }}
                                                </p>
                                                <p class="mt-1 text-xs text-gray-600 dark:text-gray-300">
                                                    {{ $schedule->scheduled_date->format('d M Y') }}
                                                    · {{ \Carbon\Carbon::parse($schedule->scheduled_time)->format('g:i A') }}
                                                </p>
                                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $schedule->aircraft_type }}
                                                </p>
                                            </div>

                                            <div class="shrink-0 self-end text-right">
                                                <div class="mt-2 flex justify-end">
                                                    <span class="inline-flex px-2 py-0.5 text-[11px] font-semibold rounded-full bg-blue-100 text-blue-700">
                                                        {{ ucfirst($schedule->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-4 py-4 border-b border-gray-100 dark:border-gray-700/70">
                                        <p class="text-sm text-gray-600 dark:text-gray-300">No upcoming sessions booked.</p>
                                    </div>
                                @endforelse

                                @if(auth()->user()->role === 'admin' && $bookedScheduleNotifications->isNotEmpty())
                                    <div class="px-4 py-2 bg-gray-50 dark:bg-gray-800/70 border-b border-gray-100 dark:border-gray-700/70">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">New Bookings</p>
                                    </div>

                                    @foreach($bookedScheduleNotifications as $notification)
                                        @php
                                            $data = $notification->data;
                                            $bookedAt = ! empty($data['booked_at'])
                                                ? \Carbon\Carbon::parse($data['booked_at'])->diffForHumans()
                                                : null;
                                        @endphp

                                        <a href="{{ route('flight-schedules.index') }}"
                                            class="block px-4 py-3 border-b border-gray-100 dark:border-gray-700/70 hover:bg-gray-100 dark:hover:bg-gray-700/70 transition">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                        {{ $data['pilot_name'] ?? 'Unknown pilot' }}
                                                    </p>
                                                    <p class="mt-1 text-xs text-gray-600 dark:text-gray-300">
                                                        Booked {{ $data['aircraft_type'] ?? 'flight session' }}
                                                    </p>
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                        {{ ! empty($data['scheduled_date']) ? \Carbon\Carbon::parse($data['scheduled_date'])->format('d M Y') : '-' }}
                                                        @if(! empty($data['scheduled_time']))
                                                            · {{ \Carbon\Carbon::parse($data['scheduled_time'])->format('g:i A') }}
                                                        @endif
                                                    </p>
                                                </div>

                                                <div class="shrink-0 text-right">
                                                    @if(is_null($notification->read_at))
                                                        <span class="inline-block h-2 w-2 rounded-full bg-sky-500"></span>
                                                    @endif
                                                    @if($bookedAt)
                                                        <p class="mt-1 text-[11px] text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $bookedAt }}</p>
                                                    @endif
                                                    <div class="mt-2 flex justify-end">
                                                        <span class="inline-flex px-2 py-0.5 text-[11px] font-semibold rounded-full bg-blue-100 text-blue-700">
                                                            Upcoming
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif

                                @if($reminderNotifications->isNotEmpty())
                                    <div class="px-4 py-2 bg-gray-50 dark:bg-gray-800/70 border-b border-gray-100 dark:border-gray-700/70">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Tomorrow's Reminders</p>
                                    </div>

                                    @foreach($reminderNotifications as $notification)
                                        @php
                                            $data = $notification->data;
                                            $remindedAt = ! empty($data['reminded_at'])
                                                ? \Carbon\Carbon::parse($data['reminded_at'])->diffForHumans()
                                                : null;
                                        @endphp

                                        <a href="{{ route('flight-schedules.index') }}"
                                            class="block px-4 py-3 border-b border-gray-100 dark:border-gray-700/70 hover:bg-gray-100 dark:hover:bg-gray-700/70 transition">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                        {{ auth()->user()->role === 'admin' ? ($data['pilot_name'] ?? 'Unknown pilot') : 'Your booked session' }}
                                                    </p>
                                                    <p class="mt-1 text-xs text-gray-600 dark:text-gray-300">
                                                        Reminder: {{ $data['aircraft_type'] ?? 'flight session' }}
                                                    </p>
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                        {{ ! empty($data['scheduled_date']) ? \Carbon\Carbon::parse($data['scheduled_date'])->format('d M Y') : '-' }}
                                                        @if(! empty($data['scheduled_time']))
                                                            · {{ \Carbon\Carbon::parse($data['scheduled_time'])->format('g:i A') }}
                                                        @endif
                                                    </p>
                                                </div>

                                                <div class="shrink-0 text-right">
                                                    <span class="inline-block h-2 w-2 rounded-full bg-sky-500"></span>
                                                    @if($remindedAt)
                                                        <p class="mt-1 text-[11px] text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $remindedAt }}</p>
                                                    @endif
                                                    <div class="mt-2 flex justify-end">
                                                        <span class="inline-flex px-2 py-0.5 text-[11px] font-semibold rounded-full bg-blue-100 text-blue-700">
                                                            Upcoming
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif

                                <div class="px-4 py-2 bg-gray-50 dark:bg-gray-800/70 border-b border-gray-100 dark:border-gray-700/70">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Completed Flight Sessions</p>
                                </div>

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
                                                            {{ \App\Models\FlightSession::formatDuration($data['duration_sec']) }}
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
                                                    <div class="mt-2 flex justify-end">
                                                        <span class="inline-flex px-2 py-0.5 text-[11px] font-semibold rounded-full bg-green-100 text-green-700">
                                                            Completed
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                @empty
                                    <div class="px-4 py-6 text-center">
                                        <p class="text-sm text-gray-600 dark:text-gray-300">No completed flight sessions yet.</p>
                                    </div>
                                @endforelse

                                @if(auth()->user()->role === 'admin')
                                    <div class="px-4 py-2 bg-gray-50 dark:bg-gray-800/70 border-y border-gray-100 dark:border-gray-700/70">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Cancelled Bookings</p>
                                    </div>

                                    @forelse($cancelledScheduleNotifications as $notification)
                                        @php
                                            $data = $notification->data;
                                            $cancelledAt = ! empty($data['cancelled_at'])
                                                ? \Carbon\Carbon::parse($data['cancelled_at'])->diffForHumans()
                                                : null;
                                        @endphp

                                        <a href="{{ route('flight-schedules.index') }}"
                                            class="block px-4 py-3 border-b border-gray-100 dark:border-gray-700/70 hover:bg-gray-100 dark:hover:bg-gray-700/70 transition">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                        {{ $data['pilot_name'] ?? 'Unknown pilot' }}
                                                    </p>
                                                    <p class="mt-1 text-xs text-gray-600 dark:text-gray-300">
                                                        {{ $data['aircraft_type'] ?? 'flight session' }}
                                                    </p>
                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                        {{ ! empty($data['scheduled_date']) ? \Carbon\Carbon::parse($data['scheduled_date'])->format('d M Y') : '-' }}
                                                        @if(! empty($data['scheduled_time']))
                                                            · {{ \Carbon\Carbon::parse($data['scheduled_time'])->format('g:i A') }}
                                                        @endif
                                                    </p>
                                                </div>

                                                <div class="shrink-0 text-right">
                                                    @if(is_null($notification->read_at))
                                                        <span class="inline-block h-2 w-2 rounded-full bg-sky-500"></span>
                                                    @endif
                                                    @if($cancelledAt)
                                                        <p class="mt-1 text-[11px] text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $cancelledAt }}</p>
                                                    @endif
                                                    <div class="mt-2 flex justify-end">
                                                        <span class="inline-flex px-2 py-0.5 text-[11px] font-semibold rounded-full bg-red-100 text-red-700">
                                                            Cancelled
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="px-4 py-4">
                                            <p class="text-sm text-gray-600 dark:text-gray-300">No cancelled bookings yet.</p>
                                        </div>
                                    @endforelse
                                @endif
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

                        <x-dropdown-link :href="route('contact-about')">
                            {{ __('Contact / About') }}
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
            <x-responsive-nav-link :href="route('flight-schedules.index')" :active="request()->routeIs('flight-schedules.*')">
                {{ auth()->user()->role === 'admin' ? __('Manage Schedule') : __('Book Flight Session') }}
            </x-responsive-nav-link>
            @if(auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('pilot-selection.index')" :active="request()->routeIs('pilot-selection.*')">
                    {{ __('Select Pilot') }}
                </x-responsive-nav-link>
            @endif
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
