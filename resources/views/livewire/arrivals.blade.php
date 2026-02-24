<div>
    <x-slot:title>Current Weather at Arrival Airports</x-slot:title>

    {{-- php artisan livewire:publish --pagination --}}
    {{-- https://itf-laravel-12.netlify.app/laravel/publicShopMaster#customizing-pagination-views --}}

    <div class="my-4">{{ $airports->links() }}</div>
    <div class="@container">
        <div class="grid grid-cols-1 @4xl:grid-cols-2 @7xl:grid-cols-3 gap-8 mt-8">
            @foreach ($airports as $airport)
                <div
                    wire:key="{{ $airport->id }}"
                    class="bg-white dark:bg-zinc-700 border border-zinc-300 dark:border-zinc-800 rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <!-- Header -->
                    <div class="p-5 border-b border-zinc-200 dark:border-zinc-600 flex items-center justify-between gap-4">
                        <!-- Links: Airport code en city -->
                        <div>
                            <p class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $airport->code }}</p>
                            <p class="text-sm italic text-zinc-600 dark:text-zinc-300">
                                Arrivals at {{ $airport->city }} ({{ $airport->airportstatus->name }})
                            </p>
                        </div>

                        <!-- Rechts: Weather button -->
                        <flux:button
                            wire:click="showWeather({{ $airport->id }})"
                            icon="cloud"
                            tooltip="Show current weather"
                            variant="subtle"
                            class="cursor-pointer border border-zinc-200 dark:border-zinc-700"
                        />
                    </div>

                    <!-- Flights -->
                    <ul class="p-2 space-y-2">
                        @foreach($airport->arrival_flights as $flight)
                            <li class="flex flex-col md:flex-row md:justify-between md:items-center bg-zinc-50 dark:bg-zinc-600 rounded-lg p-3 text-xs">
                                <div>
                                    <span class="font-medium text-zinc-800 dark:text-zinc-100">{{ $flight->full_arrival_time }}</span>
                                    <span class="text-zinc-700 dark:text-zinc-300">- {{ $flight->carrier->name }} flight</span>
                                    <b class="text-zinc-900 dark:text-zinc-100">{{ $flight->number }}</b>
                                </div>
                                <div class="text-zinc-600 dark:text-zinc-300 mt-1 md:mt-0">
                                    from {{ $flight->from_airport->city }}: <i>{{ $flight->flightstatus->name }}</i>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
    <div class="my-4">{{ $airports->links() }}</div>

    {{-- Detail Modal will go here --}}
    <flux:modal name="weatherModal" class="w-[500px]">
        <!-- Header met wolk icoon -->
        <div class="flex items-center border-b border-zinc-300 pb-4 gap-4">
            <!-- Wolk icoon -->
            <div class="flex-shrink-0 text-zinc-500 dark:text-zinc-300">
                <flux:icon name="cloud" class="w-10 h-10" />
            </div>
            <!-- Header & subheader -->
            <div>
                <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">
                    Current Weather Information
                </flux:heading>
                <flux:subheading class="text-zinc-600 dark:text-zinc-300">
                    {{ $selectedAirport->city ?? 'City' }} ({{ $selectedAirport->code ?? 'Code' }})
                </flux:subheading>
            </div>
        </div>

        <!-- Weather Table -->
        @isset($selectedAirport->condition)
            <x-itf.table cols="w-8, w-auto, w-24" class="mt-4">
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-600">
                <tr>
                    <td class="px-4 py-2 font-medium text-zinc-700 dark:text-zinc-300">Airport status:</td>
                    <td class="px-4 py-2 text-zinc-900 dark:text-zinc-100">{{ $selectedAirport->airportstatus->name }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-zinc-700 dark:text-zinc-300">Current condition:</td>
                    <td class="px-4 py-2 text-zinc-900 dark:text-zinc-100">{{ $selectedAirport->condition }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-zinc-700 dark:text-zinc-300">Description:</td>
                    <td class="px-4 py-2 text-zinc-900 dark:text-zinc-100">{{ $selectedAirport->description }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-zinc-700 dark:text-zinc-300">Temperature:</td>
                    <td class="px-4 py-2 text-zinc-900 dark:text-zinc-100">{{ $selectedAirport->temperature }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-zinc-700 dark:text-zinc-300">Latitude:</td>
                    <td class="px-4 py-2 text-zinc-900 dark:text-zinc-100">{{ $selectedAirport->latitude }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-zinc-700 dark:text-zinc-300">Longitude:</td>
                    <td class="px-4 py-2 text-zinc-900 dark:text-zinc-100">{{ $selectedAirport->longitude }}</td>
                </tr>
                </tbody>
            </x-itf.table>
        @endif
    </flux:modal>

    {{-- Debug log - now shows paginator object --}}
    <x-itf.livewire-log :airports="$airports"/>
</div>
