<?php

namespace App\Livewire\Admin;

use App\Models\Flightstatus;
use Livewire\Attributes\Validate;
use App\Traits\NotificationsTrait;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Flightstatuses extends Component
{
    use NotificationsTrait;
    use WithPagination;

    public $perPage = 5;
    public $search = '';

    // sort properties
    public $sortColumn = 'name';
    public $sortDirection = 'asc';

    // Property for the new flightstatus input field
    #[Validate('required|min:3|max:30|unique:flightstatuses,name', as: 'name for this flightstatus' )]
    public $newName = '';

    // Properties for editing a flightstatus
    public ?int $editingId = null;
    #[Validate('required|min:3|max:30|unique:flightstatuses,name', as: 'name for this flightstatus')]
    public string $editingName = '';

    public function updated($property, $value): void
    {
        if (in_array($property, ['perPage', 'search']))
        {
            $this->resetPage();
        }
    }

    public function resort($column): void
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function resetValues(): void
    {
        $this->reset('newName', 'editingName', 'editingId');
        $this->resetErrorBag();
    }

    public function deleteConfirm(Flightstatus $flightstatus): void
    {
        $this->confirm(
            "Are you sure you want to delete the flightstatus <b><i>{$flightstatus->name}</i></b>?",
            [
                'next' => [
                    'onEvent' => 'delete-flightstatus',
                    'flightstatus' => $flightstatus->id
                ]
            ]
        );
    }

    #[On('delete-flightstatus')]
    public function deleteFlightstatus(Flightstatus $flightstatus): void
    {
        $flightstatus->delete();
        $this->toastSuccess(
            "The flightstatus <b><i>{$flightstatus->name}</i></b> has been deleted.",
            [
                'duration' => 3000,
                'position' => 'top-right'
            ]
        );
    }

    public function editFlightstatus(Flightstatus $flightstatus): void
    {
        $this->editingId = $flightstatus->id;
        $this->editingName = $flightstatus->name;
    }

    public function updateFlightstatus(Flightstatus $flightstatus): void
    {
        $this->editingName = trim($this->editingName);

        if (strtolower($this->editingName) === strtolower($flightstatus->name) || $this->editingName === '') {
            $this->resetValues();
            return;
        }

        $this->validateOnly('editingName');
        $oldName = $flightstatus->name;

        $flightstatus->update([
            'name' => $this->editingName
        ]);

        $this->resetValues();

        $this->toastSuccess(
            "The flightstatus <b><i>$oldName</i></b> has been updated to <b><i>$flightstatus->name</i></b>",
            [
                'duration' => 3000,
                'position' => 'top-right'
            ]
        );
    }

    public function createNewFlightstatus(): void
    {
        $this->validateOnly('newName');

        $flightstatus = Flightstatus::create([
            'name' => trim($this->newName)
        ]);

        $this->reset('newName');

        $this->toastSuccess(
            "The flightstatus <b><i>$flightstatus->name</i></b> has been added",
            [
                'duration' => 3000,
                'position' => 'top-right'
            ]
        );
    }

    public function render()
    {
        $flightstatuses = Flightstatus::withCount('flights')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.flightstatuses', compact('flightstatuses'));
    }
}
