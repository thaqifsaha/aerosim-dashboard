<x-app-layout>
    <x-slot name="header">
        <h2 class="hud-title animate-hud text-xl font-semibold">
            Select Active Pilot
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('pilot-selection.update') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="active_pilot_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Active Pilot
                        </label>

                        <select name="active_pilot_id" id="active_pilot_id"
                            class="w-full rounded border-gray-300 dark:bg-gray-900 dark:text-gray-100">
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
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Save Active Pilot
                    </button>
                </form>

                @if(optional($setting)->activePilot)
                    <div class="mt-6 text-sm text-gray-800 dark:text-gray-200">
                        <strong>Current Active Pilot:</strong>
                        {{ $setting->activePilot->name }} ({{ $setting->activePilot->email }})
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>