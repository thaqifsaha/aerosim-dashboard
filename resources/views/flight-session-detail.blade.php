<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="hud-title animate-hud text-xl font-semibold">
                Flight Session Details
            </h2>

            @php
                $backRoute = auth()->user()->role === 'admin'
                    ? route('pilots.show', $session->user_id)
                    : route('dashboard');
            @endphp

            <a href="{{ $backRoute }}"
            class="px-4 py-2
                text-sm font-medium tracking-wide
                bg-gray-200 hover:bg-gray-300 text-gray-800
                dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white
                rounded-md transition">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="relative bg-white/80 dark:bg-white/5 backdrop-blur-md border border-white/40 dark:border-white/10 shadow-lg sm:rounded-xl p-6 transition">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">
                        Session Information
                    </h3>
                    <a href="{{ route('flight-sessions.report', $session->id) }}"
                        target="_blank"
                        class="flex flex-col items-center justify-center w-20 h-20
                                text-sm font-medium tracking-wide
                                bg-gray-200 hover:bg-gray-300 text-gray-800
                                dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white
                                rounded-md border border-gray-300 dark:border-gray-600
                                shadow transition">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 9V2h12v7M6 18h12v4H6v-4zm-2-9h16a2 2 0 012 2v5H2v-5a2 2 0 012-2z"/>
                            </svg>
                        <span class="text-xs mt-1">Print</span>
                    </a>
                </div>

                @php
                    $aircraftImages = [
                        'X-Plane 11' => 'X-Plane.png',
                        'Boeing 737-800' => 'boeing737.png',
                        'Boeing 747-400' => 'boeing747.png',
                        'MD-82' => 'md82.png',
                    ];

                    $aircraftImage = $aircraftImages[$session->aircraft_type] ?? 'default.jpg';
                @endphp

                <div class="flex justify-between items-start gap-8">

                    {{-- LEFT INFO --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-m text-gray-500 dark:text-gray-300 flex-1 items-center gap-2 ">
                        <p><strong>Pilot:</strong> {{ $session->user->name ?? 'Unknown' }}</p>
                        <p><strong>Session ID:</strong> <span class="font-mono">{{ $session->id }}</span></p>

                        <p><strong>Flight Date:</strong> <span class="font-mono">{{ \Carbon\Carbon::parse($session->flight_date)->format('d/m/y') }}</span></p>
                        <p><strong>Duration:</strong> <span class="font-mono">{{ $session->formatted_duration }}</span></p>

                        <p><strong>Start Time:</strong> <span class="font-mono">{{ \Carbon\Carbon::parse($session->start_time)->format('H:i:s') }}</span></p>
                        <p><strong>End Time:</strong> <span class="font-mono">{{ \Carbon\Carbon::parse($session->end_time)->format('H:i:s') }}</span></p>

                        {{-- AIRCRAFT DROPDOWN --}}
                        <form method="POST"
                            action="{{ route('flight-sessions.update-aircraft', $session->id) }}"
                            class="md:col-span-2 flex items-center gap-3">
                            @csrf
                            @method('PATCH')

                            <label class="font-bold whitespace-nowrap">Aircraft Type:</label>

                            <select name="aircraft_type"
                                    onchange="this.form.submit()"
                                    class="bg-white text-gray-800 border border-gray-300 
                                           dark:bg-gray-700 dark:text-white dark:border-gray-600 
                                           rounded px-3 py-2 text-sm w-52 transition">
                                <option value="X-Plane 11" {{ $session->aircraft_type == 'X-Plane 11' ? 'selected' : '' }}>Choose Aircraft</option>
                                <option value="Boeing 737-800" {{ $session->aircraft_type == 'Boeing 737-800' ? 'selected' : '' }}>Boeing 737-800</option>
                                <option value="Boeing 747-400" {{ $session->aircraft_type == 'Boeing 747-400' ? 'selected' : '' }}>Boeing 747-400</option>
                                <option value="MD-82" {{ $session->aircraft_type == 'MD-82' ? 'selected' : '' }}>MD-82</option>
                            </select>
                        </form>
                    </div>

                    {{-- RIGHT IMAGE + PRINT --}}
                    <div class="flex items-start gap-6">
                        <img src="{{ asset('images/aircraft/' . $aircraftImage) }}"
                            class="w-80 h-44 object-contain drop-shadow-md translate-x-[-100px]">
                    </div>

                </div>
            </div>

            @if($session->dssResult)
                <div class="bg-white/80 dark:bg-white/5 backdrop-blur-md border border-white/40 dark:border-white/10 shadow-lg sm:rounded-xl p-6 transition">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">
                        DSS Evaluation Result
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 rounded-lg bg-blue-100 text-blue-800">
                            <p class="text-sm">Control Smoothness</p>
                            <p class="font-mono text-xl tracking-widest">{{ $session->dssResult->control_smoothness_score }}</p>
                        </div>
                        <div class="p-4 rounded-lg bg-green-100 text-green-800">
                            <p class="text-sm">Altitude Accuracy</p>
                            <p class="font-mono text-xl tracking-widest">{{ $session->dssResult->altitude_accuracy_score }}</p>
                        </div>
                        <div class="p-4 rounded-lg bg-yellow-100 text-yellow-800">
                            <p class="text-sm">Airspeed Accuracy</p>
                            <p class="font-mono text-xl tracking-widest">{{ $session->dssResult->airspeed_accuracy_score }}</p>
                        </div>
                        <div class="p-4 rounded-lg bg-red-100 text-red-800">
                            <p class="text-sm">Safety Score</p>
                            <p class="font-mono text-xl tracking-widest">{{ $session->dssResult->safety_score }}</p>
                        </div>
                        <div class="p-4 rounded-lg bg-purple-100 text-purple-800">
                            <p class="text-sm">Total Score</p>
                            <p class="font-mono text-xl tracking-widest">{{ $session->dssResult->total_score }}</p>
                        </div>
                        <div class="p-4 rounded-lg {{ $session->dssResult->pass_fail === 'PASS' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <p class="text-sm">Result</p>
                            <p class="text-2xl font-bold">{{ $session->dssResult->pass_fail }}</p>
                        </div>
                    </div>

                    <div class="mt-6 text-sm text-gray-900 dark:text-gray-100">
                        <p><strong>Decision Reason:</strong> {{ $session->dssResult->decision_reason }}</p>
                    </div>
                </div>
            @endif

            <div class="mt-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Flight Events</h3>

                <div class="flex gap-3 flex-wrap">
                    @if(optional($session->dssResult)->excessive_g_event)
                        <span class="px-3 py-1 bg-red-500 text-white rounded-full">
                            ⚠️ Excessive G-Force
                        </span>
                    @endif

                    @if(optional($session->dssResult)->hard_landing_event)
                        <span class="px-3 py-1 bg-orange-500 text-white rounded-full">
                            ⚠️ Hard Landing
                        </span>
                    @endif

                    @if(optional($session->dssResult)->stall_event)
                        <span class="px-3 py-1 bg-yellow-500 text-black rounded-full">
                            ⚠️ Stall Detected
                        </span>
                    @endif

                    @if(optional($session->dssResult)->unstable_flight_event)
                        <span class="px-3 py-1 bg-purple-600 text-white rounded-full">
                            ⚠️ Unstable Flight
                        </span>
                    @endif

                    @if(optional($session->dssResult)->overbank_event)
                        <span class="px-3 py-1 bg-orange-600 text-white rounded-full">
                            ⚠️ Overbank Warning
                        </span>
                    @endif

                    @if(optional($session->dssResult)->crash_event)
                        <span class="px-3 py-1 rounded-full text-white
                            {{ optional($session->dssResult)->crash_severity === 'Severe' ? 'bg-red-800' :
                            (optional($session->dssResult)->crash_severity === 'Major' ? 'bg-red-600' : 'bg-orange-600') }}">
                            ⚠️ Crash Detected
                            @if(optional($session->dssResult)->crash_severity)
                                - {{ optional($session->dssResult)->crash_severity }}
                            @endif
                        </span>
                    @endif

                    @if(
                        !optional($session->dssResult)->excessive_g_event &&
                        !optional($session->dssResult)->hard_landing_event &&
                        !optional($session->dssResult)->stall_event &&
                        !optional($session->dssResult)->crash_event &&
                        !optional($session->dssResult)->unstable_flight_event &&
                        !optional($session->dssResult)->overbank_event
                    )
                        <span class="px-3 py-1 bg-green-500 text-white rounded-full">
                            ✅ No Issues Detected
                        </span>
                    @endif
                </div>
            </div>

            <div class="bg-white/80 dark:bg-white/5 backdrop-blur-md border border-white/40 dark:border-white/10 shadow-lg sm:rounded-xl p-6 transition">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6">
                    Flight Charts
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg shadow h-80">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">
                                Flight Performance
                            </h4>
                            <button onclick="openChartModal('performanceChart', 'Flight Performance (Altitude + Airspeed)')"
                                    class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                Expand
                            </button>
                        </div>
                        <canvas id="performanceChart"></canvas>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg shadow h-80">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">
                                G-Force vs Time
                            </h4>
                            <button onclick="openChartModal('gforceChart', 'G-Force vs Time')"
                                    class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                Expand
                            </button>
                        </div>
                        <canvas id="gforceChart"></canvas>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg shadow h-80">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">
                                Vertical Speed vs Time
                            </h4>
                            <button onclick="openChartModal('verticalSpeedChart', 'Vertical Speed vs Time')"
                                    class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                Expand
                            </button>
                        </div>
                        <canvas id="verticalSpeedChart"></canvas>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg shadow h-80">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">
                                Control Inputs vs Time
                            </h4>
                            <button onclick="openChartModal('controlInputChart', 'Control Inputs vs Time')"
                                    class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                Expand
                            </button>
                        </div>
                        <canvas id="controlInputChart"></canvas>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg shadow h-80">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">
                                Pitch vs Time
                            </h4>
                            <button onclick="openChartModal('pitchChart', 'Pitch vs Time')" 
                                    class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                Expand
                            </button>
                        </div>
                        <canvas id="pitchChart"></canvas>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg shadow h-80">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">
                                Roll vs Time
                            </h4>
                            <button onclick="openChartModal('rollChart', 'Roll vs Time')" 
                                    class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                Expand
                            </button>
                        </div>
                        <canvas id="rollChart"></canvas>
                    </div>
                </div>
            </div>

            <div id="chartModal" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-11/12 max-w-5xl p-6 relative">
                    <div class="flex justify-between items-center mb-4">
                        <h3 id="modalTitle" class="text-lg font-bold text-gray-900 dark:text-gray-100"></h3>
                        <button onclick="closeChartModal()" class="text-gray-600 dark:text-gray-300 text-xl font-bold">
                            &times;
                        </button>
                    </div>

                    <div class="relative h-[500px]">
                        <canvas id="modalChart"></canvas>
                    </div>
                </div>
            </div>

            @include('partials.flight-data-records', ['flightData' => $flightData])

        </div>
    </div>
</x-app-layout>
<script>
    const flightData = @json($chartData);

    const timestamps = flightData.map(d => d.timestamp_sec);
    const altitudeData = flightData.map(d => d.altitude);
    const airspeedData = flightData.map(d => d.indicated_airspeed);
    const gForceData = flightData.map(d => d.g_force);

    const verticalSpeedData = flightData.map(d => d.vertical_speed);
    const elevatorData = flightData.map(d => d.elevator_deflection);
    const aileronData = flightData.map(d => d.aileron_deflection);
    const rudderData = flightData.map(d => d.rudder_deflection);

    const pitchData = flightData.map(d => d.pitch);
    const rollData = flightData.map(d => d.roll);
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const chartInstances = {};

    chartInstances.performanceChart = new Chart(document.getElementById('performanceChart'), {
        type: 'line',
        data: {
            labels: timestamps,
            datasets: [
                {
                    label: 'Altitude',
                    data: altitudeData,
                    borderWidth: 2,
                    tension: 0.3,
                    yAxisID: 'y'
                },
                {
                    label: 'Airspeed',
                    data: airspeedData,
                    borderWidth: 2,
                    tension: 0.3,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Time (sec)'
                    }
                },
                y: {
                    type: 'linear',
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Altitude'
                    }
                },
                y1: {
                    type: 'linear',
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Airspeed'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });

    chartInstances.gforceChart = new Chart(document.getElementById('gforceChart'), {
        type: 'line',
        data: {
            labels: timestamps,
            datasets: [{
                label: 'G-Force',
                data: gForceData,
                borderWidth: 2,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Time (sec)'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'G-Force'
                    }
                }
            }
        }
    });

    chartInstances.verticalSpeedChart = new Chart(document.getElementById('verticalSpeedChart'), {
        type: 'line',
        data: {
            labels: timestamps,
            datasets: [{
                label: 'Vertical Speed',
                data: verticalSpeedData,
                borderWidth: 2,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Time (sec)'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Vertical Speed'
                    }
                }
            }
        }
    });

    chartInstances.controlInputChart = new Chart(document.getElementById('controlInputChart'), {
        type: 'line',
        data: {
            labels: timestamps,
            datasets: [
                {
                    label: 'Elevator',
                    data: elevatorData,
                    borderWidth: 2,
                    tension: 0.3
                },
                {
                    label: 'Aileron',
                    data: aileronData,
                    borderWidth: 2,
                    tension: 0.3
                },
                {
                    label: 'Rudder',
                    data: rudderData,
                    borderWidth: 2,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Time (sec)'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Control Deflection'
                    }
                }
            }
        }
    });

    chartInstances.pitchChart = new Chart(document.getElementById('pitchChart'), {
        type: 'line',
        data: {
            labels: timestamps,
            datasets: [
                {
                    label: 'Pitch',
                    data: pitchData,
                    borderWidth: 2,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Time (sec)'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Pitch'
                    }
                }
            }
        }
    });

    chartInstances.rollChart = new Chart(document.getElementById('rollChart'), {
        type: 'line',
        data: {
            labels: timestamps,
            datasets: [
                {
                    label: 'Roll',
                    data: rollData,
                    borderWidth: 2,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Time (sec)'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Roll'
                    }
                }
            }
        }
    });

    let modalChartInstance = null;

    function openChartModal(chartKey, title) {
        document.getElementById('modalTitle').innerText = title;
        document.getElementById('chartModal').classList.remove('hidden');
        document.getElementById('chartModal').classList.add('flex');

        const modalCanvas = document.getElementById('modalChart');

        if (modalChartInstance) {
            modalChartInstance.destroy();
        }

        let chartConfig = null;

        if (chartKey === 'performanceChart') {
            chartConfig = {
                type: 'line',
                data: {
                    labels: timestamps,
                    datasets: [
                        {
                            label: 'Altitude',
                            data: altitudeData,
                            borderWidth: 2,
                            tension: 0.3,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Airspeed',
                            data: airspeedData,
                            borderWidth: 2,
                            tension: 0.3,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Time (sec)'
                            }
                        },
                        y: {
                            type: 'linear',
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Altitude'
                            }
                        },
                        y1: {
                            type: 'linear',
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Airspeed'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            };
        }

        if (chartKey === 'gforceChart') {
            chartConfig = {
                type: 'line',
                data: {
                    labels: timestamps,
                    datasets: [{
                        label: 'G-Force',
                        data: gForceData,
                        borderWidth: 2,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Time (sec)'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'G-Force'
                            }
                        }
                    }
                }
            };
        }

        if (chartKey === 'verticalSpeedChart') {
            chartConfig = {
                type: 'line',
                data: {
                    labels: timestamps,
                    datasets: [{
                        label: 'Vertical Speed',
                        data: verticalSpeedData,
                        borderWidth: 2,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Time (sec)'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Vertical Speed'
                            }
                        }
                    }
                }
            };
        }

        if (chartKey === 'controlInputChart') {
            chartConfig = {
                type: 'line',
                data: {
                    labels: timestamps,
                    datasets: [
                        {
                            label: 'Elevator',
                            data: elevatorData,
                            borderWidth: 2,
                            tension: 0.3
                        },
                        {
                            label: 'Aileron',
                            data: aileronData,
                            borderWidth: 2,
                            tension: 0.3
                        },
                        {
                            label: 'Rudder',
                            data: rudderData,
                            borderWidth: 2,
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Time (sec)'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Control Deflection'
                            }
                        }
                    }
                }
            };
        }

        if (chartKey === 'pitchChart') {
        chartConfig = {
            type: 'line',
            data: {
                labels: timestamps,
                datasets: [{
                    label: 'Pitch',
                    data: pitchData,
                    borderWidth: 2,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Time (sec)'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Pitch (deg)'
                        }
                    }
                }
            }
        };
    }

    if (chartKey === 'rollChart') {
        chartConfig = {
            type: 'line',
            data: {
                labels: timestamps,
                datasets: [{
                    label: 'Roll',
                    data: rollData,
                    borderWidth: 2,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Time (sec)'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Roll (deg)'
                        }
                    }
                }
            }
        };
    }

        modalChartInstance = new Chart(modalCanvas, chartConfig);
    }

    function closeChartModal() {
        document.getElementById('chartModal').classList.add('hidden');
        document.getElementById('chartModal').classList.remove('flex');

        if (modalChartInstance) {
            modalChartInstance.destroy();
            modalChartInstance = null;
        }
    }
</script>

<script>
document.addEventListener('click', async function (e) {
    const link = e.target.closest('.flight-data-page');
    if (!link) return;

    e.preventDefault();

    try {
        const response = await fetch(link.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const html = await response.text();

        const oldCard = document.getElementById('flight-data-records-card');
        oldCard.outerHTML = html;

        // wait for DOM to update
        setTimeout(() => {
            const newCard = document.getElementById('flight-data-records-card');
            if (newCard) {
                newCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 50);
    } catch (error) {
        console.error('Failed to load paginated flight data:', error);
    }
});
</script>
