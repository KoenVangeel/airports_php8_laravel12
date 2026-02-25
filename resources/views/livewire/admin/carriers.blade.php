<div>
    <x-slot:title>Carriers</x-slot:title>
    <x-slot:description>Manage carriers</x-slot:description>

    <div class="@container">
        <div class="grid grid-cols-10 @lg:grid-cols-20 @2xl:flex items-center gap-4 mb-4">
            <div class="col-span-10">
                <flux:input
                    wire:model.live.debounce.500ms="filter"
                    icon="magnifying-glass" placeholder="Filter by Code or Name" clearable/>
            </div>
            <div class="col-span-5">
                <flux:switch label="No logo" align="left" wire:model.live="noLogo"/>
            </div>
            <div class="col-span-5 relative -top-1">
                <flux:select wire:model.live="perPage" label="">
                    @foreach ([5,10,15,20] as $value)
                        <flux:select.option value="{{ $value }}">{{ $value }} carriers</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            <div class="col-span-5">
                <flux:button
                    wire:click="newCarrier()">
                    New Carrier
                </flux:button>
            </div>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-4"/>

    <div>
        <div class="my-4">{{ $carriers->links() }}</div>
        <div class="@container">
            <div class="grid grid-cols-1 @4xl:grid-cols-2 @7xl:grid-cols-3 gap-8">
                @forelse($carriers as $carrier)
                    <div wire:key="{{ $carrier->id }}" class="bg-white dark:bg-zinc-700 border border-zinc-300 dark:border-zinc-800 rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
                        <div class="p-5 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <img class="w-32 h-32 rounded-lg object-cover"
                                     src="{{ $carrier->image }}?{{ rand() }}" alt="{{ $carrier->name }}">
                                <div class="flex flex-col">
                                    <p class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $carrier->code }}</p>
                                    <p class="text-sm italic text-zinc-600 dark:text-zinc-300">{{ $carrier->name }}</p>
                                    <p class="text-sm italic text-zinc-600 dark:text-zinc-300 relative top-3"># Flights: {{ $carrier->flights_count }}</p>
                                </div>
                            </div>
                            <flux:button.group>
                                <flux:button
                                    wire:click="editCarrier({{ $carrier->id }})"
                                    tooltip="Edit"
                                    icon="pencil-square"/>
                                <flux:button
                                    wire:click="uploadLogoCarrier({{ $carrier->id }})"
                                    tooltip="Upload"
                                    icon="arrow-up-tray"/>
                                <flux:button
                                    wire:click="deleteConfirm({{ $carrier->id }})"
                                    tooltip="Delete {{ $carrier->name }}"
                                    icon="trash"/>
                            </flux:button.group>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center p-8 bg-white dark:bg-zinc-700 border border-zinc-300 dark:border-zinc-800 rounded-lg">No carriers found</div>
                @endforelse
            </div>
        </div>
        @if($perPage >= 10)
            <div class="my-4">{{ $carriers->links() }}</div>
        @endif
    </div>

    <flux:modal
        {{-- variant="flyout" --}}           {{-- Remove this line to show the modal in the center instead of on the right side --}}
        wire:model.self="showModal" {{-- wire:model.self is used to show the modal when the variable $showModal is true --}}
        class="w-[700px]"
        wire:keydown.escape="showModal = false"> {{-- Add escape key to close modal --}}
        <div class="space-y-4">
            <flux:heading size="xl">{{ $form->id ? 'Edit Carrier' : 'New Carrier' }}</flux:heading>
            <flux:separator/>
            <div class="flex flex-row gap-4 mt-4">
                <div class="flex-1 flex-col space-y-4">
                    <flux:input
                        wire:model.live.debounce.500ms="form.code"
                        label="Code"/>
                    <flux:input
                        wire:model.live.debounce.500ms="form.name"
                        label="Name"/>
                </div>
            </div>
            <div class="flex gap-4">
                <flux:spacer/>
                <flux:modal.close>
                    <flux:button variant="ghost" wire:click="resetValues()">Cancel</flux:button> {{-- [!code warning] Reset values on cancel --}}
                </flux:modal.close>
                <flux:button
                    wire:click="{{ $form->id ? 'updateCarrier()' : 'createCarrier()' }}"
                    variant="primary">Save
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal
        {{-- variant="flyout" --}}           {{-- Remove this line to show the modal in the center instead of on the right side --}}
        wire:model.self="showModalUpload" {{-- wire:model.self is used to show the modal when the variable $showModal is true --}}
        class="w-[700px]"
        wire:keydown.escape="showModalUpload = false"> {{-- Add escape key to close modal --}}
        <div class="space-y-4">
            <flux:heading size="xl">Upload Logo for {{ $form->code }}</flux:heading>
            <flux:separator/>
            <div class="flex flex-row gap-4 mt-4">
                <div class="flex-1 flex-col space-y-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Carrier: {{ $form->name }}</label>
                    <input type="file" wire:model="logo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-indigo-50 file:text-indigo-700
                        hover:file:bg-indigo-100"/>
                </div>
            </div>
            <div class="flex gap-4">
                <flux:spacer/>
                <flux:modal.close>
                    <flux:button variant="ghost" wire:click="resetValues()">Cancel</flux:button> {{-- [!code warning] Reset values on cancel --}}
                </flux:modal.close>
                <flux:button
                    wire:click="saveLogo()"
                    variant="primary">Upload
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <x-itf.livewire-log :carriers="$carriers"/>
</div>
