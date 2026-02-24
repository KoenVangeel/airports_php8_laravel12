<?php

namespace App\Livewire;

use App\Models\Flight;
use Carbon\Carbon;
use Flux;
use Livewire\Component;

class Calendar extends Component
{
    public $currentMonth;
    public $currentYear;

    public $days;
    public $selectedDay;

    public function mount()
    {
        $this->currentMonth = date('m');
        $this->currentYear = date('Y');
    }

    public function render()
    {
        // create an array with one day / index
        $this->days = $this->getDaysOfMonth($this->currentMonth, $this->currentYear);
        // add the flights as a subarray to every day
        $this->addFlightsToDays($this->days);

        return view('livewire.calendar');
    }

    public function nextMonth(): void
    {
        $this->currentMonth++;
        if ($this->currentMonth == 13) {
            $this->currentMonth = 1;
            $this->currentYear++;
        }
    }

    public function previousMonth(): void
    {
        $this->currentMonth--;
        if ($this->currentMonth == 0) {
            $this->currentMonth = 12;
            $this->currentYear--;
        }
    }

    public function showFlights($dayIndex): void
    {
        $this->selectedDay = $this->days[$dayIndex];
        if (count($this->selectedDay['flights']) != 0) {
            Flux::modal('flightsModal')->show();
        }
    }

    private function addFlightsToDays(&$days)
    {
        // find all flights of currentYear and Month
        $flights = Flight::with('carrier')
            ->with('from_airport')
            ->with('to_airport')
            ->with('flightstatus')
            ->whereYear('etd', '=', $this->currentYear)
            ->whereMonth('etd', '=', $this->currentMonth)
            ->orderBy('etd')
            ->get()
            ->toArray();

        // add these flights to the correct day
        foreach ($flights as $flight){
            $dayNumber = Carbon::parse($flight['etd'])->format('d');
            // use &$day if you want to change day
            foreach ($days as $key => &$day) {
                if ($day['day'] == (int) $dayNumber and $day['color'] == 'white') {
                    $day['flights'][] = $flight;
                }
            }
        }
    }

    private function previousMonthDays ($month, $year)
    {
        $lastDay = mktime(0, 0, 0, $month, 1 - 1, $year);
        return date("j", $lastDay);
    }

    private function getDaysOfMonth($month, $year) {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $firstDayOfMonth = date('N', strtotime("$year-$month-01"));
        $lastDayOfMonth = date('N', strtotime("$year-$month-$daysInMonth"));
        $days = array();

        // Add days of previous month if first day is not Monday
        if ($firstDayOfMonth != 1) {
            $prevMonthDays = $this->previousMonthDays ($month, $year);
            for ($i = 1; $i <= $firstDayOfMonth - 1; $i++) {
                $day = [ 'day' => $prevMonthDays - $firstDayOfMonth  + $i + 1,
                    'month' => $month - 1,
                    'year' => $year,
                    'color' => 'gray',
                    'flights' => array() ];
                $days[] = $day;
            }
        }

        // Add days of current month
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $day = [ 'day' => $i,
                'month' => $month,
                'year' => $year,
                'color' => 'white',
                'flights' => [] ];
            $days[] = $day;
        }

        // Add days of next month if last day is not Sunday
        if ($lastDayOfMonth != 7) {
            $nextMonthDays = 1;
            for ($i = 0 ; $i < 7 - $lastDayOfMonth; $i++) {
                $day = [ 'day' => $nextMonthDays,
                    'month' => $month + 1,
                    'year' => $year,
                    'color' => 'gray',
                    'flights' => array() ];
                $days[] = $day;
                $nextMonthDays++;
            }
        }

        return $days;
    }

}
