<?php

namespace Database\Seeders;

use App\Models\Flightstatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlightstatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Flightstatus::create(['name' => 'On Time']);
        Flightstatus::create(['name' => 'Delayed']);
        Flightstatus::create(['name' => 'Cancelled']);
        Flightstatus::create(['name' => 'Returned']);
        Flightstatus::create(['name' => 'Gate Closed']);
    }
}
