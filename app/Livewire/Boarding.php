<?php

namespace App\Livewire;
use App\Models\Airport;
use App\Models\Booking;
use App\Models\Flight;
use Flux;
use Livewire\Component;
use Livewire\WithPagination;

class Boarding extends Component
{
    use WithPagination;

    public $perPage = 20;

    public $selectedFlight;
    public $waiting; // all waiting passengers
    public $boarded; // all passengers already in the plane

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

    public function render()
    {
        $flights = Flight::with('carrier', 'from_airport', 'to_airport')
            ->where('boarding' , '=', 1)
            ->withCount('bookings')
            ->orderBy(
                Airport::select('city')
                    ->whereColumn('airports.id', 'flights.from_airport_id')
            )
            ->paginate($this->perPage);

        return view('livewire.boarding', compact('flights'));
    }

    public function startBoarding(Flight $flight): void
    {
        $this->selectedFlight = $flight;

        $this->waiting = $this->readBookings($flight->id, false);
        $this->boarded = $this->readBookings($flight->id, true);

        Flux::modal('boardingModal')->show();
    }

    private function readBookings($flight_id, $boarded){
        return Booking::select('id', 'seat', 'passenger_id')
            ->with('passenger:id,firstname,lastname,passport_number')
            ->orderBy('seat','asc')
            ->where('flight_id', '=', $flight_id)
            ->where('boarded', '=', $boarded)
            ->get();
    }

    public function boardPassenger(Booking $booking): void
    {
        $booking->update([
            'boarded' => true,
        ]);

        $this->waiting = $this->readBookings($this->selectedFlight->id, false);
        $this->boarded = $this->readBookings($this->selectedFlight->id, true);
    }
}

