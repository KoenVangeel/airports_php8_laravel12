<div>
    <x-slot:title>Flights Calendar View</x-slot:title>

    @php($colors = [
        'bg-slate-200 text-slate-900 border border-slate-300',    // neutraal / standaard
        'bg-stone-50 text-stone-900 border border-stone-200', // op tijd / info (melkachtig)
        'bg-emerald-200 text-emerald-900 border border-emerald-300', // bevestigde vluchten
        'bg-amber-200 text-amber-900 border border-amber-300',   // boarding / aandacht
        'bg-rose-200 text-rose-900 border border-rose-300',      // vertraagd / waarschuwing
        'bg-violet-200 text-violet-900 border border-violet-300',// bijzonder / VIP / charter
    ])

    {{-- started with this calendar: https://tailwindcomponents.com/component/calendar-table --}}

    <div class="container mx-auto mt-10">
        <div class="wrapper bg-white rounded shadow w-full ">
            <div class="header flex justify-between border-b p-2">
        <span class="text-5xl font-extralight p-4">
          {{ $currentYear }} {{ date('F', mktime(0, 0, 0, $currentMonth, 10)) }}
        </span>
                <div class="buttons">
                    <button class="p-1" wire:click="previousMonth()">
                        <svg width="1em" fill="gray" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-left-circle" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path fill-rule="evenodd" d="M8.354 11.354a.5.5 0 0 0 0-.708L5.707 8l2.647-2.646a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708 0z"/>
                            <path fill-rule="evenodd" d="M11.5 8a.5.5 0 0 0-.5-.5H6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 .5-.5z"/>
                        </svg>
                    </button>
                    <button class="p-1" wire:click="nextMonth()">
                        <svg width="1em" fill="gray" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-right-circle" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path fill-rule="evenodd" d="M7.646 11.354a.5.5 0 0 1 0-.708L10.293 8 7.646 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0z"/>
                            <path fill-rule="evenodd" d="M4.5 8a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5z"/>
                        </svg>
                    </button>
                </div>
            </div>
            <table class="w-full">
                <thead>
                <tr>
                    <th class="p-2 border-r h-10 xl:w-40 lg:w-30 md:w-30 sm:w-20 w-10 xl:text-sm text-xs">
                        <span class="xl:block lg:block md:block sm:block hidden">Monday</span>
                        <span class="xl:hidden lg:hidden md:hidden sm:hidden block">Mon</span>
                    </th>
                    <th class="p-2 border-r h-10 xl:w-40 lg:w-30 md:w-30 sm:w-20 w-10 xl:text-sm text-xs">
                        <span class="xl:block lg:block md:block sm:block hidden">Tuesday</span>
                        <span class="xl:hidden lg:hidden md:hidden sm:hidden block">Tue</span>
                    </th>
                    <th class="p-2 border-r h-10 xl:w-40 lg:w-30 md:w-30 sm:w-20 w-10 xl:text-sm text-xs">
                        <span class="xl:block lg:block md:block sm:block hidden">Wednesday</span>
                        <span class="xl:hidden lg:hidden md:hidden sm:hidden block">Wed</span>
                    </th>
                    <th class="p-2 border-r h-10 xl:w-40 lg:w-30 md:w-30 sm:w-20 w-10 xl:text-sm text-xs">
                        <span class="xl:block lg:block md:block sm:block hidden">Thursday</span>
                        <span class="xl:hidden lg:hidden md:hidden sm:hidden block">Thu</span>
                    </th>
                    <th class="p-2 border-r h-10 xl:w-40 lg:w-30 md:w-30 sm:w-20 w-10 xl:text-sm text-xs">
                        <span class="xl:block lg:block md:block sm:block hidden">Friday</span>
                        <span class="xl:hidden lg:hidden md:hidden sm:hidden block">Fri</span>
                    </th>
                    <th class="p-2 border-r h-10 xl:w-40 lg:w-30 md:w-30 sm:w-20 w-10 xl:text-sm text-xs">
                        <span class="xl:block lg:block md:block sm:block hidden">Saturday</span>
                        <span class="xl:hidden lg:hidden md:hidden sm:hidden block">Sat</span>
                    </th>
                    <th class="p-2 border-r h-10 xl:w-40 lg:w-30 md:w-30 sm:w-20 w-10 xl:text-sm text-xs">
                        <span class="xl:block lg:block md:block sm:block hidden">Sunday</span>
                        <span class="xl:hidden lg:hidden md:hidden sm:hidden block">Sun</span>
                    </th>
                </tr>
                </thead>
                <tbody>
                @for ($i = 0; $i < (int) (count($days) / 7); $i++)
                    <tr wire:key="row_{{ $i }}"
                        class="text-center h-20">
                        @for ($j = 0; $j < 7; $j++)
                            @php($day = $days[($i * 7) + $j])
                            <td wire:key="day_{{ ($i * 7) + $j }}"
                                class="border {{ $day['color'] == 'gray' ? 'bg-gray-100' : '' }} p-1 h-40 xl:w-40 lg:w-30 md:w-30 sm:w-20 w-10 overflow-auto {{ count($day['flights']) != 0 ? 'transition cursor-pointer duration-500 ease hover:bg-gray-300' : '' }}">
                                <div class="flex flex-col h-40 mx-auto xl:w-40 lg:w-30 md:w-30 sm:w-full w-10 mx-auto overflow-hidden">
                                    <div class="top h-5 w-full">
                                        <span class="text-gray-500">{{ $day['day'] }}</span>
                                    </div>
                                    <div
                                        wire:click="showFlights({{ ($i * 7) + $j }})"
                                        class="bottom flex-grow h-30 py-1 w-full {{ count($day['flights']) != 0 ? 'cursor-pointer' : '' }} ">
                                        @foreach($day['flights'] as $flight)
                                            <div class="event {{ $colors[$flight['flightstatus_id'] % 6] }} rounded-md px-2 py-1 text-xs mb-1 shadow-sm">
                                                <span class="event-name">
                                                  {{ $flight['number'] }}
                                                </span>
                                                    <span class="time">
                                                  {{ $flight['short_departure_time'] }}-{{ $flight['short_arrival_time'] }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                        @endfor
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>
    </div>

    {{-- Detail Modal will go here --}}
    <flux:modal name="flightsModal" class="w-[600px]">
        @isset($selectedDay['day'])
        <!-- Header met wolk icoon -->
        <div class="flex items-center border-b border-zinc-300 pb-4 gap-4">
            <!-- Calendar icoon -->
            <div class="flex-shrink-0 text-zinc-500 dark:text-zinc-300">
                <flux:icon name="calendar" class="w-10 h-10" />
            </div>
            <!-- Header & subheader -->
            <div>
                <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">
                    Departing Flights
                </flux:heading>
                <flux:subheading class="text-zinc-600 dark:text-zinc-300">
                    {{ $selectedDay['day'] }}/{{ $selectedDay['month'] }}/{{ $selectedDay['year'] }}
                </flux:subheading>
            </div>
        </div>

        <!-- Flights Table -->
        <x-itf.table cols="w-8, w-auto, w-24" class="mt-4">
            <thead>
            <tr>
                <th>Flight</th>
                <th>ETD</th>
                <th>ETA</th>
                <th>From</th>
                <th>To</th>
                <th>Gate</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @foreach($selectedDay['flights'] as $flight)
                <tr class="mb-1" >
                    <td class="pr-5">{{ $flight['number'] }}</td>
                    <td class="pr-5">{{ $flight['short_departure_time'] }}</td>
                    <td class="pr-5">{{ $flight['short_arrival_time'] }}</td>
                    <td class="pr-5">{{ $flight['from_airport']['city'] }}</td>
                    <td class="pr-5">{{ $flight['to_airport']['city'] }}</td>
                    <td class="pr-5">{{ $flight['gate'] }}</td>
                    <td class="px-2 py-1 text-center">
                        <span class="inline-flex items-center justify-center min-w-[90px] h-6 text-xs rounded-md {{ $colors[$flight['flightstatus_id'] % 6] }}">
                            {{ $flight['flightstatus']['name'] }}
                        </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </x-itf.table>
        @endif
    </flux:modal>
    <x-itf.livewire-log :days="$days"/>
</div>
