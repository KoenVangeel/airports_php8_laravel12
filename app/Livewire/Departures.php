<?php

namespace App\Livewire;

use App\Models\Airport;
use App\Models\Flight;
use Flux;
use Livewire\Component;
use Livewire\WithPagination;

class Departures extends Component
{
    use WithPagination;

    public $perPage = 12;

    public $selectedAirport;
    public $flights = [];

    public function render()
    {
        $airports = Airport::select('id', 'code', 'city', 'airportstatus_id')
            ->orderBy('code')
            ->with('airportstatus')
            ->with('departure_flights')
            ->withcount('departure_flights')
            ->paginate($this->perPage);

        return view('livewire.departures', compact('airports'));
    }

    public function showDepartures(Airport $airport): void
    {
        $this->selectedAirport = $airport;

        // select('etd', 'number', 'gate', 'boarding', 'flightstatus_id', 'to_airport_id') gaat niet meer
        // want als je eta niet selecteert dan fout op extra attribuut van flight: fullArrivalTime
        $this->flights = Flight::with('flightstatus')
            ->with('to_airport')
            ->where('from_airport_id', '=', $airport->id)
            ->orderByRaw("strftime('%H:%M', etd)")
            ->get();

        Flux::modal('departuresModal')->show();
    }

}
