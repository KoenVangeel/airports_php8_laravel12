<div>
    <x-slot:title>Live Departures at all Airports</x-slot:title>

    {{-- php artisan livewire:publish --pagination --}}
    {{-- https://itf-laravel-12.netlify.app/laravel/publicShopMaster#customizing-pagination-views --}}

    {{-- blink-board in resources/css/app.css--}}

    <div class="my-4">{{ $airports->links() }}</div>
    <div class="@container">
        <div class="grid grid-cols-1 @4xl:grid-cols-2 @7xl:grid-cols-3 gap-8 mt-8">
            @foreach ($airports as $airport)
                <div class="bg-white dark:bg-zinc-700 border border-zinc-300 dark:border-zinc-800 rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                    <div class="p-5 flex items-start justify-between gap-4">
                        <!-- Teksten links, onder elkaar -->
                        <div class="flex flex-col gap-1">
                            <p class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $airport->code }}</p>
                            <p class="text-sm italic text-zinc-600 dark:text-zinc-300">
                                {{ $airport->city }} ({{ $airport->airportstatus->name }})
                            </p>
                            <p class="text-sm italic text-zinc-600 dark:text-zinc-300">
                                {{ $airport->departure_flights_count }} departure
                                @if ($airport->departure_flights_count == 1)
                                    flight
                                @else
                                    flights
                                @endif
                            </p>
                        </div>

                        <!-- Knop rechts -->
                        <flux:button
                            wire:click="showDepartures({{ $airport->id }})"
                            icon="plane-takeoff"
                            tooltip="Show live departures"
                            variant="subtle"
                            class="cursor-pointer border border-zinc-200 dark:border-zinc-700"
                        />
                    </div>
                </div>

            @endforeach
        </div>
    </div>
    <div class="my-4">{{ $airports->links() }}</div>

    {{-- Departures Modal --}}
    <flux:modal name="departuresModal" class="w-[700px] !bg-transparent !p-0 !shadow-none" :closable="false">

        <div class="bg-zinc-950 rounded-md overflow-hidden border border-zinc-800 shadow-lg">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-800">
                <div class="flex items-center gap-4">
                    <flux:icon name="plane-takeoff" class="w-10 h-10 text-yellow-400" />

                    <div>
                        <flux:heading size="lg" class="text-yellow-300 font-mono tracking-widest uppercase">
                            Departures
                        </flux:heading>
                        <flux:subheading class="text-zinc-400 font-mono tracking-wide">
                            {{ $selectedAirport->city ?? 'City' }} ({{ $selectedAirport->code ?? 'Code' }})
                        </flux:subheading>
                    </div>
                </div>

                <div class="text-zinc-500 font-mono text-sm">
                    LIVE BOARD
                </div>
            </div>

            <!-- Table -->
            <div class="p-6">
                <table class="w-full font-mono text-sm text-yellow-300 tracking-wide">
                    <thead class="text-zinc-400 uppercase text-xs">
                    <tr class="border-b border-zinc-800">
                        <th class="text-left pb-3 pr-6">Time</th>
                        <th class="text-left pb-3 pr-6">Flight</th>
                        <th class="text-left pb-3 pr-6">Destination</th>
                        <th class="text-left pb-3 pr-6">Gate</th>
                        <th class="text-left pb-3 pr-6">Status</th>
                        <th class="text-left pb-3">Boarding</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($flights as $flight)
                        <tr class="border-b border-zinc-900 hover:bg-zinc-900/50 transition">
                            <td class="py-1 pr-6 whitespace-nowrap">
                                {{ $flight->short_departure_time }}
                            </td>

                            <td class="py-1 pr-6 font-bold text-yellow-200">
                                {{ $flight->number }}
                            </td>

                            <td class="py-1 pr-6">
                                {{ strtoupper($flight->to_airport->city) }}
                            </td>

                            <td class="py-1 pr-6 text-yellow-200">
                                {{ $flight->gate ?? '-' }}
                            </td>

                            <td class="py-1 pr-6">
                                {{ strtoupper($flight->flightstatus->name) }}
                            </td>

                            <td class="py-1">
                                @if(str_contains(strtolower($flight->boarding_text), 'boarding'))
                                    <span class="blink-board text-yellow-200 font-bold">
                                        {{ strtoupper($flight->boarding_text) }}
                                    </span>
                                @else
                                    <span class="text-yellow-300">
                                        {{ strtoupper($flight->boarding_text) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @if(count($flights) == 0)
                    <div class="text-center text-zinc-500 font-mono py-10">
                        NO DEPARTURES AVAILABLE
                    </div>
                @endif
            </div>
        </div>

    </flux:modal>

    {{-- Debug log - now shows paginator object --}}
    <x-itf.livewire-log :airports="$airports"/>
</div>
