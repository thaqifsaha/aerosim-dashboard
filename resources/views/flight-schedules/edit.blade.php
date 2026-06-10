<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-['Montserrat'] text-xl font-bold tracking-wide text-slate-800 dark:text-white">
                Edit Flight Schedule
            </h2>
            <a href="{{ route('flight-schedules.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold
                    bg-slate-100 hover:bg-slate-200 text-slate-700
                    dark:bg-white/5 dark:hover:bg-white/10 dark:text-slate-300
                    border border-slate-200 dark:border-white/10 transition cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Schedules
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">

                <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex items-center gap-2">
                    <div class="w-1 h-5 bg-cyan-400 rounded-full"></div>
                    <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Schedule Details</h3>
                </div>

                <div class="px-6 py-6">
                    @if($errors->any())
                        <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200/60 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-500/20 dark:bg-red-950/40 dark:text-red-300">
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

                    <form method="POST" action="{{ route('flight-schedules.update', $flightSchedule) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="aircraft_type" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Aircraft Type</label>
                            <select id="aircraft_type" name="aircraft_type" required
                                class="w-full rounded-lg border border-slate-200 dark:border-white/10
                                    bg-white/80 dark:bg-white/5
                                    text-slate-800 dark:text-slate-200
                                    px-3 py-2.5 text-sm
                                    focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-400
                                    transition">
                                @foreach(['Boeing 737-800', 'Boeing 747-400', 'MD-82'] as $aircraftType)
                                    <option value="{{ $aircraftType }}" @selected(old('aircraft_type', $flightSchedule->aircraft_type) === $aircraftType)>
                                        {{ $aircraftType }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div>
                                <label for="scheduled_date" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Booking Date</label>
                                <input type="date" id="scheduled_date" name="scheduled_date"
                                    value="{{ old('scheduled_date', $flightSchedule->scheduled_date->toDateString()) }}" required
                                    class="w-full rounded-lg border border-slate-200 dark:border-white/10
                                        bg-white/80 dark:bg-white/5
                                        text-slate-800 dark:text-slate-200
                                        px-3 py-2.5 text-sm
                                        focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-400
                                        transition">
                            </div>

                            <div>
                                <label for="scheduled_time" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Booking Time</label>
                                <input type="time" id="scheduled_time" name="scheduled_time"
                                    value="{{ old('scheduled_time', substr($flightSchedule->scheduled_time, 0, 5)) }}" min="11:00" max="20:00" required
                                    class="w-full rounded-lg border border-slate-200 dark:border-white/10
                                        bg-white/80 dark:bg-white/5
                                        text-slate-800 dark:text-slate-200
                                        px-3 py-2.5 text-sm
                                        focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-400
                                        transition">
                                <p class="mt-1.5 text-xs text-slate-400 dark:text-slate-500">Available booking hours: 11:00 AM – 8:00 PM.</p>
                            </div>
                        </div>

                        @if(auth()->user()->role === 'admin')
                            <div>
                                <label for="status" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</label>
                                <select id="status" name="status"
                                    class="w-full rounded-lg border border-slate-200 dark:border-white/10
                                        bg-white/80 dark:bg-white/5
                                        text-slate-800 dark:text-slate-200
                                        px-3 py-2.5 text-sm
                                        focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-400
                                        transition">
                                    @foreach(['upcoming', 'cancelled'] as $status)
                                        <option value="{{ $status }}" @selected(old('status', $flightSchedule->status) === $status)>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div>
                            <label for="notes" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Notes</label>
                            <textarea id="notes" name="notes" rows="4"
                                class="w-full rounded-lg border border-slate-200 dark:border-white/10
                                    bg-white/80 dark:bg-white/5
                                    text-slate-800 dark:text-slate-200
                                    px-3 py-2.5 text-sm
                                    focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-400
                                    transition resize-none">{{ old('notes', $flightSchedule->notes) }}</textarea>
                        </div>

                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg
                                text-xs font-bold uppercase tracking-wider
                                bg-cyan-500 hover:bg-cyan-400 text-white
                                shadow-md shadow-cyan-500/20 hover:shadow-cyan-400/30
                                transition-all duration-200 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
