<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="hud-title animate-hud text-xl font-semibold">Edit Flight Schedule</h2>
            <a href="{{ route('flight-schedules.index') }}"
                class="px-4 py-2 text-sm font-medium tracking-wide bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white rounded transition">
                ← Back to schedules
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto bg-white/80 dark:bg-white/5 backdrop-blur-md border border-white/40 dark:border-white/10 shadow-lg sm:rounded-xl p-6 transition">
                @if($errors->any())
                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/50 dark:text-red-300">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('flight-schedules.update', $flightSchedule) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="aircraft_type" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">Aircraft Type</label>
                        <select id="aircraft_type" name="aircraft_type" required
                            class="w-full rounded border border-gray-300 bg-white text-gray-800 dark:bg-gray-700 dark:text-white dark:border-gray-600 px-3 py-2 text-sm transition">
                            @foreach(['Boeing 737-800', 'Boeing 747-400', 'MD-82'] as $aircraftType)
                                <option value="{{ $aircraftType }}" @selected(old('aircraft_type', $flightSchedule->aircraft_type) === $aircraftType)>
                                    {{ $aircraftType }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label for="scheduled_date" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">Booking Date</label>
                            <input type="date" id="scheduled_date" name="scheduled_date"
                                value="{{ old('scheduled_date', $flightSchedule->scheduled_date->toDateString()) }}" required
                                class="w-full rounded border border-gray-300 bg-white text-gray-800 dark:bg-gray-700 dark:text-white dark:border-gray-600 px-3 py-2 text-sm transition">
                        </div>

                        <div>
                            <label for="scheduled_time" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">Booking Time</label>
                            <input type="time" id="scheduled_time" name="scheduled_time"
                                value="{{ old('scheduled_time', substr($flightSchedule->scheduled_time, 0, 5)) }}" min="11:00" max="20:00" required
                                class="w-full rounded border border-gray-300 bg-white text-gray-800 dark:bg-gray-700 dark:text-white dark:border-gray-600 px-3 py-2 text-sm transition">
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Available booking hours: 11:00 AM – 8:00 PM.</p>
                        </div>
                    </div>

                    @if(auth()->user()->role === 'admin')
                        <div>
                            <label for="status" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">Status</label>
                            <select id="status" name="status"
                                class="w-full rounded border border-gray-300 bg-white text-gray-800 dark:bg-gray-700 dark:text-white dark:border-gray-600 px-3 py-2 text-sm transition">
                                @foreach(['upcoming', 'cancelled'] as $status)
                                    <option value="{{ $status }}" @selected(old('status', $flightSchedule->status) === $status)>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        <label for="notes" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">Notes</label>
                        <textarea id="notes" name="notes" rows="4"
                            class="w-full rounded border border-gray-300 bg-white text-gray-800 dark:bg-gray-700 dark:text-white dark:border-gray-600 px-3 py-2 text-sm transition">{{ old('notes', $flightSchedule->notes) }}</textarea>
                    </div>

                    <button class="inline-block px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded hover:bg-blue-700">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
