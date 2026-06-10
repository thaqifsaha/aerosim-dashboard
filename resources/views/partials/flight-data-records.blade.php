<div id="flight-data-records-card"
     class="rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">

    <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex items-center gap-2">
        <div class="w-1 h-5 bg-cyan-400 rounded-full"></div>
        <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Flight Data Records</h3>
    </div>

    <div class="overflow-x-auto overflow-y-auto max-h-96">
        <table class="min-w-full">
            <thead class="sticky top-0 z-10 bg-slate-50/80 dark:bg-slate-800/60">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Time</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Airspeed</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Altitude</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Vertical Speed</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">G-Force</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @foreach($flightData as $row)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-white/5 transition-colors duration-150">
                        <td class="px-5 py-2.5 font-mono text-sm text-slate-600 dark:text-slate-300">{{ number_format($row->timestamp_sec, 3) }}</td>
                        <td class="px-5 py-2.5 font-mono text-sm text-slate-700 dark:text-slate-300">{{ $row->indicated_airspeed }}</td>
                        <td class="px-5 py-2.5 font-mono text-sm text-slate-700 dark:text-slate-300">{{ $row->altitude }}</td>
                        <td class="px-5 py-2.5 font-mono text-sm text-slate-700 dark:text-slate-300">{{ $row->vertical_speed }}</td>
                        <td class="px-5 py-2.5 font-mono text-sm text-slate-700 dark:text-slate-300">{{ $row->g_force }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-slate-100 dark:border-white/10">
        {{ $flightData->links() }}
    </div>
</div>
