<div>
    <x-slot:title>Flightstatuses</x-slot:title>
    <x-slot:description>Manage flightstatuses</x-slot:description>

    {{-- instellen van toast en notifictations: https://itf-laravel-12.netlify.app/config/notifications --}}
    {{-- hier ook gebruik van x-itf.table en sortable header --}}

    <div class="flex items-start gap-4 mb-4">
        <flux:input
            {{-- .live modifier updates the property on the backend as the user types (debounced by 500ms)
                allowing for real-time validation feedback --}}
            wire:model.live.debounce.500ms="newName"
            {{-- user presses Enter in the input field, insert the new flightstatus --}}
            wire:keydown.enter="createNewFlightstatus()"
            {{-- bij klikken op esc, de foutmelding en waarde inputvak wegdoen, zie resetValues() in class --}}
            wire:keydown.esc="resetValues()"
            {{-- idem when user clicks outside the input field --}}
            wire:blur="resetValues()"
            class="w-56"
            icon="plus" placeholder="Create new flightstatus" label="" clearable/>
    </div>

    {{-- Pagination --}}
    <div class="my-4">{{ $flightstatuses->links() }}</div>

    <x-itf.table cols="w-12, w-52, w-auto, w-36">
        <thead>
        <tr>
            <x-itf.table.sortable-header
                wire:click="resort('id')"
                :sorted="$sortColumn === 'id'" :direction="$sortDirection">
                ID
            </x-itf.table.sortable-header>
            <x-itf.table.sortable-header
                wire:click="resort('name')"
                {{-- om door te geven naar sorted en direction props in sortable-header --}}
                :sorted="$sortColumn === 'name'" :direction="$sortDirection">
                Name
            </x-itf.table.sortable-header>
            <x-itf.table.sortable-header
                wire:click="resort('flights_count')"
                :sorted="$sortColumn === 'flights_count'" :direction="$sortDirection">
                Flights
            </x-itf.table.sortable-header>
            <th>
                <flux:select wire:model.live="perPage" label="">
                    @foreach ([5,10,15,20,25,30] as $value)
                        <flux:select.option value="{{ $value }}">{{ $value }} Records</flux:select.option>
                    @endforeach
                </flux:select>
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($flightstatuses as $flightstatus)
            <tr wire:key="{{ $flightstatus->id }}">
                <td>{{ $flightstatus->id }}</td>
                <td>
                    {{-- zien of de editingId hetzelfde is, dan in edit-modus zetten met input field --}}
                    @if($editingId === $flightstatus->id)
                        <flux:input
                            wire:model="editingName"
                            {{-- set the cursor on the input field. $el refers to the element itself --}}
                            x-init="$el.focus()"
                            wire:keydown.enter="updateFlightstatus({{ $flightstatus->id }})"
                            wire:blur="resetValues()"
                            wire:keydown.escape="resetValues()"
                            label=""/>
                    @else
                        <span wire:click="editFlightstatus({{ $flightstatus->id }})">{{ $flightstatus->name }}</span>
                    @endif
                </td>
                <td>{{ $flightstatus->flights_count }}</td>
                <td>
                    <flux:button.group>
                        {{-- Call editFlightstatus with the ID of the flightstatus --}}
                        {{-- klaar zetten van veldjes om door gebruiker aan te passen --}}
                        <flux:button
                            wire:click="editFlightstatus({{ $flightstatus->id }})"
                            tooltip="Edit {{ $flightstatus->name }}"
                            icon="pencil-square"/>
                        <flux:button
                            wire:click="deleteConfirm({{ $flightstatus->id }})"
                            tooltip="Delete {{ $flightstatus->name }}"
                            icon="trash"/>
                    </flux:button.group>
                </td>
            </tr>
        @endforeach
        </tbody>
    </x-itf.table>

    {{-- Pagination --}}
    <div class="my-4">{{ $flightstatuses->links() }}</div>

    <x-itf.livewire-log :flightstatuses="$flightstatuses"/>
</div>
