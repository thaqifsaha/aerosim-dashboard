<div id="flight-data-records-card"
     class="bg-white/80 dark:bg-white/5 backdrop-blur-md border border-white/40 dark:border-white/10 shadow-lg sm:rounded-xl p-6 transition">
    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Flight Data Records</h3>

    <div class="overflow-x-auto overflow-y-auto max-h-96 rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="min-w-full">
            <thead class="bg-gray-100 dark:bg-gray-700 sticky top-0 z-10">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Time</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Airspeed</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Altitude</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Vertical Speed</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">G-Force</th>
                </tr>
            </thead>
            <tbody>
                @foreach($flightData as $row)
                    <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <td class="px-4 py-3 font-mono text-sm text-gray-800 dark:text-gray-200">{{ number_format($row->timestamp_sec, 3) }}</td>
                        <td class="px-4 py-3 font-mono text-sm text-gray-800 dark:text-gray-200">{{ $row->indicated_airspeed }}</td>
                        <td class="px-4 py-3 font-mono text-sm text-gray-800 dark:text-gray-200">{{ $row->altitude }}</td>
                        <td class="px-4 py-3 font-mono text-sm text-gray-800 dark:text-gray-200">{{ $row->vertical_speed }}</td>
                        <td class="px-4 py-3 font-mono text-sm text-gray-800 dark:text-gray-200">{{ $row->g_force }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4 flex justify-center">
        {{ $flightData->links() }}
    </div>
</div>