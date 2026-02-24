<div>
    <x-slot:title>Currently Boarding at all Airports</x-slot:title>

    {{-- Pagination --}}
    <div class="my-4">{{ $flights->links() }}</div>

    {{-- Grid of flight cards --}}
    <div class="container mx-auto mt-8">
        <div class="bg-white dark:bg-zinc-700 border border-zinc-300 dark:border-zinc-800 rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
            {{-- Header: Titel + perPage selector --}}
            <div class="p-5 border-b border-zinc-200 dark:border-zinc-600 flex items-center justify-between">
                <p class="text-base font-semibold text-zinc-900 dark:text-zinc-100">Currently Boarding</p>

                <flux:select wire:model.live="perPage" label="" class="w-36">
                    @foreach ([10,20,30,40] as $value)
                        <flux:select.option value="{{ $value }}">{{ $value }} Records</flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            {{-- Flights list --}}
            <ul class="p-2 space-y-2">
                @forelse($flights as $flight)
                    <li class="flex flex-col text-xs md:flex-row md:justify-between md:items-center bg-zinc-50 dark:bg-zinc-600 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-500 transition-colors">
                        {{-- Flight details --}}
                        <div class="flex flex-col md:flex-row">
                        <span class="font-normal text-zinc-800 dark:text-zinc-100 left-5 pl-3">
                            {{ $flight->full_departure_time }} → {{ $flight->full_arrival_time }}
                        </span>
                            <span class="text-zinc-700 dark:text-zinc-300 md:ml-2">
                            {{ $flight->from_airport->city }} → {{ $flight->to_airport->city }}
                            ({{ $flight->carrier->name }} flight <b>{{ $flight->number }}</b>, {{ $flight->bookings_count }} passengers)
                        </span>
                        </div>

                        {{-- Knop rechts --}}
                        <div class="mt-2 md:mt-0">
                            <flux:button
                                wire:click="startBoarding({{ $flight->id }})"
                                icon="paper-airplane"
                                tooltip="Start boarding"
                                variant="subtle"
                                class="cursor-pointer border-zinc-200 dark:border-zinc-700 rounded-md"
                            />
                        </div>
                    </li>
                @empty
                    <li class="bg-zinc-50 dark:bg-zinc-600 rounded-lg p-4 text-center text-gray-500 dark:text-gray-400 italic font-semibold">
                        No flights currently boarding
                    </li>
                @endforelse
            </ul>

        </div>
    </div>

    {{-- Pagination bottom --}}
    <div class="my-4">{{ $flights->links() }}</div>

    {{-- Detail section --}}
    <flux:modal name="boardingModal" class="w-full max-w-6xl">
        @isset($selectedFlight->number)
        <div class="flex flex-row gap-4 mt-4">
            <div class="flex-1 flex-col gap-2">
                <div class="grid grid-cols-3 gap-5">
                    <div>
                        <div class="bg-white text-gray-900 p-2 rounded-lg shadow-lg">
                            <div class="flex bg-yellow-400 col-span-2 text-xl font-bold text-center mb-2 p-5">

                                <flux:icon name="plane-takeoff" class="w-12 h-12 text-black" />
                                <h3 class="text-4xl font-bold ml-5 mb-4 mt-2"> GATE {{ $selectedFlight->gate }}</h3>
                            </div>
                            <div class="flex bg-white mt-3 items-center">
                                <img class="w-1/3"
                                     src="{{ $selectedFlight->carrier->image }}?{{ rand() }}" alt="Logo">
                                <div class="text-2xl ml-5">{{ $selectedFlight->carrier->name }}</div>
                            </div>
                            <div class="bg-black text-white p-3 mt-3">
                                <h3 class="text-3xl">{{ $selectedFlight->to_airport->city }}</h3>
                            </div>
                            <div class="bg-black text-white flex justify-between p-3">
                                <h4 class="text-2xl">{{ $selectedFlight->short_departure_time }}</h4>
                                <h3 class="text-2xl">{{ $selectedFlight->number }}</h3>
                            </div>
                            <div class="bg-black text-green-300 flex justify-between p-3">
                                <h4 class="text-2xl">Now boarding all passengers</h4>
                            </div>
                        </div>
                    </div>
                    <div>
                        <!-- Passengers waiting for boarding -->
                        <h2 class="text-lg font-semibold mb-2">Waiting for Boarding</h2>
                        <x-itf.table class="w-full">
                            <thead>
                                <tr>
                                    <th>Seat</th>
                                    <th>Passenger</th>
                                    <th>Passport</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($waiting as $booking)
                                    <tr wire:key="waiting_{{ $booking->id }}">
                                        <td>{{ $booking->seat }}</td>
                                        <td>{{ $booking->passenger->firstname }} {{ $booking->passenger->lastname }}</td>
                                        <td>{{ $booking->passenger->passport_number }}</td>
                                        <td>
                                            <flux:button
                                                wire:click="boardPassenger({{ $booking->id }})"
                                                icon="paper-airplane"
                                                tooltip="Board Passenger"
                                                variant="subtle"
                                                label="Board"
                                            />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center p-4">All passengers are on board.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </x-itf.table>
                    </div>
                    <div>
                        <!-- Passengers already in the plane -->
                        <h2 class="text-lg font-semibold mb-2">On Board</h2>
                        <x-itf.table>
                            <thead>
                                <tr>
                                    <th>Seat</th>
                                    <th>Passenger</th>
                                    <th>Passport</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($boarded as $booking)
                                    <tr wire:key="boarded_{{ $booking->id }}">
                                        <td>{{ $booking->seat }}</td>
                                        <td>{{ $booking->passenger->firstname }} {{ $booking->passenger->lastname }}</td>
                                        <td>{{ $booking->passenger->passport_number }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center p-4">No passengers on board yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </x-itf.table>
                    </div>
                </div>
            </div>
        </div>
        @endisset
    </flux:modal>

    <x-itf.livewire-log :flights="$flights"/>
</div>
