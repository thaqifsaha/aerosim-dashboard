<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-['Montserrat'] text-xl font-bold tracking-wide text-slate-800 dark:text-white">
                {{ auth()->user()->role === 'admin' ? 'Flight Schedule Management' : 'Book Flight Session' }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="flex items-center gap-3 rounded-xl border border-emerald-200/60 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-950/40 dark:text-emerald-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="flex items-start gap-3 rounded-xl border border-red-200/60 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/20 dark:bg-red-950/40 dark:text-red-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ───── PILOT: Upcoming sessions ───── --}}
            @if(auth()->user()->role === 'pilot')
                <div class="rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex items-center gap-2">
                        <div class="w-1 h-5 bg-cyan-400 rounded-full"></div>
                        <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Your Upcoming Sessions</h3>
                    </div>

                    @if($upcomingSchedules->isEmpty())
                        <div class="px-6 py-8 text-center">
                            <p class="text-sm text-slate-500 dark:text-slate-400">No upcoming sessions found.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-slate-50/80 dark:bg-slate-800/60">
                                    <tr>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Date</th>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Time</th>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Aircraft</th>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Notes</th>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                    @foreach($upcomingSchedules as $schedule)
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-white/5 transition-colors duration-150">
                                            <td class="px-5 py-3 font-mono text-sm text-slate-700 dark:text-slate-300">{{ $schedule->scheduled_date->format('d M Y') }}</td>
                                            <td class="px-5 py-3 font-mono text-sm text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::parse($schedule->scheduled_time)->format('g:i A') }}</td>
                                            <td class="px-5 py-3 text-sm text-slate-700 dark:text-slate-300">{{ $schedule->aircraft_type }}</td>
                                            <td class="px-5 py-3 text-sm">
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-700 dark:bg-cyan-500/15 dark:text-cyan-300">
                                                    {{ ucfirst($schedule->status) }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-3 text-sm text-slate-600 dark:text-slate-400">{{ $schedule->notes ?: '-' }}</td>
                                            <td class="px-5 py-3 text-sm">
                                                <div class="flex flex-wrap gap-2">
                                                    <a href="{{ route('flight-schedules.edit', $schedule) }}"
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold
                                                            bg-slate-100 hover:bg-slate-200 text-slate-700
                                                            dark:bg-white/5 dark:hover:bg-white/10 dark:text-slate-300
                                                            border border-slate-200 dark:border-white/10 transition cursor-pointer">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    <form method="POST" action="{{ route('flight-schedules.cancel', $schedule) }}" onsubmit="return confirm('Cancel this scheduled session?');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold
                                                            bg-red-50 hover:bg-red-100 text-red-600
                                                            dark:bg-red-500/10 dark:hover:bg-red-500/20 dark:text-red-400
                                                            border border-red-200 dark:border-red-500/20 transition cursor-pointer">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                            </svg>
                                                            Cancel
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- ───── PILOT: Book new session ───── --}}
                <div class="rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex items-center gap-2">
                        <div class="w-1 h-5 bg-cyan-400 rounded-full"></div>
                        <div>
                            <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Book a New Session</h3>
                            <p class="mt-0.5 text-xs text-slate-400 dark:text-slate-500">Fridays and already-booked dates are unavailable.</p>
                        </div>
                    </div>

                    <div class="px-6 py-6">
                        <form method="POST" action="{{ route('flight-schedules.store') }}" class="space-y-6">
                            @csrf

                            <div x-data="aircraftTypeDropdown()" @click.outside="open = false" class="relative">
                                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Aircraft Type</label>
                                <input type="hidden" name="aircraft_type" :value="selectedId">

                                <button type="button" @click="open = !open"
                                    class="w-full rounded-lg border border-slate-200 dark:border-white/10
                                        bg-white dark:bg-slate-800
                                        px-3 py-2.5 text-sm
                                        flex items-center justify-between gap-2
                                        focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-400
                                        transition cursor-pointer"
                                    :class="selectedId ? 'text-slate-800 dark:text-slate-100' : 'text-slate-400 dark:text-slate-500'">
                                    <span x-text="selectedLabel" class="truncate text-left"></span>
                                    <svg class="w-4 h-4 shrink-0 text-slate-400 transition-transform duration-200"
                                         :class="open ? 'rotate-180' : ''"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute top-full left-0 z-50 mt-1 w-full rounded-lg border border-slate-200 dark:border-white/10
                                        bg-white dark:bg-slate-800 shadow-xl overflow-hidden">
                                    <div class="overflow-y-auto max-h-48">
                                        <template x-for="option in options" :key="option.id">
                                            <div @click="select(option)"
                                                class="px-3 py-2.5 text-sm cursor-pointer transition-colors
                                                    text-slate-700 dark:text-slate-200
                                                    hover:bg-slate-50 dark:hover:bg-white/10"
                                                :class="option.id === selectedId
                                                    ? 'bg-cyan-50 dark:bg-cyan-500/10 !text-cyan-700 dark:!text-cyan-300 font-medium'
                                                    : ''">
                                                <span x-text="option.label"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Booking Date</label>
                                <input type="hidden" id="scheduled_date" name="scheduled_date" value="{{ old('scheduled_date') }}">

                                <div class="rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50/80 dark:bg-slate-900/50 p-4">
                                    <div class="mb-4 flex items-center justify-between">
                                        <button type="button" id="calendar-prev"
                                            class="rounded-lg px-3 py-1.5 text-sm font-semibold text-slate-600 dark:text-slate-300
                                                hover:bg-slate-100 dark:hover:bg-white/5 transition cursor-pointer">←</button>
                                        <p id="calendar-title" class="text-sm font-bold text-slate-800 dark:text-slate-200 tracking-wide"></p>
                                        <button type="button" id="calendar-next"
                                            class="rounded-lg px-3 py-1.5 text-sm font-semibold text-slate-600 dark:text-slate-300
                                                hover:bg-slate-100 dark:hover:bg-white/5 transition cursor-pointer">→</button>
                                    </div>

                                    <div class="grid grid-cols-7 gap-1.5 text-center text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">
                                        <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span class="text-red-400">Fri</span><span>Sat</span>
                                    </div>
                                    <div id="calendar-grid" class="grid grid-cols-7 gap-1.5"></div>
                                </div>

                                <p id="calendar-selection" class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                    Select an available date.
                                </p>

                                <div class="mt-3 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50/80 dark:bg-slate-900/50 p-4">
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">Availability Guide</p>
                                    <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-600 dark:text-slate-400">
                                        <div class="flex items-center gap-2">
                                            <span class="h-3.5 w-3.5 rounded bg-slate-200 dark:bg-slate-700"></span>
                                            <span class="text-xs">Unavailable date</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="h-3.5 w-3.5 rounded bg-cyan-500"></span>
                                            <span class="text-xs">Selected date</span>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-500">
                                        Unavailable dates show "Already booked" or "Closed on Friday".
                                    </p>
                                </div>
                            </div>

                            <div>
                                <label for="scheduled_time" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Booking Time</label>
                                <input type="time" id="scheduled_time" name="scheduled_time" value="{{ old('scheduled_time') }}" min="11:00" max="20:00" required
                                    class="w-full rounded-lg border border-slate-200 dark:border-white/10
                                        bg-white/80 dark:bg-white/5
                                        text-slate-800 dark:text-white
                                        px-3 py-2.5 text-sm
                                        focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-400
                                        transition">
                                <p class="mt-1.5 text-xs text-slate-400 dark:text-slate-500">Available booking hours: 11:00 AM – 8:00 PM.</p>
                            </div>

                            <div>
                                <label for="notes" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Notes</label>
                                <textarea id="notes" name="notes" rows="4"
                                    class="w-full rounded-lg border border-slate-200 dark:border-white/10
                                        bg-white/80 dark:bg-white/5
                                        text-slate-800 dark:text-slate-200
                                        px-3 py-2.5 text-sm
                                        focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-400
                                        transition resize-none">{{ old('notes') }}</textarea>
                            </div>

                            <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg
                                    text-xs font-bold uppercase tracking-wider
                                    bg-cyan-500 hover:bg-cyan-400 text-white
                                    shadow-md shadow-cyan-500/20 hover:shadow-cyan-400/30
                                    transition-all duration-200 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Book Session
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- ───── ADMIN: Upcoming sessions ───── --}}
            @if(auth()->user()->role === 'admin')
                <div class="rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex items-center gap-2">
                        <div class="w-1 h-5 bg-cyan-400 rounded-full"></div>
                        <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Upcoming Scheduled Sessions</h3>
                    </div>

                    @if($upcomingSchedules->isEmpty())
                        <div class="px-6 py-8 text-center">
                            <p class="text-sm text-slate-500 dark:text-slate-400">No upcoming sessions found.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-slate-50/80 dark:bg-slate-800/60">
                                    <tr>
                                        @if(auth()->user()->role === 'admin')
                                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pilot</th>
                                        @endif
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Date</th>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Time</th>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Aircraft</th>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Notes</th>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                    @foreach($upcomingSchedules as $schedule)
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-white/5 transition-colors duration-150">
                                            @if(auth()->user()->role === 'admin')
                                                <td class="px-5 py-3 text-sm font-medium text-slate-700 dark:text-slate-300">{{ $schedule->user?->name ?? '-' }}</td>
                                            @endif
                                            <td class="px-5 py-3 font-mono text-sm text-slate-700 dark:text-slate-300">{{ $schedule->scheduled_date->format('d M Y') }}</td>
                                            <td class="px-5 py-3 font-mono text-sm text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::parse($schedule->scheduled_time)->format('g:i A') }}</td>
                                            <td class="px-5 py-3 text-sm text-slate-700 dark:text-slate-300">{{ $schedule->aircraft_type }}</td>
                                            <td class="px-5 py-3 text-sm">
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-700 dark:bg-cyan-500/15 dark:text-cyan-300">
                                                    {{ ucfirst($schedule->status) }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-3 text-sm text-slate-600 dark:text-slate-400">{{ $schedule->notes ?: '-' }}</td>
                                            <td class="px-5 py-3 text-sm">
                                                <div class="flex flex-wrap gap-2">
                                                    <a href="{{ route('flight-schedules.edit', $schedule) }}"
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold
                                                            bg-slate-100 hover:bg-slate-200 text-slate-700
                                                            dark:bg-white/5 dark:hover:bg-white/10 dark:text-slate-300
                                                            border border-slate-200 dark:border-white/10 transition cursor-pointer">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    <form method="POST" action="{{ route('flight-schedules.cancel', $schedule) }}" onsubmit="return confirm('Cancel this scheduled session?');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold
                                                            bg-red-50 hover:bg-red-100 text-red-600
                                                            dark:bg-red-500/10 dark:hover:bg-red-500/20 dark:text-red-400
                                                            border border-red-200 dark:border-red-500/20 transition cursor-pointer">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                            </svg>
                                                            Cancel
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- ───── ADMIN: Cancelled sessions ───── --}}
                <div class="rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex items-center gap-2">
                        <div class="w-1 h-5 bg-red-400 rounded-full"></div>
                        <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Recently Cancelled Sessions</h3>
                    </div>

                    @if($cancelledSchedules->isEmpty())
                        <div class="px-6 py-8 text-center">
                            <p class="text-sm text-slate-500 dark:text-slate-400">No cancelled sessions yet.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-slate-50/80 dark:bg-slate-800/60">
                                    <tr>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pilot</th>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Date</th>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Time</th>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Aircraft</th>
                                        <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                    @foreach($cancelledSchedules as $schedule)
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-white/5 transition-colors duration-150">
                                            <td class="px-5 py-3 text-sm font-medium text-slate-700 dark:text-slate-300">{{ $schedule->user?->name ?? '-' }}</td>
                                            <td class="px-5 py-3 font-mono text-sm text-slate-700 dark:text-slate-300">{{ $schedule->scheduled_date->format('d M Y') }}</td>
                                            <td class="px-5 py-3 font-mono text-sm text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::parse($schedule->scheduled_time)->format('g:i A') }}</td>
                                            <td class="px-5 py-3 text-sm text-slate-700 dark:text-slate-300">{{ $schedule->aircraft_type }}</td>
                                            <td class="px-5 py-3 text-sm">
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400">
                                                    {{ ucfirst($schedule->status) }}
                                                </span>
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

    @if(auth()->user()->role === 'pilot')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const bookedDates = new Set(@json($bookedDates));
                const hiddenInput = document.getElementById('scheduled_date');
                const grid = document.getElementById('calendar-grid');
                const title = document.getElementById('calendar-title');
                const selection = document.getElementById('calendar-selection');
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                let currentMonth = hiddenInput.value ? new Date(hiddenInput.value + 'T00:00:00') : new Date();
                currentMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), 1);

                function formatDate(date) {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                }

                function renderCalendar() {
                    grid.innerHTML = '';
                    title.textContent = currentMonth.toLocaleDateString(undefined, { month: 'long', year: 'numeric' });

                    const firstDay = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), 1);
                    const lastDay = new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1, 0);

                    for (let i = 0; i < firstDay.getDay(); i++) {
                        grid.appendChild(document.createElement('div'));
                    }

                    for (let day = 1; day <= lastDay.getDate(); day++) {
                        const date = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), day);
                        const dateValue = formatDate(date);
                        const isPast = date < today;
                        const isFriday = date.getDay() === 5;
                        const isBooked = bookedDates.has(dateValue);
                        const unavailable = isPast || isFriday || isBooked;

                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'min-h-16 rounded-lg border px-2 py-2 text-sm transition';

                        if (unavailable) {
                            button.disabled = true;
                            button.className += ' cursor-not-allowed border-gray-200 bg-gray-200 text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-500';
                            button.innerHTML = `<span class="block font-semibold">${day}</span><span class="mt-1 block text-[10px]">${isPast ? 'Past date' : (isFriday ? 'Closed on Friday' : 'Already booked')}</span>`;
                        } else {
                            button.className += hiddenInput.value === dateValue
                                ? ' border-cyan-500 bg-cyan-500 text-white'
                                : ' border-gray-200 bg-white text-gray-800 hover:border-cyan-400 hover:bg-cyan-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 dark:hover:bg-gray-800';
                            button.textContent = day;
                            button.addEventListener('click', function () {
                                hiddenInput.value = dateValue;
                                selection.textContent = `Selected date: ${date.toLocaleDateString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}`;
                                renderCalendar();
                            });
                        }

                        grid.appendChild(button);
                    }
                }

                document.getElementById('calendar-prev').addEventListener('click', function () {
                    currentMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth() - 1, 1);
                    renderCalendar();
                });

                document.getElementById('calendar-next').addEventListener('click', function () {
                    currentMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1, 1);
                    renderCalendar();
                });

                if (hiddenInput.value) {
                    const selectedDate = new Date(hiddenInput.value + 'T00:00:00');
                    selection.textContent = `Selected date: ${selectedDate.toLocaleDateString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}`;
                }

                renderCalendar();
            });
        </script>
    @endif

<script>
function aircraftTypeDropdown() {
    const currentId = @json(old('aircraft_type') ?? '');
    const options = [
        { id: 'Boeing 737-800', label: 'Boeing 737-800' },
        { id: 'Boeing 747-400', label: 'Boeing 747-400' },
        { id: 'MD-82',          label: 'MD-82' },
    ];
    const found = options.find(o => o.id === currentId);
    return {
        open:          false,
        selectedId:    currentId,
        selectedLabel: found ? found.label : 'Select aircraft type',
        options:       options,
        select(option) {
            this.selectedId    = option.id;
            this.selectedLabel = option.label;
            this.open          = false;
        },
    };
}
</script>
</x-app-layout>
