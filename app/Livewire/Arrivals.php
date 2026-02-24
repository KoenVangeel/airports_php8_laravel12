<?php

namespace App\Livewire;

use App\Models\Airport;
use Http;
use Livewire\Component;
use Livewire\WithPagination;
use Flux;

class Arrivals extends Component
{
    use WithPagination;

    public $perPage = 6;

    public $selectedAirport;

    public function render()
    {
        $airports = Airport::orderBy('code')
            ->with('airportstatus')
            ->with('arrival_flights')
            ->with('arrival_flights.flightstatus')
            ->with('arrival_flights.from_airport')
            ->with('arrival_flights.carrier')
            ->paginate($this->perPage);

        //dd($airports);

        return view('livewire.arrivals', compact('airports'));
    }

    public function showWeather(Airport $airport): void
    {
        $this->selectedAirport = $airport;

        $weatherUrl = 'http://api.openweathermap.org/data/2.5/weather?q=' .
            $this->selectedAirport->city .
            '&units=metric&appid=563bd02c8e48c9cec4a6a9a0acd8e896';

        try {
            $response = Http::timeout(10)->get($weatherUrl)->json();

            $this->selectedAirport->condition = $response['weather'][0]['main'];
            $this->selectedAirport->description = $response['weather'][0]['description'];
            $this->selectedAirport->temperature = $response['main']['temp'];
            $this->selectedAirport->latitude = $response['coord']['lon'];
            $this->selectedAirport->longitude = $response['coord']['lat'];

        } catch (\Exception $e) {
            report($e);                // Handle exceptions (e.g., timeout, network error, invalid JSON)
            $this->selectedAirport = null;
        }

        // Show the modal with the name attribute "weatherModal"
        Flux::modal('weatherModal')->show();
    }
}
