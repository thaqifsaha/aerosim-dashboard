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

            <div class="rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">

                <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex items-center gap-2">
                    <div class="w-1 h-5 bg-cyan-400 rounded-full"></div>
                    <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Pilot Assignment</h3>
                </div>

                <div class="px-6 py-6 space-y-6">

                    <form method="POST" action="{{ route('pilot-selection.update') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="active_pilot_id" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Active Pilot
                            </label>
                            <select name="active_pilot_id" id="active_pilot_id"
                                class="w-full rounded-lg border border-slate-200 dark:border-white/10
                                    bg-white/80 dark:bg-white/5
                                    text-slate-800 dark:text-slate-200
                                    px-3 py-2.5 text-sm
                                    focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-400
                                    transition cursor-pointer">
                                <option value="">-- None --</option>
                                @foreach($pilots as $pilot)
                                    <option value="{{ $pilot->id }}"
                                        {{ optional($setting)->active_pilot_id == $pilot->id ? 'selected' : '' }}>
                                        {{ $pilot->name }} ({{ $pilot->email }})
                                    </option>
                                @endforeach
                            </select>
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
</x-app-layout>
