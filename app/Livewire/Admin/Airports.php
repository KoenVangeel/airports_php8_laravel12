<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\AirportForm;
use App\Models\Airport;
use App\Models\Airportstatus;
use App\Traits\NotificationsTrait;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Airports extends Component
{
    use WithPagination;
    use NotificationsTrait;

    // filter and pagination
    public $filter;
    public $airportstatus = 0;

    public $perPage = 5;

    // airport status list
    public $allairportstatuses;

    public $showModal = false;
    public AirportForm $form;

    // paginator terug naar pagina 1 als nieuw aantal per page
    public function updated($property, $value): void
    {
        // $property: The name of the current property being updated
        // $value: The value about to be set to the property (available but often not needed here)

        // Check if the updated property is one of the filters or pagination control
        if (in_array($property, ['perPage', 'filter', 'airportstatus']))
        {
            // If yes, reset the paginator back to page 1
            $this->resetPage();
        }
    }

    // reset the form and error bag
    public function resetValues()
    {
        $this->form->reset();
        $this->resetErrorBag();
    }

    public function newAirport()
    {
        $this->resetValues();
        $this->showModal = true;
    }

    public function createAirport()
    {
        $this->form->create();
        $this->showModal = false;
        $this->toastSuccess("The airport <b><i>{$this->form->city}</i></b> has been added");
        $this->resetValues();
    }

    public function editAirport(Airport $airport)
    {
        $this->resetValues();
        $this->form->fill($airport);
        $this->showModal = true;
    }

    public function updateAirport()
    {
        $this->form->update();
        $this->showModal = false;
        $this->toastInfo("The airport <b><i>{$this->form->city}</i></b> has been updated",
            [
                'position' => 'bottom-right'
            ]);
        $this->resetValues();
    }

    public function deleteConfirm(Airport $airport)
    {
        $this->confirm(
            "Are you sure you want to delete the airport <b><i>{$airport->city}</i></b>?",
            [
                'heading' => "DELETE",
                'confirmText' => 'Delete Airport',
                'class' => 'bg-red-100! dark:bg-red-500!',
                'next' => [
                    'onEvent' => 'delete-airport',
                    'airport' => $airport->id,
                ]
            ]
        );
    }

    // use Livewire\Attributes\On; niet vergeten, anders triggert hij het event niet
    #[On('delete-airport')]
    public function deleteAirport(Airport $airport)
    {
        $airport->delete();
        $this->toastInfo("The airport <b><i>{$airport->city}</i></b> has been deleted",
            [
                'position' => 'bottom-right'
            ]
        );
    }

    public function mount()
    {
        $this->allairportstatuses = Airportstatus::orderBy('name')->get();
    }

    public function render()
    {
        $airportstatuses =
            Airportstatus::has('airports')
                ->withCount('airports')
                ->orderBy('name')
                ->get();

        $airports = Airport::orderBy('city')
            ->with('airportstatus')
            ->searchCityOrCode($this->filter);

        if ($this->airportstatus != 0) {
            $airports = $airports->where('airportstatus_id', $this->airportstatus);
        }

        $airports = $airports->paginate($this->perPage);

        return view('livewire.admin.airports', compact('airports', 'airportstatuses'));
    }
}
