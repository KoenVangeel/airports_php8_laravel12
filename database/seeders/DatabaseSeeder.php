<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AirportstatusSeeder::class,
            CarrierSeeder::class,
            FlightstatusSeeder::class,
            SeatclassSeeder::class,
            AirportSeeder::class,
            FlightSeeder::class,
            PassengerSeeder::class,
            BookingSeeder::class
        ]);
    }
}
