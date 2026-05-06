<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            
            <!-- LEFT: Title -->
            <h2 class="hud-title animate-hud text-xl font-semibold">
                Aviation Performance Dashboard
            </h2>

            <!-- RIGHT: Logged in -->
            <p class="text-sm text-gray-400">
                Logged in as:
                <span class="font-semibold text-blue-400">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </p>

        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($isAdmin)
                {{-- ================= ADMIN DASHBOARD ================= --}}
                
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-3">
                        Pilot Overview
                    </h3>

                    @if($activePilot)
                    <div class="mb-4 flex items-center gap-2 text-sm">

                        <span class="text-gray-400">Active Pilot:</span>

                        <span class="flex items-center gap-2 px-3 py-1 bg-blue-500/10 text-blue-400 rounded-full">
                            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                            {{ $activePilot->name }}
                        </span>

                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($pilotCards as $pilot)
                            <a href="{{ route('pilots.show', $pilot['id']) }}"
                                class="block p-4 rounded-xl
                                bg-white/70 dark:bg-white/5
                                backdrop-blur-md
                                border border-white/40 dark:border-white/10
                                shadow-lg
                                transition transform hover:scale-105 hover:shadow-2xl hover:border-blue-400">

                                <div class="mb-2">
                                    <p class="text-base font-bold text-gray-900 dark:text-gray-100">
                                        {{ $pilot['name'] }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-300">
                                        {{ $pilot['email'] }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-2 gap-2 text-sm mt-3">
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-300">Sessions</p>    
                                        <p class="text-xl font-mono tracking-widest text-gray-900 dark:text-gray-100">{{ $pilot['total_sessions'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-300">Avg Score</p>
                                        <p class="text-xl font-mono tracking-widest text-gray-900 dark:text-gray-100">{{ $pilot['average_score'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-300">PASS</p>
                                        <p class="text-xl font-mono tracking-widest text-green-600">{{ $pilot['pass_count'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-300">FAIL</p>
                                        <p class="text-xl font-mono tracking-widest text-red-600">{{ $pilot['fail_count'] }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

            @else
                {{-- ================= PILOT DASHBOARD ================= --}}

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-100 text-blue-800 p-4 rounded-lg shadow">
                        <p class="text-sm font-medium">Total Sessions</p>
                        <p class="text-xl font-mono tracking-widest">{{ $totalSessions }}</p>
                    </div>

                    <div class="bg-purple-100 text-purple-800 p-4 rounded-lg shadow">
                        <p class="text-sm font-medium">Average Score</p>
                        <p class="text-xl font-mono tracking-widest">{{ $averageScore }}</p>

                        @if($trend === 'up')
                            <p class="text-green-500 text-sm">↑ Improving</p>
                        @elseif($trend === 'down')
                            <p class="text-red-500 text-sm">↓ Declining</p>
                        @elseif($trend === 'same')
                            <p class="text-gray-500 text-sm">→ Stable</p>
                        @endif
                    </div>

                    <div class="bg-green-100 text-green-800 p-4 rounded-lg shadow">
                        <p class="text-sm font-medium">PASS Count</p>
                        <p class="text-xl font-mono tracking-widest">{{ $passCount }}</p>
                    </div>

                    <div class="p-4 rounded-lg shadow {{ $failCount > 0 ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-600' }}">
                        <p class="text-sm font-medium">FAIL Count</p>
                        <p class="text-xl font-mono tracking-widest">{{ $failCount }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">
                        Flight Sessions
                    </h3>

                    @if($sessions->isEmpty())
                        <p class="text-gray-600 dark:text-gray-300">No flight sessions found.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-200 dark:border-gray-700 rounded-lg">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Session ID</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Flight Date</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Duration (sec)</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Aircraft Type</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Total Score</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Result</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sessions as $session)
                                        <tr class="border-t border-gray-200 dark:border-gray-700">
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $session->id }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $session->flight_date ?? '-' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $session->duration_sec ?? '-' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $session->aircraft_type ?? '-' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $session->dssResult->total_score ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                @if(optional($session->dssResult)->pass_fail === 'PASS')
                                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                                        PASS
                                                    </span>
                                                @elseif(optional($session->dssResult)->pass_fail === 'FAIL')
                                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                        FAIL
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                                        N/A
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <a href="{{ route('flight-sessions.show', $session->id) }}"
                                                   class="inline-block px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded hover:bg-blue-700">
                                                    View Details
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