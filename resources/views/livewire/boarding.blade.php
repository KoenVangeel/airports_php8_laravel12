<div>
    <x-slot:title>Currently Boarding at all Airports</x-slot:title>

    <div class="container mx-auto">
        <div class="my-6">
            {{ $flights->links() }}
        </div>

        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-zinc-50 dark:bg-zinc-900/50 border-b border-zinc-200 dark:border-zinc-700">
                        <tr>
                            <th class="px-6 py-6 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Flight</th>
                            <th class="px-6 py-6 text-xs font-semibold text-zinc-500 uppercase tracking-wider text-center">Departure</th>
                            <th class="px-6 py-6 text-xs font-semibold text-zinc-500 uppercase tracking-wider text-center">Route</th>
                            <th class="px-6 py-6 text-xs font-semibold text-zinc-500 uppercase tracking-wider text-center">Arrival</th>
                            <th class="px-6 py-6 text-xs font-semibold text-zinc-500 uppercase tracking-wider text-center">Passengers</th>
                            <th class="px-6 py-0 text-xs font-semibold text-zinc-500 uppercase tracking-wider text-right">
                                <flux:select wire:model.live="perPage" label="" size="sm" class="w-32 ml-auto">
                                    @foreach ([10,20,30,40] as $value)
                                        <flux:select.option value="{{ $value }}">{{ $value }} Flights</flux:select.option>
                                    @endforeach
                                </flux:select>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse($flights as $flight)
                            <tr wire:key="{{ $flight->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="h-10 w-10 flex-shrink-0 bg-white p-1 rounded border border-zinc-200 dark:border-zinc-600">
                                            <img src="{{ $flight->carrier->image }}" alt="{{ $flight->carrier->name }}" class="h-full w-full object-contain">
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-zinc-900 dark:text-white">{{ $flight->number }}</div>
                                            <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $flight->carrier->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $flight->short_departure_time }}</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $flight->full_departure_date }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-3">
                                        <div class="text-center">
                                            <div class="text-sm font-medium text-zinc-900 dark:text-white">{{ $flight->from_airport->code }}</div>
                                            <div class="text-[10px] text-zinc-500 dark:text-zinc-400 uppercase">{{ $flight->from_airport->city }}</div>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <flux:icon name="arrow-right" variant="micro" class="text-zinc-400" />
                                            <div class="h-px w-8 bg-zinc-200 dark:bg-zinc-600 my-1"></div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-sm font-medium text-zinc-900 dark:text-white">{{ $flight->to_airport->code }}</div>
                                            <div class="text-[10px] text-zinc-500 dark:text-zinc-400 uppercase">{{ $flight->to_airport->city }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $flight->short_arrival_time }}</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $flight->full_arrival_date }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <flux:badge size="sm" color="zinc" class="font-mono">
                                        {{ $flight->bookings_count }}
                                    </flux:badge>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <flux:button
                                        wire:click="startBoarding({{ $flight->id }})"
                                        variant="primary"
                                        size="sm"
                                        icon="paper-airplane"
                                    >
                                        Boarding
                                    </flux:button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <flux:icon name="no-symbol" class="mx-auto h-12 w-12 text-zinc-300 dark:text-zinc-600 mb-4" />
                                    <div class="text-zinc-500 dark:text-zinc-400 font-medium">No flights currently boarding</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="my-6">
            {{ $flights->links() }}
        </div>
    </div>

    {{-- Detail section --}}
    <flux:modal name="boardingModal" class="w-full max-w-6xl" :closable="false">
        @isset($selectedFlight->number)
        <div class="space-y-6 mt-4">
            {{-- Boarding Header / "Ticket" Style --}}
            <div class="bg-zinc-900 text-white rounded-xl overflow-hidden shadow-2xl flex flex-col md:flex-row">
                {{-- Left: Gate & Info --}}
                <div class="bg-yellow-400 text-black p-8 flex flex-col items-center justify-center md:w-1/4">
                    <flux:icon name="ticket" variant="outline" class="w-12 h-12 mb-2" />
                    <div class="text-xs font-black uppercase tracking-widest opacity-60">Departure Gate</div>
                    <div class="text-6xl font-black">{{ $selectedFlight->gate }}</div>
                </div>

                {{-- Right: Flight Details --}}
                <div class="p-8 flex-1 relative overflow-hidden">
                    {{-- Decorative pattern --}}
                    <div class="absolute right-0 top-0 opacity-10 pointer-events-none">
                         <flux:icon name="globe-americas" class="w-64 h-64 -mr-20 -mt-20" />
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-6 relative z-10">
                        <div class="flex items-center gap-6">
                            <div class="h-16 w-16 bg-white p-2 rounded-lg flex-shrink-0">
                                <img src="{{ $selectedFlight->carrier->image }}" alt="Logo" class="h-full w-full object-contain">
                            </div>
                            <div>
                                <div class="text-2xl font-black tracking-tight">{{ $selectedFlight->carrier->name }}</div>
                                <div class="text-yellow-400 font-mono font-bold">{{ $selectedFlight->number }}</div>
                            </div>
                        </div>

                        <div class="flex items-center gap-8">
                            <div class="text-center">
                                <div class="text-3xl font-black uppercase">{{ $selectedFlight->from_airport->code }}</div>
                                <div class="text-[10px] opacity-60 uppercase">{{ $selectedFlight->from_airport->city }}</div>
                                <div class="text-sm font-bold mt-1">{{ $selectedFlight->short_departure_time }}</div>
                            </div>
                            <div class="flex flex-col items-center gap-1">
                                <flux:icon name="chevron-right" class="text-yellow-400" />
                                <div class="w-12 h-px bg-zinc-700"></div>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-black uppercase">{{ $selectedFlight->to_airport->code }}</div>
                                <div class="text-[10px] opacity-60 uppercase">{{ $selectedFlight->to_airport->city }}</div>
                                <div class="text-sm font-bold mt-1">{{ $selectedFlight->short_arrival_time }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center gap-2 text-green-400">
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                        <span class="text-xs font-black uppercase tracking-widest">Now Boarding All Passengers</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Waiting List --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-between px-2">
                        <h2 class="text-lg font-bold flex items-center gap-2">
                            <flux:icon name="users" variant="mini" class="text-zinc-400" />
                            Waiting for Boarding
                        </h2>
                        <flux:badge size="sm" color="yellow" variant="solid">{{ count($waiting) }}</flux:badge>
                    </div>

                    <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden shadow-sm">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-zinc-50 dark:bg-zinc-900/50 border-b border-zinc-200 dark:border-zinc-700">
                                <tr>
                                    <th class="px-4 py-3 font-semibold text-zinc-500">Seat</th>
                                    <th class="px-4 py-3 font-semibold text-zinc-500">Passenger</th>
                                    <th class="px-4 py-3 font-semibold text-zinc-500">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                                @forelse($waiting as $booking)
                                    <tr wire:key="waiting_{{ $booking->id }}" class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition-colors">
                                        <td class="px-4 py-3 font-mono font-bold">{{ $booking->seat }}</td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $booking->passenger->firstname }} {{ $booking->passenger->lastname }}</div>
                                            <div class="text-[10px] text-zinc-500">{{ $booking->passenger->passport_number }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <flux:button
                                                wire:click="boardPassenger({{ $booking->id }})"
                                                size="xs"
                                                variant="subtle"
                                                icon="arrow-right"
                                            >Board</flux:button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-8 text-center text-zinc-500 italic">No passengers waiting.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- On Board List --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-between px-2">
                        <h2 class="text-lg font-bold flex items-center gap-2 text-zinc-400">
                            <flux:icon name="check-circle" variant="mini" class="text-green-500" />
                            Already On Board
                        </h2>
                        <flux:badge size="sm" color="green" variant="solid">{{ count($boarded) }}</flux:badge>
                    </div>

                    <div class="bg-zinc-50 dark:bg-zinc-900/50 rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-zinc-100 dark:bg-zinc-950 border-b border-zinc-200 dark:border-zinc-700">
                                <tr>
                                    <th class="px-4 py-3 font-semibold text-zinc-500">Seat</th>
                                    <th class="px-4 py-3 font-semibold text-zinc-500">Passenger</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                @forelse($boarded as $booking)
                                    <tr wire:key="boarded_{{ $booking->id }}" class="text-zinc-500">
                                        <td class="px-4 py-3 font-mono font-medium">{{ $booking->seat }}</td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium">{{ $booking->passenger->firstname }} {{ $booking->passenger->lastname }}</div>
                                            <div class="text-[10px] opacity-75">{{ $booking->passenger->passport_number }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-8 text-center text-zinc-400 italic">Cabin is empty.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endisset
    </flux:modal>

    <x-itf.livewire-log :flights="$flights"/>
</div>
