<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">

            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-cyan-500/10 border border-cyan-500/30">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold tracking-widest text-slate-900 dark:text-white uppercase">
                    Aviation Performance Dashboard
                </h2>
            </div>

            <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Logged in as</span>
                <span class="text-xs font-bold text-cyan-600 dark:text-cyan-400 uppercase tracking-wider">{{ ucfirst(auth()->user()->role) }}</span>
            </div>

        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($isAdmin)
                {{-- ================= ADMIN DASHBOARD ================= --}}

                <div class="mb-6">

                    <!-- Section Header -->
                    <div class="flex items-center gap-2 mb-5">
                        <div class="w-1 h-5 bg-cyan-400 rounded-full"></div>
                        <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                            Pilot Overview
                        </h3>
                    </div>

                    <!-- Active Pilot + Search Row -->
                    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                        @if($activePilot)
                            <div class="inline-flex items-center gap-3 px-4 py-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 backdrop-blur-sm">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-400"></span>
                                </span>
                                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium uppercase tracking-wider">Active Pilot</span>
                                <span class="text-sm font-bold text-emerald-700 dark:text-emerald-300">{{ $activePilot->name }}</span>
                            </div>
                        @else
                            <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                <span class="text-xs text-slate-400 font-medium">No active pilot selected</span>
                            </div>
                        @endif

                        <!-- Search Form -->
                        <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text"
                                    name="search"
                                    value="{{ $search }}"
                                    placeholder="Search pilots by name or email"
                                    class="w-full sm:w-72 pl-9 pr-4 py-2 text-sm rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-white/5 text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-500/50 transition">
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="submit" class="cursor-pointer px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-semibold rounded-xl transition-colors duration-200">
                                    Search
                                </button>
                                @if($search !== '')
                                    <a href="{{ route('dashboard') }}"
                                        class="cursor-pointer inline-block px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl border border-slate-200 dark:border-white/10 transition-colors duration-200">
                                        Clear
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Pilot Cards Grid -->
                    @if($pilotCards->isEmpty())
                        <div class="flex flex-col items-center justify-center py-16 rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10">
                            <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-sm text-slate-500 dark:text-slate-400">No pilots found.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($pilotCards as $pilot)
                                <a href="{{ route('pilots.show', $pilot['id']) }}"
                                    class="group cursor-pointer block p-5 rounded-xl
                                    bg-white/70 dark:bg-white/5
                                    backdrop-blur-md
                                    border border-slate-200/80 dark:border-white/10
                                    shadow-sm hover:shadow-lg hover:shadow-cyan-500/5
                                    hover:border-cyan-500/40 dark:hover:border-cyan-500/30
                                    transition-all duration-200">

                                    <!-- Avatar + Info -->
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm select-none">
                                            {{ strtoupper(substr($pilot['name'], 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-slate-900 dark:text-white truncate">
                                                {{ $pilot['name'] }}
                                            </p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                                {{ $pilot['email'] }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="border-t border-slate-100 dark:border-white/5 mb-4"></div>

                                    <!-- Stats Grid -->
                                    <div class="grid grid-cols-2 gap-2.5">
                                        <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-white/5">
                                            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">Sessions</p>
                                            <p class="text-xl font-mono font-bold text-slate-900 dark:text-white">{{ $pilot['total_sessions'] }}</p>
                                        </div>
                                        <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-white/5">
                                            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">Avg Score</p>
                                            <p class="text-xl font-mono font-bold text-slate-900 dark:text-white">{{ $pilot['average_score'] }}</p>
                                        </div>
                                        <div class="p-2.5 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20">
                                            <p class="text-xs text-emerald-600 dark:text-emerald-400 uppercase tracking-wide mb-1">Pass</p>
                                            <p class="text-xl font-mono font-bold text-emerald-700 dark:text-emerald-400">{{ $pilot['pass_count'] }}</p>
                                        </div>
                                        <div class="p-2.5 rounded-lg bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20">
                                            <p class="text-xs text-red-500 dark:text-red-400 uppercase tracking-wide mb-1">Fail</p>
                                            <p class="text-xl font-mono font-bold text-red-600 dark:text-red-400">{{ $pilot['fail_count'] }}</p>
                                        </div>
                                    </div>

                                    <!-- Footer -->
                                    <div class="mt-4 flex items-center justify-end gap-1 text-xs text-slate-400 dark:text-slate-500 group-hover:text-cyan-500 transition-colors duration-200">
                                        <span>View Profile</span>
                                        <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

            @else
                {{-- ================= PILOT DASHBOARD ================= --}}

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

                    <!-- Total Sessions -->
                    <div class="p-5 rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Sessions</p>
                            <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-mono font-bold text-slate-900 dark:text-white">{{ $totalSessions }}</p>
                    </div>

                    <!-- Average Score -->
                    <div class="p-5 rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Average Score</p>
                            <div class="w-8 h-8 rounded-lg bg-cyan-500/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-cyan-500 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-end gap-2">
                            <p class="text-3xl font-mono font-bold text-slate-900 dark:text-white">{{ $averageScore }}</p>
                            @if($trend === 'up')
                                <span class="mb-1 flex items-center gap-0.5 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                    </svg>
                                    Improving
                                </span>
                            @elseif($trend === 'down')
                                <span class="mb-1 flex items-center gap-0.5 text-xs font-semibold text-red-500 dark:text-red-400">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                    </svg>
                                    Declining
                                </span>
                            @elseif($trend === 'same')
                                <span class="mb-1 flex items-center gap-0.5 text-xs font-semibold text-slate-500 dark:text-slate-400">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"/>
                                    </svg>
                                    Stable
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- PASS Count -->
                    <div class="p-5 rounded-xl bg-emerald-50/80 dark:bg-emerald-500/10 backdrop-blur-md border border-emerald-200/60 dark:border-emerald-500/20 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Pass Count</p>
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/15 flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-mono font-bold text-emerald-700 dark:text-emerald-400">{{ $passCount }}</p>
                    </div>

                    <!-- FAIL Count -->
                    <div class="p-5 rounded-xl backdrop-blur-md shadow-sm
                        {{ $failCount > 0
                            ? 'bg-red-50/80 dark:bg-red-500/10 border border-red-200/60 dark:border-red-500/20'
                            : 'bg-white/70 dark:bg-white/5 border border-slate-200/80 dark:border-white/10' }}">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-semibold uppercase tracking-wider {{ $failCount > 0 ? 'text-red-600 dark:text-red-400' : 'text-slate-500 dark:text-slate-400' }}">Fail Count</p>
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $failCount > 0 ? 'bg-red-500/15' : 'bg-slate-500/10' }}">
                                <svg class="w-4 h-4 {{ $failCount > 0 ? 'text-red-600 dark:text-red-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-mono font-bold {{ $failCount > 0 ? 'text-red-700 dark:text-red-400' : 'text-slate-400 dark:text-slate-500' }}">{{ $failCount }}</p>
                    </div>
                </div>

                <!-- Sessions Table -->
                <div class="rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex items-center gap-2">
                        <div class="w-1 h-5 bg-cyan-400 rounded-full"></div>
                        <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                            Flight Sessions
                        </h3>
                    </div>

                    @if($sessions->isEmpty())
                        <div class="flex flex-col items-center justify-center py-16">
                            <svg class="w-10 h-10 text-slate-300 dark:text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <p class="text-sm text-slate-500 dark:text-slate-400">No flight sessions found.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-slate-50/80 dark:bg-slate-800/30">
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Session ID</th>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Flight Date</th>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Duration</th>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Aircraft Type</th>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Score</th>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Result</th>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                    @foreach($sessions as $session)
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-white/5 transition-colors duration-150">
                                            <td class="px-5 py-3.5 text-sm font-mono text-slate-500 dark:text-slate-400">#{{ $session->id }}</td>
                                            <td class="px-5 py-3.5 text-sm text-slate-700 dark:text-slate-300">{{ $session->flight_date ?? '-' }}</td>
                                            <td class="px-5 py-3.5 text-sm font-mono text-slate-700 dark:text-slate-300">{{ $session->formatted_duration }}</td>
                                            <td class="px-5 py-3.5 text-sm text-slate-700 dark:text-slate-300">{{ $session->aircraft_type ?? '-' }}</td>
                                            <td class="px-5 py-3.5 text-sm font-mono font-semibold text-slate-800 dark:text-slate-200">{{ $session->dssResult->total_score ?? 'N/A' }}</td>
                                            <td class="px-5 py-3.5">
                                                @if(optional($session->dssResult)->pass_fail === 'PASS')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-bold rounded-full bg-emerald-100 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30">
                                                        PASS
                                                    </span>
                                                @elseif(optional($session->dssResult)->pass_fail === 'FAIL')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-bold rounded-full bg-red-100 dark:bg-red-500/15 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-500/30">
                                                        FAIL
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-bold rounded-full bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-white/10">
                                                        N/A
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-5 py-3.5">
                                                <a href="{{ route('flight-sessions.show', $session->id) }}"
                                                   class="cursor-pointer inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-semibold rounded-lg transition-colors duration-200">
                                                    View Details
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
