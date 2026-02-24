<?php

namespace App\Livewire\Admin;

use App\Models\Airportstatus;
use Livewire\Attributes\Validate;   // NIET VERGETEN!!! anders werkt #[Validate en $this->validateOnly( niet
use App\Traits\NotificationsTrait;  // Om notificaties rechtsboven te tonen
use Livewire\Attributes\On;         // This is needed to listen for Livewire events. delete wordt getriggerd van confirmation dialog, daarop moet je luisteren
use Livewire\Component;
use Livewire\WithPagination;

class Airportstatuses extends Component
{
    use NotificationsTrait;
    use WithPagination;

    public $perPage = 5;

    // sort properties
    public $sortColumn = 'name';
    public $sortDirection = 'asc';

    // Property for the new airportstatus input field
    // Add validation rules
    #[Validate('required|min:3|max:30|unique:airportstatuses,name', as: 'name for this airportstatus' )]
    public $newName = '';

    // Properties for editing an airportstatus
    public ?int $editingId = null; // Use ?int for type safety, allowing null
    #[Validate('required|min:3|max:30|unique:airportstatuses,name', as: 'name for this airportstatus')]
    public string $editingName = '';

    // paginator terug naar pagina 1 als nieuw aantal per page
    public function updated($property, $value): void
    {
        // $property: The name of the current property being updated
        // $value: The value about to be set to the property (available but often not needed here)

        // Check if the updated property is one of the filters or pagination control
        if (in_array($property, ['perPage']))
        {
            // If yes, reset the paginator back to page 1
            $this->resetPage();
        }
    }

    // Method to handle column sorting
    public function resort($column): void
    {
        // If clicking the same column, toggle direction
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        }
        // Otherwise, set new column and default direction
        else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function resetValues(): void
    {
        $this->reset('newName', 'editingName', 'editingId');
        $this->resetErrorBag();
    }

    // Method to show deletion confirmation dialog
    public function deleteConfirm(Airportstatus $airportstatus): void
    {
        // Use the confirm helper from NotificationsTrait
        $this->confirm(
            "Are you sure you want to delete the airportstatus <b><i>{$airportstatus->name}</i></b>?", // Confirmation message
            [
                'next' => [                               // Configuration for the action *after* confirmation
                    'onEvent' => 'delete-airportstatus',  // Livewire event to dispatch on confirm
                    'airportstatus' => $airportstatus->id         // Parameter(s) for the event listener (pass ID)
                ]
            ]
        );
    }

    // Method to actually delete the airportstatus if confirmed
    #[On('delete-airportstatus')] // Listen for the 'delete-airportstatus' event dispatched by the confirmation dialog
    public function deleteGenre(Airportstatus $airportstatus): void // Route Model Binding resolves ID from event data
    {
        // Delete the airportstatus from the database
        $airportstatus->delete();
        // Show success feedback
        $this->toastSuccess(
            "The airportstatus <b><i>{$airportstatus->name}</i></b> has been deleted.",
            [
                'duration' => 3000,
                'position' => 'top-right'
            ]
        );
    }

    // Method to set up edit mode for a specific airportstatus
    // Dus zorg dat de geklikte kan aangepast worden door gebruiker
    public function editAirportstatus(Airportstatus $airportstatus): void
    {
        // Set the ID of the airportstatus being edited
        $this->editingId = $airportstatus->id;
        // Pre-fill the input with the current name
        $this->editingName = $airportstatus->name;
    }

    // Method to update a airportstatus
    public function updateAirportstatus(Airportstatus $airportstatus): void
    {
        // Trim the input before comparing and updating the airportstatus name
        $this->editingName = trim($this->editingName);

        // If the name is not changed or is empty, do nothing
        if (strtolower($this->editingName) === strtolower($airportstatus->name) || $this->editingName === '') {
            $this->resetValues();
            return;
        }

        // Validate only the editingName property based on its #[Validate] attribute
        $this->validateOnly('editingName');

        // Store the old airportstatus name for the toast message
        $oldName = $airportstatus->name;

        // Update the airportstatus name
        $airportstatus->update([
            'name' => $this->editingName
        ]);

        // Reset the input field and error messages
        $this->resetValues();

        // Add a toast response
        $this->toastSuccess(
            "The airportstatus <b><i>$oldName</i></b> has been updated to <b><i>$airportstatus->name</i></b>",
            [
                'duration' => 3000,
                'position' => 'top-right'
            ]
        );
    }

    // Method to create a new airportstatus
    public function createNewAirportStatus(): void
    {
        // Validate only the newName property based on its #[Validate] attribute
        $this->validateOnly('newName');

        // Create the new airportstatus
        $airportstatus = Airportstatus::create([
            'name' => trim($this->newName)
        ]);

        // Reset the input field
        $this->reset('newName');

        // Add a toast response
        $this->toastSuccess(
            "The airportstatus <b><i>$airportstatus->name</i></b> has been added",
            [
                'duration' => 3000,  // Toast is displayed for 3 seconds in the top right corner
                'position' => 'top-right'
            ]
        );
    }

    public function render()
    {
        $airportstatuses = Airportstatus::withCount('airports')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.airportstatuses', compact('airportstatuses'));
    }
}
