<?php

namespace Database\Seeders;

use App\Models\Airport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Airport::create(['code' => 'LVG', 'city' => 'Las Vegas', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'MEM', 'city' => 'Memphis', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'SFO', 'city' => 'San Francisco', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'SLC', 'city' => 'Salt Lake City', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'BUF', 'city' => 'Buffalo', 'airportstatus_id' => 3]);
        Airport::create(['code' => 'CLE', 'city' => 'Cleveland', 'airportstatus_id' => 2]);
        Airport::create(['code' => 'ELP', 'city' => 'El Paso', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'FLL', 'city' => 'Fort Lauderdale', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'DEN', 'city' => 'Denver', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'DES', 'city' => 'Des Moines', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'DET', 'city' => 'Detroit', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'DFW', 'city' => 'Dallas', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'HCI', 'city' => 'Houston', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'HON', 'city' => 'Honolulu', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'LAX', 'city' => 'Los Angeles', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'LGA', 'city' => 'New York', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'MON', 'city' => 'Minneapolis', 'airportstatus_id' => 2]);
        Airport::create(['code' => 'MSP', 'city' => 'Montreal', 'airportstatus_id' => 2]);
        Airport::create(['code' => 'ORD', 'city' => 'Chicago', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'PHL', 'city' => 'Philadelphia', 'airportstatus_id' => 2]);
        Airport::create(['code' => 'PHX', 'city' => 'Phoenix', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'SAJ', 'city' => 'San Diego', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'SEA', 'city' => 'Seattle', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'STL', 'city' => 'St Louis', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'TOR', 'city' => 'Toronto', 'airportstatus_id' => 2]);
        Airport::create(['code' => 'VCR', 'city' => 'Vancouver', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'ATL', 'city' => 'Atlanta', 'airportstatus_id' => 1]);
        Airport::create(['code' => 'AUS', 'city' => 'Austin', 'airportstatus_id' => 1]);
    }
}
