<div>
    <x-slot:title>Seatclasses</x-slot:title>
    <x-slot:description>Manage seatclasses</x-slot:description>

    <div class="flex items-start gap-4 mb-4">
        <flux:input
            wire:model.live.debounce.500ms="newName"
            wire:keydown.enter="createNewSeatclass()"
            wire:keydown.esc="resetValues()"
            wire:blur="resetValues()"
            class="w-56"
            icon="plus" placeholder="Create new seatclass" label="" clearable/>
    </div>

    <div class="my-4">{{ $seatclasses->links() }}</div>

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
                :sorted="$sortColumn === 'name'" :direction="$sortDirection">
                Name
            </x-itf.table.sortable-header>
            <x-itf.table.sortable-header
                wire:click="resort('bookings_count')"
                :sorted="$sortColumn === 'bookings_count'" :direction="$sortDirection">
                Bookings
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
        @foreach($seatclasses as $seatclass)
            <tr wire:key="{{ $seatclass->id }}">
                <td>{{ $seatclass->id }}</td>
                <td>
                    @if($editingId === $seatclass->id)
                        <flux:input
                            wire:model="editingName"
                            x-init="$el.focus()"
                            wire:keydown.enter="updateSeatclass({{ $seatclass->id }})"
                            wire:blur="resetValues()"
                            wire:keydown.escape="resetValues()"
                            label=""/>
                    @else
                        <span wire:click="editSeatclass({{ $seatclass->id }})">{{ $seatclass->name }}</span>
                    @endif
                </td>
                <td>{{ $seatclass->bookings_count }}</td>
                <td>
                    <flux:button
                        wire:click="editSeatclass({{ $seatclass->id }})"
                        tooltip="Edit {{ $seatclass->name }}"
                        icon="pencil-square"/>
                    <flux:button
                        wire:click="deleteConfirm({{ $seatclass->id }})"
                        tooltip="Delete {{ $seatclass->name }}"
                        icon="trash"/>
                </td>
            </tr>
        @endforeach
        </tbody>
    </x-itf.table>

    <div class="my-4">{{ $seatclasses->links() }}</div>

    <x-itf.livewire-log :seatclasses="$seatclasses"/>
</div>
