<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">

            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-cyan-500/10 border border-cyan-500/30">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold tracking-widest text-slate-900 dark:text-white uppercase">
                    Flight Session Details
                </h2>
            </div>

            @php
                $backRoute = auth()->user()->role === 'admin'
                    ? route('pilots.show', $session->user_id)
                    : route('dashboard');
            @endphp

            <a href="{{ $backRoute }}"
                class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl
                    bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10
                    text-slate-700 dark:text-slate-300
                    border border-slate-200 dark:border-white/10
                    transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ===== SESSION INFORMATION ===== --}}
            <div class="rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">

                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-1 h-5 bg-cyan-400 rounded-full"></div>
                        <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                            Session Information
                        </h3>
                    </div>
                    <a href="{{ route('flight-sessions.report', $session->id) }}"
                        target="_blank"
                        class="cursor-pointer inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-xl
                            bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10
                            text-slate-700 dark:text-slate-300
                            border border-slate-200 dark:border-white/10
                            transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 9V2h12v7M6 18h12v4H6v-4zm-2-9h16a2 2 0 012 2v5H2v-5a2 2 0 012-2z"/>
                        </svg>
                        Print Report
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

                <div class="p-6 flex flex-col md:flex-row justify-between items-start gap-8">

                    {{-- LEFT INFO --}}
                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">

                        <div>
                            <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-0.5">Pilot</p>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $session->user->name ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-0.5">Session ID</p>
                            <p class="text-sm font-mono text-slate-800 dark:text-slate-200">#{{ $session->id }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-0.5">Flight Date</p>
                            <p class="text-sm font-mono text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($session->flight_date)->format('d/m/y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-0.5">Duration</p>
                            <p class="text-sm font-mono text-slate-800 dark:text-slate-200">{{ $session->formatted_duration }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-0.5">Start Time</p>
                            <p class="text-sm font-mono text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($session->start_time)->format('H:i:s') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-0.5">End Time</p>
                            <p class="text-sm font-mono text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($session->end_time)->format('H:i:s') }}</p>
                        </div>

                        {{-- AIRCRAFT DROPDOWN --}}
                        <form method="POST"
                            action="{{ route('flight-sessions.update-aircraft', $session->id) }}"
                            class="sm:col-span-2 flex items-center gap-3">
                            @csrf
                            @method('PATCH')

                            <label class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider whitespace-nowrap">Aircraft Type</label>

                            <select name="aircraft_type"
                                    onchange="this.form.submit()"
                                    class="cursor-pointer bg-white dark:bg-white/5 text-slate-800 dark:text-white
                                           border border-slate-200 dark:border-white/10
                                           rounded-lg px-3 py-2 text-sm w-52
                                           focus:outline-none focus:ring-2 focus:ring-cyan-500/40
                                           transition-colors duration-200">
                                <option value="X-Plane 11" {{ $session->aircraft_type == 'X-Plane 11' ? 'selected' : '' }}>Choose Aircraft</option>
                                <option value="Boeing 737-800" {{ $session->aircraft_type == 'Boeing 737-800' ? 'selected' : '' }}>Boeing 737-800</option>
                                <option value="Boeing 747-400" {{ $session->aircraft_type == 'Boeing 747-400' ? 'selected' : '' }}>Boeing 747-400</option>
                                <option value="MD-82" {{ $session->aircraft_type == 'MD-82' ? 'selected' : '' }}>MD-82</option>
                            </select>
                        </form>
                    </div>

                    {{-- RIGHT: Aircraft Image --}}
                    <div class="hidden md:flex items-center justify-end">
                        <img src="{{ asset('images/aircraft/' . $aircraftImage) }}"
                            class="w-80 h-44 object-contain drop-shadow-md translate-x-[-100px]"
                            alt="{{ $session->aircraft_type ?? 'Aircraft' }}">
                    </div>

                </div>
            </div>

            {{-- ===== DSS EVALUATION RESULT ===== --}}
            @if($session->dssResult)
                <div class="rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">

                    <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex items-center gap-2">
                        <div class="w-1 h-5 bg-cyan-400 rounded-full"></div>
                        <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                            DSS Evaluation Result
                        </h3>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-5">

                            <div class="p-4 rounded-xl bg-blue-50/80 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20">
                                <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-2">Control Smoothness</p>
                                <p class="font-mono text-2xl font-bold text-blue-700 dark:text-blue-300 tracking-widest">{{ $session->dssResult->control_smoothness_score }}</p>
                            </div>

                            <div class="p-4 rounded-xl bg-cyan-50/80 dark:bg-cyan-500/10 border border-cyan-100 dark:border-cyan-500/20">
                                <p class="text-xs font-semibold text-cyan-600 dark:text-cyan-400 uppercase tracking-wider mb-2">Altitude Accuracy</p>
                                <p class="font-mono text-2xl font-bold text-cyan-700 dark:text-cyan-300 tracking-widest">{{ $session->dssResult->altitude_accuracy_score }}</p>
                            </div>

                            <div class="p-4 rounded-xl bg-amber-50/80 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20">
                                <p class="text-xs font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wider mb-2">Airspeed Accuracy</p>
                                <p class="font-mono text-2xl font-bold text-amber-700 dark:text-amber-300 tracking-widest">{{ $session->dssResult->airspeed_accuracy_score }}</p>
                            </div>

                            <div class="p-4 rounded-xl bg-orange-50/80 dark:bg-orange-500/10 border border-orange-100 dark:border-orange-500/20">
                                <p class="text-xs font-semibold text-orange-600 dark:text-orange-400 uppercase tracking-wider mb-2">Safety Score</p>
                                <p class="font-mono text-2xl font-bold text-orange-700 dark:text-orange-300 tracking-widest">{{ $session->dssResult->safety_score }}</p>
                            </div>

                            <div class="p-4 rounded-xl bg-violet-50/80 dark:bg-violet-500/10 border border-violet-100 dark:border-violet-500/20">
                                <p class="text-xs font-semibold text-violet-600 dark:text-violet-400 uppercase tracking-wider mb-2">Total Score</p>
                                <p class="font-mono text-2xl font-bold text-violet-700 dark:text-violet-300 tracking-widest">{{ $session->dssResult->total_score }}</p>
                            </div>

                            <div class="p-4 rounded-xl {{ $session->dssResult->pass_fail === 'PASS'
                                ? 'bg-emerald-50/80 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20'
                                : 'bg-red-50/80 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20' }}">
                                <p class="text-xs font-semibold uppercase tracking-wider mb-2 {{ $session->dssResult->pass_fail === 'PASS' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">Result</p>
                                <p class="text-2xl font-bold {{ $session->dssResult->pass_fail === 'PASS' ? 'text-emerald-700 dark:text-emerald-300' : 'text-red-700 dark:text-red-300' }}">
                                    {{ $session->dssResult->pass_fail }}
                                </p>
                            </div>

                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-white/10">
                            <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Decision Reason</p>
                            <p class="text-sm text-slate-700 dark:text-slate-300">{{ $session->dssResult->decision_reason }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ===== FLIGHT EVENTS ===== --}}
            <div class="rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-5 bg-cyan-400 rounded-full"></div>
                    <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                        Flight Events
                    </h3>
                </div>

                <div class="flex gap-2.5 flex-wrap">
                    @if(optional($session->dssResult)->excessive_g_event)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-red-500 dark:bg-red-600 text-white">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Excessive G-Force
                        </span>
                    @endif

                    @if(optional($session->dssResult)->hard_landing_event)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-orange-500 dark:bg-orange-600 text-white">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Hard Landing
                        </span>
                    @endif

                    @if(optional($session->dssResult)->stall_event)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-500 text-black">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Stall Detected
                        </span>
                    @endif

                    @if(optional($session->dssResult)->unstable_flight_event)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-violet-600 text-white">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Unstable Flight
                        </span>
                    @endif

                    @if(optional($session->dssResult)->overbank_event)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-orange-600 text-white">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Overbank Warning
                        </span>
                    @endif

                    @if(optional($session->dssResult)->crash_event)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold text-white
                            {{ optional($session->dssResult)->crash_severity === 'Severe' ? 'bg-red-800' :
                            (optional($session->dssResult)->crash_severity === 'Major' ? 'bg-red-600' : 'bg-orange-600') }}">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Crash Detected
                            @if(optional($session->dssResult)->crash_severity)
                                — {{ optional($session->dssResult)->crash_severity }}
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
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-500 dark:bg-emerald-600 text-white">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            No Issues Detected
                        </span>
                    @endif
                </div>
            </div>

            {{-- ===== FLIGHT CHARTS ===== --}}
            <div class="rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">

                <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex items-center gap-2">
                    <div class="w-1 h-5 bg-cyan-400 rounded-full"></div>
                    <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                        Flight Charts
                    </h3>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="bg-slate-50/80 dark:bg-slate-900/50 rounded-xl border border-slate-100 dark:border-white/5 p-4 h-80">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-1.5">
                                <div class="w-0.5 h-4 bg-cyan-400 rounded-full"></div>
                                <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-200">Flight Performance</h4>
                            </div>
                            <button onclick="openChartModal('performanceChart', 'Flight Performance (Altitude + Airspeed)')"
                                    class="cursor-pointer px-2.5 py-1 text-xs font-semibold rounded-lg
                                        bg-slate-200 hover:bg-slate-300 dark:bg-white/10 dark:hover:bg-white/15
                                        text-slate-700 dark:text-slate-300 transition-colors duration-200">
                                Expand
                            </button>
                        </div>
                        <canvas id="performanceChart"></canvas>
                    </div>

                    <div class="bg-slate-50/80 dark:bg-slate-900/50 rounded-xl border border-slate-100 dark:border-white/5 p-4 h-80">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-1.5">
                                <div class="w-0.5 h-4 bg-cyan-400 rounded-full"></div>
                                <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-200">G-Force vs Time</h4>
                            </div>
                            <button onclick="openChartModal('gforceChart', 'G-Force vs Time')"
                                    class="cursor-pointer px-2.5 py-1 text-xs font-semibold rounded-lg
                                        bg-slate-200 hover:bg-slate-300 dark:bg-white/10 dark:hover:bg-white/15
                                        text-slate-700 dark:text-slate-300 transition-colors duration-200">
                                Expand
                            </button>
                        </div>
                        <canvas id="gforceChart"></canvas>
                    </div>

                    <div class="bg-slate-50/80 dark:bg-slate-900/50 rounded-xl border border-slate-100 dark:border-white/5 p-4 h-80">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-1.5">
                                <div class="w-0.5 h-4 bg-cyan-400 rounded-full"></div>
                                <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-200">Vertical Speed vs Time</h4>
                            </div>
                            <button onclick="openChartModal('verticalSpeedChart', 'Vertical Speed vs Time')"
                                    class="cursor-pointer px-2.5 py-1 text-xs font-semibold rounded-lg
                                        bg-slate-200 hover:bg-slate-300 dark:bg-white/10 dark:hover:bg-white/15
                                        text-slate-700 dark:text-slate-300 transition-colors duration-200">
                                Expand
                            </button>
                        </div>
                        <canvas id="verticalSpeedChart"></canvas>
                    </div>

                    <div class="bg-slate-50/80 dark:bg-slate-900/50 rounded-xl border border-slate-100 dark:border-white/5 p-4 h-80">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-1.5">
                                <div class="w-0.5 h-4 bg-cyan-400 rounded-full"></div>
                                <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-200">Control Inputs vs Time</h4>
                            </div>
                            <button onclick="openChartModal('controlInputChart', 'Control Inputs vs Time')"
                                    class="cursor-pointer px-2.5 py-1 text-xs font-semibold rounded-lg
                                        bg-slate-200 hover:bg-slate-300 dark:bg-white/10 dark:hover:bg-white/15
                                        text-slate-700 dark:text-slate-300 transition-colors duration-200">
                                Expand
                            </button>
                        </div>
                        <canvas id="controlInputChart"></canvas>
                    </div>

                    <div class="bg-slate-50/80 dark:bg-slate-900/50 rounded-xl border border-slate-100 dark:border-white/5 p-4 h-80">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-1.5">
                                <div class="w-0.5 h-4 bg-cyan-400 rounded-full"></div>
                                <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-200">Pitch vs Time</h4>
                            </div>
                            <button onclick="openChartModal('pitchChart', 'Pitch vs Time')"
                                    class="cursor-pointer px-2.5 py-1 text-xs font-semibold rounded-lg
                                        bg-slate-200 hover:bg-slate-300 dark:bg-white/10 dark:hover:bg-white/15
                                        text-slate-700 dark:text-slate-300 transition-colors duration-200">
                                Expand
                            </button>
                        </div>
                        <canvas id="pitchChart"></canvas>
                    </div>

                    <div class="bg-slate-50/80 dark:bg-slate-900/50 rounded-xl border border-slate-100 dark:border-white/5 p-4 h-80">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-1.5">
                                <div class="w-0.5 h-4 bg-cyan-400 rounded-full"></div>
                                <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-200">Roll vs Time</h4>
                            </div>
                            <button onclick="openChartModal('rollChart', 'Roll vs Time')"
                                    class="cursor-pointer px-2.5 py-1 text-xs font-semibold rounded-lg
                                        bg-slate-200 hover:bg-slate-300 dark:bg-white/10 dark:hover:bg-white/15
                                        text-slate-700 dark:text-slate-300 transition-colors duration-200">
                                Expand
                            </button>
                        </div>
                        <canvas id="rollChart"></canvas>
                    </div>

                </div>
            </div>

            {{-- Chart Expand Modal --}}
            <div id="chartModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center z-50">
                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200 dark:border-white/10 w-11/12 max-w-5xl p-6 relative">
                    <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100 dark:border-white/10">
                        <div class="flex items-center gap-2">
                            <div class="w-1 h-5 bg-cyan-400 rounded-full"></div>
                            <h3 id="modalTitle" class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider"></h3>
                        </div>
                        <button onclick="closeChartModal()"
                            class="cursor-pointer w-8 h-8 flex items-center justify-center rounded-lg
                                bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10
                                text-slate-600 dark:text-slate-300 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
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
