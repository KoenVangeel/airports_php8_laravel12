<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\FlightForm;
use App\Models\Airport;
use App\Models\Carrier;
use App\Models\Flight;
use App\Models\Flightstatus;
use App\Traits\NotificationsTrait;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Flights extends Component
{
    use WithPagination;
    use NotificationsTrait;

    // filter and pagination
    public $flightnumber;   // search on flightnumber
    public $from = 0;       // search on from airport
    public $to = 0;         // search on carrier
    public $carrier = 0;    // search on to airport
    public $boarding = false;

    public $perPage = 20;

    // to fill the selects
    public $carriers;
    public $airports;
    public $flightstatuses;

    public $showModal = false;
    public $showModalUpdateSchedule = false;

    public FlightForm $form;

    // paginator terug naar pagina 1 als nieuw aantal per page
    public function updated($property, $value): void
    {
        // $property: The name of the current property being updated
        // $value: The value about to be set to the property (available but often not needed here)

        // Check if the updated property is one of the filters or pagination control
        if (in_array($property, ['flightnumber', 'boarding', 'from', 'to', 'carrier', 'perPage']))
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

    public function newFlight()
    {
        $this->resetValues();
        $this->showModal = true;
    }

    public function createFlight()
    {
        $this->form->create();
        $this->showModal = false;
        $this->toastSuccess("The flight <b><i>{$this->form->number}</i></b> has been added");
        $this->resetValues();
    }

    public function editFlight(Flight $flight)
    {
        $this->resetValues();
        $this->form->fill($flight);
        $this->showModal = true;
    }

    public function updateFlight()
    {
        $this->form->update();
        $this->showModal = false;
        $this->toastInfo("The flight <b><i>{$this->form->number}</i></b> has been updated",
            [
                'position' => 'bottom-right'
            ]);
        $this->resetValues();
    }

    public function deleteConfirm(Flight $flight)
    {
        $this->confirm(
            "Are you sure you want to delete the flight <b><i>{$flight->number}</i></b>?",
            [
                'heading' => "DELETE",
                'confirmText' => 'Delete Flight',
                'class' => 'bg-red-100! dark:bg-red-500!',
                'next' => [
                    'onEvent' => 'delete-flight',
                    'flight' => $flight->id,
                ]
            ]
        );
    }

    // use Livewire\Attributes\On; niet vergeten, anders triggert hij het event niet
    #[On('delete-flight')]
    public function deleteFlight(Flight $flight)
    {
        $flight->delete();
        $this->toastInfo("The flight <b><i>{$flight->number}</i></b> has been deleted",
            [
                'position' => 'bottom-right'
            ]
        );
    }

    public function mount()
    {
        $this->carriers = Carrier::orderBy('name')->get();
        $this->airports = Airport::orderBy('city')->get();
        $this->flightstatuses = Flightstatus::orderBy('name')->get();
    }

    public function render()
    {
        $flights = Flight::orderBy('etd')
            ->with('carrier')
            ->with('from_airport')
            ->with('to_airport')
            ->searchFlightnumber($this->flightnumber);

        if($this->boarding)
            $flights = $flights->where('boarding', true);
        if($this->carrier != 0)
            $flights = $flights->where('carrier_id', $this->carrier);
        if($this->from != 0)
            $flights = $flights->where('from_airport_id', $this->from);
        if($this->to != 0)
            $flights = $flights->where('to_airport_id', $this->to);
        $flights = $flights->paginate($this->perPage);

        return view('livewire.admin.flights', compact('flights'));
    }

}
