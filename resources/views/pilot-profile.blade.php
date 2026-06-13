<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">

            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-cyan-500/10 border border-cyan-500/30">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold tracking-widest text-slate-900 dark:text-white uppercase">
                    Pilot Profile
                </h2>
            </div>

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('dashboard') }}"
                    class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-white/10 transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Dashboard
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Top Row: Info + Stats -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

                {{-- Pilot Info Card --}}
                <div class="relative bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm rounded-xl p-6">

                    @if(auth()->user()->role === 'admin')
                        <form method="POST" action="{{ route('pilots.deactivate', $pilot) }}"
                            onsubmit="return confirm('Deactivate this pilot account? The pilot will no longer be able to sign in, but historical records will be preserved.');"
                            class="mb-4 flex justify-end sm:absolute sm:right-6 sm:top-6 sm:mb-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cursor-pointer px-4 py-2 text-xs font-bold tracking-wide bg-red-600 hover:bg-red-500 text-white rounded-lg transition-colors duration-200">
                                Deactivate
                            </button>
                        </form>
                    @endif

                    <div class="flex flex-col items-center justify-around text-center gap-5 py-4">

                        <!-- Profile Photo -->
                        @if($pilot->profile_photo_url)
                            <div class="flex-shrink-0 relative">
                                <div class="w-36 h-36 rounded-full ring-2 ring-cyan-500/40 ring-offset-2 ring-offset-white dark:ring-offset-slate-900 overflow-hidden shadow-lg">
                                    <img src="{{ $pilot->profile_photo_url }}"
                                        id="profileImage"
                                        class="w-full h-full object-cover object-center cursor-pointer hover:opacity-90 transition-opacity duration-200"
                                        alt="{{ $pilot->name }} profile photo">
                                </div>
                            </div>
                        @else
                            <div class="flex-shrink-0 w-36 h-36 rounded-full bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center ring-2 ring-slate-200 dark:ring-white/10 shadow-md">
                                <img src="{{ asset('images/default_user.png') }}"
                                    class="w-20 h-20 object-contain opacity-60 dark:opacity-50 filter grayscale"
                                    alt="Default profile">
                            </div>
                        @endif

                        <!-- Pilot Details -->
                        <div class="space-y-4 min-w-0 w-full">
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">
                                {{ $pilot->name }}
                            </h3>

                            <div class="flex items-center justify-center gap-2 text-base">
                                <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-slate-500 dark:text-slate-400">{{ $pilot->email }}</span>
                            </div>

                            @if($pilot->phone_number)
                            <div class="flex items-center justify-center gap-2 text-base">
                                <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span class="text-slate-500 dark:text-slate-400">{{ $pilot->phone_number }}</span>
                            </div>
                            @endif

                            <div class="flex items-center justify-center gap-2 text-base">
                                <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                <span class="text-slate-500 dark:text-slate-400">Trend:</span>
                                <span class="px-3.5 py-1 rounded-full text-sm font-bold
                                    {{ $trend == 'Improving'
                                        ? 'bg-emerald-100 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30'
                                        : ($trend == 'Declining'
                                            ? 'bg-red-100 dark:bg-red-500/15 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-500/30'
                                            : 'bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-white/10') }}">
                                    {{ $trend }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image Preview Modal -->
                <div id="imageModal"
                    class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center hidden z-50">
                    <span id="closeModal"
                        class="absolute top-6 right-8 text-white text-3xl cursor-pointer leading-none hover:text-slate-300 transition-colors">&times;</span>
                    <img id="modalImage"
                        class="max-w-[90%] max-h-[90%] rounded-xl shadow-2xl"
                        alt="Profile photo preview">
                </div>

                {{-- Performance Summary Cards --}}
                <div class="grid grid-cols-2 gap-4">

                    <div class="p-5 rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Sessions</p>
                            <div class="w-7 h-7 rounded-lg bg-blue-500/10 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-blue-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-7xl font-mono font-bold text-slate-900 dark:text-white text-center">{{ $totalSessions }}</p>
                    </div>

                    <div class="p-5 rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Avg Score</p>
                            <div class="w-7 h-7 rounded-lg bg-cyan-500/10 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-cyan-500 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-7xl font-mono font-bold text-slate-900 dark:text-white text-center">{{ $averageScore }}</p>
                    </div>

                    <div class="p-5 rounded-xl bg-emerald-50/80 dark:bg-emerald-500/10 backdrop-blur-md border border-emerald-200/60 dark:border-emerald-500/20 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Pass Count</p>
                            <div class="w-7 h-7 rounded-lg bg-emerald-500/15 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-7xl font-mono font-bold text-emerald-700 dark:text-emerald-400 text-center">{{ $passCount }}</p>
                    </div>

                    <div class="p-5 rounded-xl bg-red-50/80 dark:bg-red-500/10 backdrop-blur-md border border-red-200/60 dark:border-red-500/20 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-semibold text-red-600 dark:text-red-400 uppercase tracking-wider">Fail Count</p>
                            <div class="w-7 h-7 rounded-lg bg-red-500/15 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-7xl font-mono font-bold text-red-700 dark:text-red-400 text-center">{{ $failCount }}</p>
                    </div>

                </div>
            </div>

            <!-- Chart + Sessions Table Card -->
            <div class="rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">

                <!-- Score Trend Chart -->
                <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex items-center gap-2">
                    <div class="w-1 h-5 bg-cyan-400 rounded-full"></div>
                    <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                        Score Trend
                    </h3>
                </div>
                <div class="p-6 border-b border-slate-100 dark:border-white/10">
                    <canvas id="scoreChart"></canvas>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    const ctx = document.getElementById('scoreChart');

                    const labels = {!! json_encode($scoreTrend->pluck('date')) !!};
                    const scores = {!! json_encode($scoreTrend->pluck('score')) !!};
                    const badFlags = {!! json_encode($scoreTrend->pluck('is_bad')) !!};

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Score Trend',
                                    data: scores,
                                    borderColor: '#00BFFF',
                                    backgroundColor: 'rgba(0,191,255,0.08)',
                                    fill: true,
                                    tension: 0.3,
                                    pointRadius: scores.map((_, i) => badFlags[i] ? 6 : 4),
                                    pointHoverRadius: scores.map((_, i) => badFlags[i] ? 7 : 5),
                                    pointBackgroundColor: scores.map((_, i) => badFlags[i] ? '#ef4444' : '#00BFFF'),
                                    pointBorderColor: scores.map((_, i) => badFlags[i] ? '#ef4444' : '#00BFFF'),
                                },
                                {
                                    label: 'PASS Threshold',
                                    data: labels.map(() => 70),
                                    borderColor: '#ef4444',
                                    borderDash: [8, 6],
                                    pointRadius: 0,
                                    pointHoverRadius: 0,
                                    tension: 0,
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        afterLabel: function(context) {
                                            const index = context.dataIndex;
                                            if (context.dataset.label === 'Score Trend' && badFlags[index]) {
                                                return 'Warning: Low / failed session';
                                            }
                                            return '';
                                        }
                                    }
                                }
                            }
                        }
                    });
                </script>

                <!-- Sessions Table -->
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
                                    <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Score</th>
                                    <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Result</th>
                                    <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                @foreach($sessions as $session)
                                    <tr class="hover:bg-slate-50/60 dark:hover:bg-white/5 transition-colors duration-150">
                                        <td class="px-5 py-3.5 text-sm font-mono text-slate-500 dark:text-slate-400">#{{ $session->id }}</td>
                                        <td class="px-5 py-3.5 text-sm font-mono text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::parse($session->flight_date)->format('d/m/y') }}</td>
                                        <td class="px-5 py-3.5 text-sm font-mono text-slate-700 dark:text-slate-300">{{ $session->formatted_duration }}</td>
                                        <td class="px-5 py-3.5 text-sm text-slate-700 dark:text-slate-300">{{ $session->aircraft_type ?? '-' }}</td>
                                        <td class="px-5 py-3.5 text-sm font-mono font-semibold text-slate-800 dark:text-slate-200">{{ $session->dssResult->total_score ?? 'N/A' }}</td>
                                        <td class="px-5 py-3.5">
                                            @if(optional($session->dssResult)->pass_fail === 'PASS')
                                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-bold rounded-full bg-emerald-100 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30">PASS</span>
                                            @elseif(optional($session->dssResult)->pass_fail === 'FAIL')
                                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-bold rounded-full bg-red-100 dark:bg-red-500/15 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-500/30">FAIL</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-bold rounded-full bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-white/10">N/A</span>
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

        </div>
    </div>

    <script>
        const profileImage = document.getElementById('profileImage');
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const closeModal = document.getElementById('closeModal');

        if (profileImage) {
            profileImage.addEventListener('click', () => {
                modal.classList.remove('hidden');
                modalImage.src = profileImage.src;
            });
        }

        closeModal.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
