<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="hud-title animate-hud text-xl font-semibold">
                {{ auth()->user()->role === 'admin' ? 'Flight Schedule Management' : 'Book Flight Session' }}
            </h2>
            <p class="text-sm text-gray-400">
                Logged in as:
                <span class="font-semibold text-blue-400">{{ ucfirst(auth()->user()->role) }}</span>
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-900 dark:bg-green-950/50 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/50 dark:text-red-300">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(auth()->user()->role === 'pilot')
                <section class="bg-white/80 dark:bg-white/5 backdrop-blur-md border border-white/40 dark:border-white/10 shadow-lg sm:rounded-xl p-6 transition">
                    <h3 class="mb-4 text-lg font-bold text-gray-900 dark:text-gray-100">Your upcoming sessions</h3>

                    @if($upcomingSchedules->isEmpty())
                        <p class="text-sm text-gray-600 dark:text-gray-300">No upcoming sessions found.</p>
                    @else
                        <div class="overflow-x-auto rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                            <table class="min-w-full">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Date</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Time</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Aircraft</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Status</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Notes</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingSchedules as $schedule)
                                        <tr class="border-t border-gray-200 dark:border-gray-700">
                                            <td class="px-4 py-3 text-sm font-mono text-gray-900 dark:text-gray-100">{{ $schedule->scheduled_date->format('d M Y') }}</td>
                                            <td class="px-4 py-3 text-sm font-mono text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($schedule->scheduled_time)->format('g:i A') }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $schedule->aircraft_type }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                                    {{ ucfirst($schedule->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $schedule->notes ?: '-' }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                <div class="flex flex-wrap gap-2">
                                                    <a href="{{ route('flight-schedules.edit', $schedule) }}"
                                                        class="inline-block px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white text-xs font-semibold rounded transition">
                                                        Edit
                                                    </a>
                                                    <form method="POST" action="{{ route('flight-schedules.cancel', $schedule) }}" onsubmit="return confirm('Cancel this scheduled session?');">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="inline-block px-4 py-2 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700">
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
                </section>

                <section>
                    <div class="bg-white/80 dark:bg-white/5 backdrop-blur-md border border-white/40 dark:border-white/10 shadow-lg sm:rounded-xl p-6 transition">
                        <div class="mb-5">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Book a new session</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                Fridays and already-booked dates are unavailable.
                            </p>
                        </div>

                        <form method="POST" action="{{ route('flight-schedules.store') }}" class="space-y-5">
                            @csrf

                            <div>
                                <label for="aircraft_type" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">Aircraft Type</label>
                                <select id="aircraft_type" name="aircraft_type" required
                                    class="w-full rounded border border-gray-300 bg-white text-gray-800 dark:bg-gray-700 dark:text-white dark:border-gray-600 px-3 py-2 text-sm transition">
                                    <option value="">Select aircraft type</option>
                                    @foreach(['Boeing 737-800', 'Boeing 747-400', 'MD-82'] as $aircraftType)
                                        <option value="{{ $aircraftType }}" @selected(old('aircraft_type') === $aircraftType)>
                                            {{ $aircraftType }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">Booking Date</label>
                                <input type="hidden" id="scheduled_date" name="scheduled_date" value="{{ old('scheduled_date') }}">

                                <div class="rounded-lg border border-gray-200 bg-white/80 p-4 dark:border-gray-700 dark:bg-gray-900/50">
                                    <div class="mb-4 flex items-center justify-between">
                                        <button type="button" id="calendar-prev" class="rounded px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">←</button>
                                        <p id="calendar-title" class="text-sm font-semibold text-gray-900 dark:text-gray-100"></p>
                                        <button type="button" id="calendar-next" class="rounded px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">→</button>
                                    </div>

                                    <div class="grid grid-cols-7 gap-2 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                        <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
                                    </div>
                                    <div id="calendar-grid" class="mt-3 grid grid-cols-7 gap-2"></div>
                                </div>

                                <p id="calendar-selection" class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    Select an available date.
                                </p>

                                <div class="mt-4 rounded-lg border border-gray-200 bg-gray-50/80 p-4 dark:border-gray-700 dark:bg-gray-900/60">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Availability guide</p>
                                    <div class="mt-3 flex flex-wrap gap-x-5 gap-y-3 text-sm text-gray-600 dark:text-gray-300">
                                        <div class="flex items-center gap-2">
                                            <span class="h-4 w-4 rounded bg-gray-300 dark:bg-gray-700"></span>
                                            <span>Unavailable date</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="h-4 w-4 rounded bg-blue-600"></span>
                                            <span>Selected date</span>
                                        </div>
                                    </div>
                                    <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">
                                        Unavailable dates will show either “Already booked” or “Closed on Friday”.
                                    </p>
                                </div>
                            </div>

                            <div>
                                <label for="scheduled_time" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">Booking Time</label>
                                <input type="time" id="scheduled_time" name="scheduled_time" value="{{ old('scheduled_time') }}" min="11:00" max="20:00" required
                                    class="w-full rounded border border-gray-300 bg-white text-gray-800 dark:bg-gray-700 dark:text-white dark:border-gray-600 px-3 py-2 text-sm transition">
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Available booking hours: 11:00 AM – 8:00 PM.</p>
                            </div>

                            <div>
                                <label for="notes" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">Notes</label>
                                <textarea id="notes" name="notes" rows="4"
                                    class="w-full rounded border border-gray-300 bg-white text-gray-800 dark:bg-gray-700 dark:text-white dark:border-gray-600 px-3 py-2 text-sm transition">{{ old('notes') }}</textarea>
                            </div>

                            <button class="inline-block px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded hover:bg-blue-700">
                                Book Session
                            </button>
                        </form>
                    </div>
                </section>
            @endif

            @if(auth()->user()->role === 'admin')
            <section class="bg-white/80 dark:bg-white/5 backdrop-blur-md border border-white/40 dark:border-white/10 shadow-lg sm:rounded-xl p-6 transition">
                <h3 class="mb-4 text-lg font-bold text-gray-900 dark:text-gray-100">
                    {{ auth()->user()->role === 'admin' ? 'Upcoming scheduled sessions' : 'Your upcoming sessions' }}
                </h3>

                @if($upcomingSchedules->isEmpty())
                    <p class="text-sm text-gray-600 dark:text-gray-300">No upcoming sessions found.</p>
                @else
                    <div class="overflow-x-auto rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    @if(auth()->user()->role === 'admin')
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Pilot</th>
                                    @endif
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Date</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Time</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Aircraft</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Status</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Notes</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingSchedules as $schedule)
                                    <tr class="border-t border-gray-200 dark:border-gray-700">
                                        @if(auth()->user()->role === 'admin')
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $schedule->user?->name ?? '-' }}</td>
                                        @endif
                                        <td class="px-4 py-3 text-sm font-mono text-gray-900 dark:text-gray-100">{{ $schedule->scheduled_date->format('d M Y') }}</td>
                                        <td class="px-4 py-3 text-sm font-mono text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($schedule->scheduled_time)->format('g:i A') }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $schedule->aircraft_type }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                                {{ ucfirst($schedule->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $schedule->notes ?: '-' }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <div class="flex flex-wrap gap-2">
                                                <a href="{{ route('flight-schedules.edit', $schedule) }}"
                                                    class="inline-block px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white text-xs font-semibold rounded transition">
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('flight-schedules.cancel', $schedule) }}" onsubmit="return confirm('Cancel this scheduled session?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="inline-block px-4 py-2 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700">
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
            </section>
            @endif

            @if(auth()->user()->role === 'admin')
                <section class="bg-white/80 dark:bg-white/5 backdrop-blur-md border border-white/40 dark:border-white/10 shadow-lg sm:rounded-xl p-6 transition">
                    <h3 class="mb-4 text-lg font-bold text-gray-900 dark:text-gray-100">Recently cancelled sessions</h3>

                    @if($cancelledSchedules->isEmpty())
                        <p class="text-sm text-gray-600 dark:text-gray-300">No cancelled sessions yet.</p>
                    @else
                        <div class="overflow-x-auto rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                            <table class="min-w-full">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Pilot</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Date</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Time</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Aircraft</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cancelledSchedules as $schedule)
                                        <tr class="border-t border-gray-200 dark:border-gray-700">
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $schedule->user?->name ?? '-' }}</td>
                                            <td class="px-4 py-3 text-sm font-mono text-gray-900 dark:text-gray-100">{{ $schedule->scheduled_date->format('d M Y') }}</td>
                                            <td class="px-4 py-3 text-sm font-mono text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($schedule->scheduled_time)->format('g:i A') }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $schedule->aircraft_type }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                    {{ ucfirst($schedule->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>
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
                                ? ' border-blue-600 bg-blue-600 text-white'
                                : ' border-gray-200 bg-white text-gray-800 hover:border-blue-400 hover:bg-blue-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 dark:hover:bg-gray-800';
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
</x-app-layout>
