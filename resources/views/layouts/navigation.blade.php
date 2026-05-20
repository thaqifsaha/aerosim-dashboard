@php
    $sidebarLinkClasses = function (bool $active) {
        return $active
            ? 'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-200'
            : 'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-white/10 dark:hover:text-white transition';
    };

    $sidebarIconClasses = 'h-5 w-5 shrink-0';
@endphp

<nav class="relative z-50">
    <div class="fixed inset-x-0 top-0 z-50 h-16 bg-white/70 dark:bg-gray-900/40 backdrop-blur-md border-b border-gray-200/40 dark:border-white/10">
        <div class="flex h-full items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    @click="sidebarOpen = ! sidebarOpen"
                    class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none transition-all duration-300"
                    aria-label="Toggle sidebar">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <img src="{{ asset('images/company_logo.png') }}"
                        alt="Company Logo"
                        class="h-20 w-auto object-contain opacity-90 hover:opacity-100 transition dark:brightness-90">
                </a>
            </div>

            <div class="flex items-center">
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
                            class="relative w-10 h-10 flex items-center justify-center rounded-lg
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

                <button id="theme-toggle"
                    type="button"
                    class="ml-4 w-10 h-10 flex items-center justify-center rounded-lg
                        bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100
                        hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300">
                    <span id="theme-icon" class="inline-block transition-transform duration-500">🌙</span>
                </button>
            </div>
        </div>
    </div>

    <div
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        class="fixed inset-0 top-16 z-30 bg-gray-950/40 sm:hidden"
        style="display: none;">
    </div>

    <aside
        class="fixed left-0 top-16 bottom-0 z-40 flex flex-col bg-white/80 dark:bg-gray-900/70 backdrop-blur-md border-r border-gray-200/40 dark:border-white/10 shadow-xl sm:shadow-none transition-all duration-300"
        :class="sidebarOpen ? 'w-64 translate-x-0' : 'w-64 -translate-x-full sm:w-20 sm:translate-x-0'">
        <div class="flex-1 overflow-y-auto px-3 py-4">
            <div class="space-y-1">
                <a href="{{ route('dashboard') }}"
                    class="{{ $sidebarLinkClasses(request()->routeIs('dashboard')) }}"
                    @click="if (window.innerWidth < 640) sidebarOpen = false">
                    <svg class="{{ $sidebarIconClasses }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l9-9 9 9M5 10v10h14V10" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity>Dashboard</span>
                </a>

                @auth
                    <a href="{{ route('flight-schedules.index') }}"
                        class="{{ $sidebarLinkClasses(request()->routeIs('flight-schedules.*')) }}"
                        @click="if (window.innerWidth < 640) sidebarOpen = false">
                        <svg class="{{ $sidebarIconClasses }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition.opacity>
                            {{ auth()->user()->role === 'admin' ? __('Manage Schedule') : __('Book Flight Session') }}
                        </span>
                    </a>

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('pilot-selection.index') }}"
                            class="{{ $sidebarLinkClasses(request()->routeIs('pilot-selection.*')) }}"
                            @click="if (window.innerWidth < 640) sidebarOpen = false">
                            <svg class="{{ $sidebarIconClasses }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0-4a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 100-8 4 4 0 000 8z" />
                            </svg>
                            <span x-show="sidebarOpen" x-transition.opacity>{{ __('Select Pilot') }}</span>
                        </a>
                    @endif
                @endauth

                <a href="{{ route('contact-about') }}"
                    class="{{ $sidebarLinkClasses(request()->routeIs('contact-about')) }}"
                    @click="if (window.innerWidth < 640) sidebarOpen = false">
                    <svg class="{{ $sidebarIconClasses }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18 9 9 0 000 18z" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition.opacity>{{ __('Contact / About') }}</span>
                </a>
            </div>
        </div>

        @auth
            <div class="border-t border-gray-200/70 dark:border-white/10 px-3 py-4">
                <div class="mb-3 px-3" x-show="sidebarOpen" x-transition.opacity>
                    <p class="truncate text-sm font-semibold text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</p>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                </div>

                <div class="space-y-1">
                    <a href="{{ route('profile.edit') }}"
                        class="{{ $sidebarLinkClasses(request()->routeIs('profile.edit')) }}"
                        @click="if (window.innerWidth < 640) sidebarOpen = false">
                        <svg class="{{ $sidebarIconClasses }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 7.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 21a7.5 7.5 0 0115 0" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition.opacity>{{ __('Profile') }}</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                            class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-white/10 dark:hover:text-white transition">
                            <svg class="{{ $sidebarIconClasses }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3-3h-9m9 0l-3-3m3 3l-3 3" />
                            </svg>
                            <span x-show="sidebarOpen" x-transition.opacity>{{ __('Log Out') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </aside>
</nav>
