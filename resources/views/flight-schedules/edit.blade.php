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
            <div class="max-w-3xl mx-auto rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm">

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

                        <div x-data="editAircraftDropdown()" @click.outside="open = false" class="relative">
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
                            <div x-data="editStatusDropdown()" @click.outside="open = false" class="relative">
                                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</label>
                                <input type="hidden" name="status" :value="selectedId">

                                <button type="button" @click="open = !open"
                                    class="w-full rounded-lg border border-slate-200 dark:border-white/10
                                        bg-white dark:bg-slate-800
                                        text-slate-800 dark:text-slate-100
                                        px-3 py-2.5 text-sm
                                        flex items-center justify-between gap-2
                                        focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-400
                                        transition cursor-pointer">
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

<script>
function editAircraftDropdown() {
    const currentId = @json(old('aircraft_type', $flightSchedule->aircraft_type));
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

function editStatusDropdown() {
    const currentId = @json(old('status', $flightSchedule->status));
    const options = [
        { id: 'upcoming',  label: 'Upcoming' },
        { id: 'cancelled', label: 'Cancelled' },
    ];
    const found = options.find(o => o.id === currentId);
    return {
        open:          false,
        selectedId:    currentId,
        selectedLabel: found ? found.label : 'Select status',
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
