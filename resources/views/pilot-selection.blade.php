<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-cyan-500/10 border border-cyan-500/30">
                <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h2 class="font-['Montserrat'] text-xl font-bold tracking-wide text-slate-800 dark:text-white">
                Select Active Pilot
            </h2>
        </div>
    </x-slot>

    <div class="flex items-center justify-center py-8 min-h-[calc(100vh-10rem)]">
        <div class="w-full max-w-lg px-4 sm:px-6">

            @if(session('success'))
                <div class="mb-5 flex items-center gap-3 rounded-xl border border-emerald-200/60 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-950/40 dark:text-emerald-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm">

                <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex items-center gap-2">
                    <div class="w-1 h-5 bg-cyan-400 rounded-full"></div>
                    <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Pilot Assignment</h3>
                </div>

                <div class="px-6 py-6 space-y-6">

                    <form method="POST" action="{{ route('pilot-selection.update') }}" class="space-y-5">
                        @csrf

                        <div x-data="pilotDropdown()" @click.outside="closeDropdown()" class="relative">
                            <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Active Pilot
                            </label>
                            <input type="hidden" name="active_pilot_id" :value="selectedId">

                            <button type="button" @click="toggleOpen()"
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
                                    bg-white dark:bg-slate-800
                                    shadow-xl overflow-hidden">

                                {{-- Search input --}}
                                <div class="p-2 border-b border-slate-100 dark:border-white/10">
                                    <div class="relative">
                                        <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400 pointer-events-none"
                                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                                        </svg>
                                        <input x-ref="search" x-model="search" type="text"
                                            placeholder="Search pilot..."
                                            class="w-full pl-8 pr-3 py-1.5 text-sm rounded-md
                                                bg-slate-50 dark:bg-slate-700/50
                                                border border-slate-200 dark:border-white/10
                                                text-slate-800 dark:text-slate-100
                                                placeholder-slate-400 dark:placeholder-slate-500
                                                focus:outline-none focus:ring-1 focus:ring-cyan-500/40 focus:border-cyan-400
                                                transition">
                                    </div>
                                </div>

                                {{-- Scrollable options list --}}
                                <div class="overflow-y-auto max-h-72">
                                    <template x-for="option in filtered" :key="option.id">
                                        <div @click="select(option)"
                                            class="px-3 py-2.5 text-sm cursor-pointer transition-colors
                                                text-slate-700 dark:text-slate-200
                                                hover:bg-slate-50 dark:hover:bg-white/10"
                                            :class="option.id == selectedId
                                                ? 'bg-cyan-50 dark:bg-cyan-500/10 !text-cyan-700 dark:!text-cyan-300 font-medium'
                                                : ''">
                                            <span x-text="option.label"></span>
                                        </div>
                                    </template>
                                    <div x-show="search && filtered.length === 1"
                                         class="px-3 py-4 text-sm text-center text-slate-400 dark:text-slate-500 italic">
                                        No pilots found.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            class="cursor-pointer inline-flex items-center gap-2 px-5 py-2.5 rounded-lg
                                text-xs font-bold uppercase tracking-wider
                                bg-cyan-500 hover:bg-cyan-400 text-white
                                shadow-md shadow-cyan-500/20 hover:shadow-cyan-400/30
                                transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Active Pilot
                        </button>
                    </form>

                    @if(optional($setting)->activePilot)
                        <div class="border-t border-slate-100 dark:border-white/10 pt-5">
                            <div class="flex items-center gap-3 p-3.5 rounded-lg bg-emerald-50/80 dark:bg-emerald-500/10 border border-emerald-200/60 dark:border-emerald-500/20">
                                <div class="flex-shrink-0 w-9 h-9 rounded-full bg-gradient-to-br from-emerald-400 to-cyan-500 flex items-center justify-center text-white font-bold text-xs select-none">
                                    {{ strtoupper(substr($setting->activePilot->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400 mb-0.5">On Duty</p>
                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $setting->activePilot->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $setting->activePilot->email }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

<script>
function pilotDropdown() {
    const currentId = @json((string)(optional($setting)->active_pilot_id ?? ''));
    const options   = @json($pilotOptions);
    const found     = options.find(o => o.id === currentId);
    return {
        open:          false,
        search:        '',
        selectedId:    currentId,
        selectedLabel: found ? found.label : '-- None --',
        options:       options,
        get filtered() {
            if (!this.search) return this.options;
            const q = this.search.toLowerCase();
            return [
                this.options[0],
                ...this.options.slice(1).filter(o => o.label.toLowerCase().includes(q)),
            ];
        },
        toggleOpen() {
            this.open = !this.open;
            if (this.open) {
                this.$nextTick(() => this.$refs.search.focus());
            }
        },
        closeDropdown() {
            this.open   = false;
            this.search = '';
        },
        select(option) {
            this.selectedId    = option.id;
            this.selectedLabel = option.label;
            this.search        = '';
            this.open          = false;
        },
    };
}
</script>
</x-app-layout>
