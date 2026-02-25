<div>
    <x-slot:title>Flights</x-slot:title>
    <x-slot:description>Manage flights</x-slot:description>

    <div class="@container">
        <div class="grid grid-cols-10 @lg:grid-cols-20 @2xl:flex items-center gap-4 mb-4">
            <div class="col-span-10">
                <flux:input
                    wire:model.live.debounce.500ms="flightnumber"
                    icon="magnifying-glass" placeholder="Filter by flight number" clearable/>
            </div>
            <div class="col-span-10">
                <flux:select wire:model.live="carrier">
                    <flux:select.option value="0">-- All Airlines --</flux:select.option>
                    @foreach($carriers as $carrier_item)
                        <flux:select.option value="{{ $carrier_item->id }}">{{ $carrier_item->name }} ({{ $carrier_item->code }})</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            <div class="col-span-10">
                <flux:select wire:model.live="from">
                    <flux:select.option value="0">-- All Departure Airports --</flux:select.option>
                    @foreach($airports as $airport_item)
                        <flux:select.option value="{{ $airport_item->id }}">{{ $airport_item->city }} ({{ $airport_item->code }})</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            <div class="col-span-10">
                <flux:select wire:model.live="to">
                    <flux:select.option value="0">-- All Arrival Airports --</flux:select.option>
                    @foreach($airports as $airport_item)
                        <flux:select.option value="{{ $airport_item->id }}">{{ $airport_item->city }} ({{ $airport_item->code }})</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            <div class="col-span-10">
                <flux:switch wire:model.live="boarding" label="Boarding" />
            </div>
            <div class="col-span-10">
                <flux:button
                    wire:click="newFlight()">
                    New Flight
                </flux:button>
            </div>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-4"/>

    <div>
        <div class="my-4">{{ $flights->links() }}</div>
        <x-itf.table cols="w-12, w-32, w-auto, w-36, w-36, w-20, w-auto, w-36">
            <thead>
            <tr>
                <th>ID</th>
                <th>Flight Number</th>
                <th>Airline</th>
                <th>ETD</th>
                <th>ETA</th>
                <th>Boarding</th>
                <th>From - To</th>
                <th>
                    <flux:select wire:model.live="perPage" label="">
                        @foreach ([10,20,30,40] as $value)
                            <flux:select.option value="{{ $value }}">{{ $value }} flights</flux:select.option>
                        @endforeach
                    </flux:select>
                </th>
            </tr>
            </thead>
            <tbody>
            @forelse($flights as $flight)
                <tr wire:key="{{ $flight->id }}">
                    <td>{{ $flight->id }}</td>
                    <td>{{ $flight->number }}</td>
                    <td>{{ $flight->carrier->name }}</td>
                    <td>{{ $flight->full_departure_time }}</td>
                    <td>{{ $flight->full_arrival_time }}</td>
                    <td>{{ $flight->boarding ? "X" : "" }}</td>
                    <td>{{ $flight->from_airport->city . ' - ' . $flight->to_airport->city }}</td>
                    <td>
                        <flux:button.group>
                            <flux:button
                                wire:click="editFlightSchedule({{ $flight->id }})"
                                tooltip="Edit Schedule"
                                icon="clock"/>
                            <flux:button
                                wire:click="editFlight({{ $flight->id }})"
                                tooltip="Edit"
                                icon="pencil-square"/>
                            <flux:button
                                wire:click="deleteConfirm({{ $flight->id }})"
                                tooltip="Delete {{ $flight->number }}"
                                icon="trash"/>
                        </flux:button.group>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No flights found</td>
                </tr>
            @endforelse
            </tbody>
        </x-itf.table>
        <div class="my-4">{{ $flights->links() }}</div>
    </div>

    <flux:modal
        variant="flyout"
        wire:model.self="showModal"
        class="w-[700px]"
        wire:keydown.escape="showModal = false">
        <div class="space-y-4">
            <flux:heading size="xl">{{ $form->id ? 'Edit Flight' : 'New Flight' }}</flux:heading>
            <flux:separator/>
            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model.live.debounce.500ms="form.number" label="Flight Number" />
                <flux:select wire:model="form.carrier_id" label="Carrier">
                    <flux:select.option value="">Select an airline</flux:select.option>
                    @foreach($carriers as $carrier_item)
                        <flux:select.option value="{{ $carrier_item->id }}">{{ $carrier_item->name }} ({{ $carrier_item->code }})</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <flux:input type="datetime-local" wire:model="form.etd" label="Departure date/time" />
                <flux:input type="datetime-local" wire:model="form.eta" label="Arrival date/time" />
            </div>
            <div class="grid grid-cols-2 gap-4">
                <flux:select wire:model="form.from_airport_id" label="From">
                    <flux:select.option value="">Select an airport</flux:select.option>
                    @foreach($airports as $airport_item)
                        <flux:select.option value="{{ $airport_item->id }}">{{ $airport_item->city }} ({{ $airport_item->code }})</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:select wire:model="form.to_airport_id" label="To">
                    <flux:select.option value="">Select an airport</flux:select.option>
                    @foreach($airports as $airport_item)
                        <flux:select.option value="{{ $airport_item->id }}">{{ $airport_item->city }} ({{ $airport_item->code }})</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            <flux:select wire:model="form.flightstatus_id" label="Flight Status">
                <flux:select.option value="">Select a flight status</flux:select.option>
                @foreach($flightstatuses as $flightstatus_item)
                    <flux:select.option value="{{ $flightstatus_item->id }}">{{ $flightstatus_item->name }}</flux:select.option>
                @endforeach
            </flux:select>

            {{--
            wire:model → “traditionele” Livewire binding, update niet onmiddellijk.
            wire:model.live → stuurt elke verandering meteen naar Livewire, real-time.
            --}}
            <flux:input wire:model="form.gate" label="Gate" />
            {{--
                IN HET MODEL: anders werkt de checkbox niet als je een update doet en in het model komt
                protected $casts = [
                    'boarding' => 'boolean',
                ];
            --}}
            <flux:checkbox wire:model="form.boarding">Boarding</flux:checkbox>
            <flux:input wire:model="form.price" label="Price" />

            <div class="flex gap-4">
                <flux:spacer/>
                <flux:modal.close>
                    <flux:button variant="ghost" wire:click="resetValues()">Cancel</flux:button>
                </flux:modal.close>
                <flux:button
                    wire:click="{{ $form->id ? 'updateFlight()' : 'createFlight()' }}"
                    variant="primary">Save
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal
        variant="flyout"
        wire:model.self="showModalUpdateSchedule"
        class="w-[500px]"
        wire:keydown.escape="showModalUpdateSchedule = false">
        <div class="space-y-4">
            <flux:heading size="xl">{{ $form->id ? 'Update Schedule: ' . $form->number : 'Update Schedule' }}</flux:heading>
            @if(isset($carriername))
                <p class="text-sm text-gray-500">Carrier: {{ $carriername }}</p>
            @endif
            <flux:separator/>
            <div class="space-y-4">
                <flux:input type="datetime-local" wire:model="form.etd" label="Departure date/time" />
                <flux:input type="datetime-local" wire:model="form.eta" label="Arrival date/time" />
            </div>
            <div class="flex gap-4">
                <flux:spacer/>
                <flux:modal.close>
                    <flux:button variant="ghost" wire:click="resetValues()">Cancel</flux:button>
                </flux:modal.close>
                <flux:button
                    wire:click="saveUpdatedSchedule()"
                    variant="primary">Update Schedule
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <x-itf.livewire-log :flights="$flights" :carriers="$carriers" :airports="$airports" :flightstatuses="$flightstatuses"/>
</div>
