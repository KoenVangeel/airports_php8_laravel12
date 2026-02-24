<div>
    <x-slot:title>Airports</x-slot:title>
    <x-slot:description>Manage airports</x-slot:description>

    <div class="@container">
        <div class="grid grid-cols-10 @lg:grid-cols-20 @2xl:flex items-center gap-4 mb-4">
            <div class="col-span-10">
                <flux:input
                    wire:model.live.debounce.500ms="filter"
                    icon="magnifying-glass" placeholder="Filter by Code or City" clearable/>
            </div>
            <div class="col-span-10">
                <flux:select wire:model.live="airportstatus">
                    <flux:select.option value="0">-- All Airportstatuses --</flux:select.option>
                    @foreach($airportstatuses as $status)
                        <flux:select.option value="{{ $status->id }}">{{ $status->name }}  ({{ $status->airports_count }})</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            <div class="col-span-10">
                <flux:button
                    wire:click="newAirport()">
                    New Airport
                </flux:button>
            </div>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-4"/>

    <div>
        <div class="my-4">{{ $airports->links() }}</div>
        <x-itf.table cols="w-12, w-20, w-20, w-auto, w-36">
            <thead>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>City</th>
                <th>Status</th>
                <th>
                    <flux:select wire:model.live="perPage" label="">
                        @foreach ([5,10,15,20] as $value)
                            <flux:select.option value="{{ $value }}">{{ $value }} Records</flux:select.option>
                        @endforeach
                    </flux:select>
                </th>
            </tr>
            </thead>
            <tbody>
            @forelse($airports as $airport)
            <tr wire:key="{{ $airport->id }}">
                <td>{{ $airport->id }}</td>
                <td>{{ $airport->code }}</td>
                <td>{{ $airport->city }}</td>
                <td>{{ $airport->airportstatus->name }}</td>
                <td>
                    <flux:button.group>
                        <flux:button
                            wire:click="editAirport({{ $airport->id }})"
                            tooltip="Edit"
                            icon="pencil-square"/>
                        <flux:button
                            wire:click="deleteConfirm({{ $airport->id }})"
                            tooltip="Delete {{ $airport->city }}"
                            icon="trash"/>
                    </flux:button.group>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="5">No airports found</td>
                </tr>
            @endforelse
            </tbody>
        </x-itf.table>
        @if($perPage >= 10)
            <div class="my-4">{{ $airports->links() }}</div>
        @endif
    </div>

    <flux:modal
        variant="flyout"            {{-- Remove this line to show the modal in the center instead of on the right side --}}
    wire:model.self="showModal" {{-- wire:model.self is used to show the modal when the variable $showModal is true --}}
        class="w-[700px]"
        wire:keydown.escape="showModal = false"> {{-- Add escape key to close modal --}}
        <div class="space-y-4">
            <flux:heading size="xl">{{ $form->id ? 'Edit Airport' : 'New Airport' }}</flux:heading>
            <flux:separator/>
            <div class="flex flex-row gap-4 mt-4">
                <div class="flex-1 flex-col space-y-4">
                    <flux:input
                        wire:model.live.debounce.500ms="form.code"
                        label="Code"/>
                    <flux:input
                        wire:model.live.debounce.500ms="form.city"
                        label="City"/>
                    <flux:select
                        wire:model.live="form.airportstatus_id"
                        label="Airportstatus">
                        <flux:select.option value="">Select an airportstatus</flux:select.option>
                        @foreach($allairportstatuses as $status)
                            <flux:select.option value="{{ $status->id }}">
                                {{ $status->name }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
            </div>
            <div class="flex gap-4">
                <flux:spacer/>
                <flux:modal.close>
                    <flux:button variant="ghost" wire:click="resetValues()">Cancel</flux:button> {{-- [!code warning] Reset values on cancel --}}
                </flux:modal.close>
                <flux:button
                    wire:click="{{ $form->id ? 'updateAirport()' : 'createAirport()' }}"
                    variant="primary">Save
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <x-itf.livewire-log :airports="$airports" :airportstatuses="$airportstatuses"/>
</div>
