<?php

namespace App\Livewire\Forms;

use App\Models\Flight;
use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FlightForm extends Form
{
    public $id = null;
    // gedaan met rules (beetje verder) om uniek te maken
    // #[Validate('required', as: 'flightnumber')]
    // #[Validate gaat alleen voor statische dingen, dus met rules!
    public $number = null;
    public $etd = null;
    public $eta = null;
    #[Validate('required|exists:airports,id', as: 'departure airport')]
    public $from_airport_id = null;
    #[Validate('required|exists:airports,id', as: 'arrival airport')]
    public $to_airport_id = null;
    #[Validate('required|exists:carriers,id', as: 'carrier')]
    public $carrier_id = null;
    #[Validate('required|exists:flightstatuses,id', as: 'flight status')]
    public $flightstatus_id = null;
    #[Validate('required', as: 'gate')]
    public $gate = null;
    public $boarding = false;
    #[Validate('required|numeric|min:0', as: 'price')]
    public $price = null;

    // special validation rule for code for insert and update!
    public function rules()
    {
        return [
            'number' => "required|unique:flights,number,{$this->id}",
        ];
    }

    // $validationAttributes is used to replace the attribute name in the error message
    protected $validationAttributes = [
        'number' => 'flight number',
    ];

    // Create a new flight
    public function create()
    {
        $this->validate();
        Flight::create([
            'number' => $this->number,
            'etd' => Carbon::parse($this->etd)->format('Y-m-d H:i:s'),
            'eta' => Carbon::parse($this->eta)->format('Y-m-d H:i:s'),
            'from_airport_id' => $this->from_airport_id,
            'to_airport_id' => $this->to_airport_id,
            'carrier_id' => $this->carrier_id,
            'flightstatus_id' => $this->flightstatus_id,
            'gate' => $this->gate,
            'boarding' => $this->boarding,
            'price' => $this->price,
        ]);
    }

    // Update an selected flight
    public function update()
    {
        $flight = Flight::findOrFail($this->id);
        $this->validate();
        $flight->update([
            'number' => $this->number,
            'etd' => Carbon::parse($this->etd)->format('Y-m-d H:i:s'),
            'eta' => Carbon::parse($this->eta)->format('Y-m-d H:i:s'),
            'from_airport_id' => $this->from_airport_id,
            'to_airport_id' => $this->to_airport_id,
            'carrier_id' => $this->carrier_id,
            'flightstatus_id' => $this->flightstatus_id,
            'gate' => $this->gate,
            'boarding' => $this->boarding,
            'price' => $this->price,
        ]);
    }

    // Only update the ETA and ETD
    public function updateFlightSchedule() {
        $flight = Flight::findOrFail($this->id);
        $this->validate();
        $flight->update([
            'etd' => Carbon::parse($this->etd)->format('Y-m-d H:i:s'),
            'eta' => Carbon::parse($this->eta)->format('Y-m-d H:i:s'),
        ]);
    }

}
