<?php

namespace App\Livewire\Admin;

use App\Models\Seatclass;
use Livewire\Attributes\Validate;
use App\Traits\NotificationsTrait;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Seatclasses extends Component
{
    use NotificationsTrait;
    use WithPagination;

    public $perPage = 5;

    // sort properties
    public $sortColumn = 'name';
    public $sortDirection = 'asc';

    // Property for the new seatclass input field
    #[Validate('required|min:3|max:30|unique:seatclasses,name', as: 'name for this seatclass' )]
    public $newName = '';

    // Properties for editing a seatclass
    public ?int $editingId = null;
    #[Validate('required|min:3|max:30|unique:seatclasses,name', as: 'name for this seatclass')]
    public string $editingName = '';

    public function updated($property, $value): void
    {
        if (in_array($property, ['perPage']))
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

    public function deleteConfirm(Seatclass $seatclass): void
    {
        $this->confirm(
            "Are you sure you want to delete the seatclass <b><i>{$seatclass->name}</i></b>?",
            [
                'next' => [
                    'onEvent' => 'delete-seatclass',
                    'seatclass' => $seatclass->id
                ]
            ]
        );
    }

    #[On('delete-seatclass')]
    public function deleteSeatclass(Seatclass $seatclass): void
    {
        $seatclass->delete();
        $this->toastSuccess(
            "The seatclass <b><i>{$seatclass->name}</i></b> has been deleted.",
            [
                'duration' => 3000,
                'position' => 'top-right'
            ]
        );
    }

    public function editSeatclass(Seatclass $seatclass): void
    {
        $this->editingId = $seatclass->id;
        $this->editingName = $seatclass->name;
    }

    public function updateSeatclass(Seatclass $seatclass): void
    {
        $this->editingName = trim($this->editingName);

        if (strtolower($this->editingName) === strtolower($seatclass->name) || $this->editingName === '') {
            $this->resetValues();
            return;
        }

        $this->validateOnly('editingName');
        $oldName = $seatclass->name;

        $seatclass->update([
            'name' => $this->editingName
        ]);

        $this->resetValues();

        $this->toastSuccess(
            "The seatclass <b><i>$oldName</i></b> has been updated to <b><i>$seatclass->name</i></b>",
            [
                'duration' => 3000,
                'position' => 'top-right'
            ]
        );
    }

    public function createNewSeatclass(): void
    {
        $this->validateOnly('newName');

        $seatclass = Seatclass::create([
            'name' => trim($this->newName)
        ]);

        $this->reset('newName');

        $this->toastSuccess(
            "The seatclass <b><i>$seatclass->name</i></b> has been added",
            [
                'duration' => 3000,
                'position' => 'top-right'
            ]
        );
    }

    public function render()
    {
        $seatclasses = Seatclass::withCount('bookings')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.seatclasses', compact('seatclasses'));
    }
}
