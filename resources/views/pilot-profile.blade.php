<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="hud-title animate-hud text-xl font-semibold">
                Pilot Profile
            </h2>

            @if(auth()->user()->role === 'admin')    
                <a href="{{ route('dashboard') }}"
                    class="px-4 py-2 text-sm font-medium tracking-wide bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white rounded transition">
                    ← Back to Dashboard
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

                {{-- Pilot Info Card --}}
                <div class="bg-white/80 dark:bg-white/5 backdrop-blur-md border border-white/40 dark:border-white/10 shadow-lg sm:rounded-xl p-6 h-full flex items-center gap-6 transition">
                    @if($pilot->profile_photo)
                        <img src="{{ asset('storage/' . $pilot->profile_photo) }}"
                            id="profileImage"
                            class="w-32 h-32 rounded-full object-cover object-center shadow-md cursor-pointer hover:scale-105 transition">
                    @else
                        <div class="w-32 h-32 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center shadow-md">
                            <img src="{{ asset('images/default_user.png') }}"
                                class="w-20 h-20 object-contain opacity-70 dark:opacity-80 filter grayscale dark:grayscale-0">
                        </div>
                    @endif

                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $pilot->name }}
                        </h3>

                        <p class="text-md text-gray-500 dark:text-gray-300 flex items-center gap-2">
                            Email: 
                            <span class="text-gray-400">{{ $pilot->email }}</span>
                        </p>

                        @if($pilot->phone_number)
                        <p class="text-md text-gray-400 flex items-center gap-2">
                            <span class="text-gray-500 dark:text-gray-300">Phone Number:</span>
                            {{ $pilot->phone_number }}
                        </p>
                        @endif

                        <p class="text-md text-gray-500 dark:text-gray-300 flex items-center gap-2">
                            Trend:
                            <span class="
                                {{ $trend == 'Improving' ? 'text-green-400' :
                                ($trend == 'Declining' ? 'text-red-400' : 'text-gray-400') }}">
                                {{ $trend }}
                            </span>
                        </p>
                    </div>
                </div>
                <!-- IMAGE PREVIEW MODAL -->
                <div id="imageModal"
                    class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center hidden z-50">

                    <span id="closeModal"
                        class="absolute top-6 right-8 text-white text-3xl cursor-pointer">&times;</span>

                    <img id="modalImage"
                        class="max-w-[90%] max-h-[90%] rounded-lg shadow-lg">
                </div>

                {{-- Performance Summary Cards --}}
                <div class="grid grid-cols-2 gap-4 h-full">
                    <div class="bg-blue-100 text-blue-800 p-4 rounded-lg shadow">
                        <p class="text-sm font-medium">Total Sessions</p>
                        <p class="text-2xl font-mono tracking-widest">{{ $totalSessions }}</p>
                    </div>

                    <div class="bg-purple-100 text-purple-800 p-4 rounded-lg shadow">
                        <p class="text-sm font-medium">Average Score</p>
                        <p class="text-2xl font-mono tracking-widest">{{ $averageScore }}</p>
                    </div>

                    <div class="bg-green-100 text-green-800 p-4 rounded-lg shadow">
                        <p class="text-sm font-medium">PASS Count</p>
                        <p class="text-2xl font-mono tracking-widest">{{ $passCount }}</p>
                    </div>

                    <div class="bg-red-100 text-red-800 p-4 rounded-lg shadow">
                        <p class="text-sm font-medium">FAIL Count</p>
                        <p class="text-2xl font-mono tracking-widest">{{ $failCount }}</p>
                    </div>
                </div>

            </div>

            <div class="bg-white/80 dark:bg-white/5 backdrop-blur-md border border-white/40 dark:border-white/10 shadow-lg sm:rounded-xl p-6 transition">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                        Flight Sessions
                    </h3>
                </div>

                <div class="mb-6">
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
                                    borderColor: '#3b82f6',
                                    backgroundColor: '#3b82f6',
                                    tension: 0.3,
                                    pointRadius: scores.map((_, i) => badFlags[i] ? 6 : 4),
                                    pointHoverRadius: scores.map((_, i) => badFlags[i] ? 7 : 5),
                                    pointBackgroundColor: scores.map((_, i) => badFlags[i] ? '#ef4444' : '#3b82f6'),
                                    pointBorderColor: scores.map((_, i) => badFlags[i] ? '#ef4444' : '#3b82f6'),
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
                                        <td class="px-4 py-3 text-sm font-mono text-gray-900 dark:text-gray-100">{{ $session->id }}</td>
                                        <td class="px-4 py-3 text-sm font-mono text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($session->flight_date)->format('d/m/y') }}</td>
                                        <td class="px-4 py-3 text-sm font-mono text-gray-900 dark:text-gray-100">{{ $session->duration_sec ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $session->aircraft_type ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm font-mono text-gray-900 dark:text-gray-100">{{ $session->dssResult->total_score ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            @if(optional($session->dssResult)->pass_fail === 'PASS')
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">PASS</span>
                                            @elseif(optional($session->dssResult)->pass_fail === 'FAIL')
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">FAIL</span>
                                            @else
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">N/A</span>
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