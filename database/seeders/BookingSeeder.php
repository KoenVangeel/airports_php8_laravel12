<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Flight;
use App\Models\Passenger;
use App\Models\Seatclass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        $passengers = Passenger::select('id') // read all passengers
            ->get();
        $max_flight_id = Flight::max('id');
        $max_seatclass_id = Seatclass::max('id');

        // And now, let's create a few bookings for our passengers
        foreach($passengers as $passenger) {
            // a passenger has 1 to 4 bookings
            $number_bookings = rand (1,4);
            for ($i = 0; $i < $number_bookings; $i++) {
                Booking::create([
                    'flight_id' => $faker->numberBetween(1, $max_flight_id),
                    'passenger_id' => $passenger->id,
                    'seatclass_id' => $faker->numberBetween(1, $max_seatclass_id),
                    'seat' => $faker->numberBetween(1, 26) . $faker->regexify('[A-F]'),
                    'checkedin' => $faker->boolean(20),
                    'payed' => $faker->boolean(80),
                    'boarded' => $faker->boolean(15)
                ]);
            }
        }
    }
}
