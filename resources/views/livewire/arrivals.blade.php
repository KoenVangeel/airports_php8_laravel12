<div>
    <x-slot:title>Arrival Board & Weather</x-slot:title>
    <x-slot:description>Real-time flight arrivals and local weather conditions.</x-slot:description>

    <div class="container mx-auto">

        <div class="my-8">
            {{ $airports->links() }}
        </div>

        <div class="my-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <flux:select wire:model.live="perPage" label="" size="sm" class="w-32">
                    @foreach ([3,6,9,12] as $value)
                        <flux:select.option value="{{ $value }}">{{ $value }} Airports</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
        </div>

        <div class="@container">
            <div class="grid grid-cols-1 @3xl:grid-cols-2 @6xl:grid-cols-3 gap-6 mt-4">
                @foreach ($airports as $airport)
                    <div
                        wire:key="{{ $airport->id }}"
                        class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden flex flex-col">

                        <!-- Header -->
                        <div class="p-5 bg-zinc-50 dark:bg-zinc-900/50 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="bg-zinc-900 text-white px-2 py-1 rounded text-lg font-black tracking-tighter">
                                    {{ $airport->code }}
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white leading-tight">{{ $airport->city }}</h3>
                                    <p class="text-[10px] uppercase tracking-wider text-zinc-500 font-semibold">{{ $airport->airportstatus->name }}</p>
                                </div>
                            </div>

                            <flux:button
                                wire:click="showWeather({{ $airport->id }})"
                                icon="cloud"
                                size="xs"
                                variant="subtle"
                                tooltip="Local weather"
                                class="rounded-full shadow-sm"
                            />
                        </div>

                        <!-- Flights List -->
                        <div class="flex-1 p-2 bg-white dark:bg-zinc-800">
                            @if(count($airport->arrival_flights) > 0)
                                <div class="space-y-1">
                                    @foreach($airport->arrival_flights as $flight)
                                        <div class="flex items-center justify-between p-3 rounded-lg bg-zinc-50/50 dark:bg-zinc-700/30 hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors group">
                                            <div class="flex items-center gap-3">
                                                <div class="text-center w-10">
                                                    <div class="text-[10px] font-black text-zinc-900 dark:text-white">{{ $flight->short_arrival_time }}</div>
                                                    <div class="text-[8px] text-zinc-400 font-bold">{{ $flight->full_arrival_date }}</div>
                                                </div>
                                                <div class="h-6 w-px bg-zinc-200 dark:bg-zinc-600"></div>
                                                <div>
                                                    <div class="text-[10px] font-bold text-zinc-700 dark:text-zinc-300 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors">
                                                        {{ $flight->number }}
                                                    </div>
                                                    <div class="text-[9px] text-zinc-400 uppercase tracking-tight">from {{ $flight->from_airport->city }}</div>
                                                </div>
                                            </div>
                                            <flux:badge size="sm" color="zinc" variant="pill" class="text-[8px] px-1.5 py-0">
                                                {{ $flight->flightstatus->name }}
                                            </flux:badge>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="h-32 flex flex-col items-center justify-center text-zinc-400 dark:text-zinc-600 italic text-xs">
                                    <flux:icon name="no-symbol" variant="micro" class="mb-1 opacity-50" />
                                    No scheduled arrivals
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="my-8">
            {{ $airports->links() }}
        </div>
    </div>

    {{-- Detail Modal --}}
    <flux:modal name="weatherModal" class="w-full max-w-lg" :closable="false">
        @isset($selectedAirport->id)
            <div class="space-y-6">
                <!-- Weather Header -->
                <div class="bg-gradient-to-br from-zinc-900 to-zinc-800 text-white rounded-xl p-8 relative overflow-hidden shadow-xl">
                    {{-- Decorative pattern --}}
                    <div class="absolute right-0 top-0 opacity-10 pointer-events-none">
                        <flux:icon name="cloud" class="w-48 h-48 -mr-12 -mt-12" />
                    </div>

                    <div class="relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="bg-white/10 backdrop-blur-md p-3 rounded-2xl">
                                <flux:icon name="cloud" variant="outline" class="w-10 h-10 text-white" />
                            </div>
                            <div>
                                <h3 class="text-2xl font-black tracking-tight">{{ $selectedAirport->city }}</h3>
                                <div class="flex items-center gap-2 opacity-70">
                                    <span class="text-xs font-bold uppercase tracking-widest">{{ $selectedAirport->code }}</span>
                                    <span class="h-1 w-1 rounded-full bg-white/40"></span>
                                    <span class="text-xs font-bold uppercase tracking-widest">{{ $selectedAirport->airportstatus->name }}</span>
                                </div>
                            </div>
                        </div>

                        @isset($selectedAirport->temperature)
                            <div class="flex items-end gap-1">
                                <span class="text-6xl font-black">{{ round($selectedAirport->temperature) }}°</span>
                                <span class="text-xl font-bold opacity-60 mb-2">C</span>
                                <div class="ml-6 mb-2">
                                    <div class="text-sm font-bold capitalize">{{ $selectedAirport->condition }}</div>
                                    <div class="text-[10px] opacity-60 uppercase tracking-wider font-bold">{{ $selectedAirport->description }}</div>
                                </div>
                            </div>
                        @else
                            <div class="py-4 text-zinc-400 italic text-sm">Weather data temporarily unavailable</div>
                        @endisset
                    </div>
                </div>

                <!-- Technical Details Table -->
                <div class="bg-zinc-50 dark:bg-zinc-900/50 rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                    <table class="w-full text-left text-sm border-collapse">
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        <tr>
                            <th class="px-5 py-4 font-bold text-zinc-500 uppercase text-[10px] tracking-widest w-1/3">Coordinates</th>
                            <td class="px-5 py-4 font-mono text-xs text-zinc-900 dark:text-zinc-100">
                                Lat: {{ $selectedAirport->latitude ?? 'N/A' }} / Lon: {{ $selectedAirport->longitude ?? 'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <th class="px-5 py-4 font-bold text-zinc-500 uppercase text-[10px] tracking-widest">Status</th>
                            <td class="px-5 py-4">
                                <flux:badge size="sm" color="zinc" variant="solid">{{ $selectedAirport->airportstatus->name }}</flux:badge>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end">
                    <flux:modal.close>
                        <flux:button variant="ghost">Close</flux:button>
                    </flux:modal.close>
                </div>
            </div>
        @endisset
    </flux:modal>

    <x-itf.livewire-log :airports="$airports"/>
</div>
