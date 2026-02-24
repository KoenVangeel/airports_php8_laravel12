<?php

namespace Database\Seeders;

use App\Models\Airport;
use App\Models\Carrier;
use App\Models\Flight;
use App\Models\Flightstatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory;

class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        // Get the maximum airport and flightstatus ids
        $max_airport_id = Airport::max('id');
        $max_flightstatus_id = Flightstatus::max('id');

        // Read all carriers and pick one at random in loop
        $carriers = Carrier::select('id', 'code')
            ->get();

        // And now, let's create 300 flights in our database
        for ($i = 0; $i < 300; $i++) {
            // departure between now and 4 months
            $etd = $faker->dateTimeBetween('now', '+4 months');
            // flight time is between 1 and 8 hours
            $eta = $faker->dateTimeBetween(
                $etd->format('Y-m-d H:i:s').' +1 hours',
                $etd->format('Y-m-d H:i:s').' +8 hours');
            // 80% flights are on time
            $flightstatus_id = (rand (1,100) <= 80) ? 1 : $faker->numberBetween(2,$max_flightstatus_id);
            // pick one carrier at random
            $carrier = $carriers[rand (1,count($carriers) - 1)];
            $price = $this->randomDouble();

            $from_airport = $faker->numberBetween(1,$max_airport_id);
            do {
                $to_airport = $faker->numberBetween(1,$max_airport_id);
            } while ($to_airport == $from_airport);

            // to test our booking system, the last 20 flights will be between Buffalo (5) en Denver (9)
            // OUTBOUND
            if ($i >= 281 && $i <= 290) {
                $from_airport = 5;
                $to_airport = 9;
                $etd = $faker->dateTimeBetween('+60 days', '+63 days');
                // flight time is between 3 and 4 hours for Buffalo-Denver
                $eta = $faker->dateTimeBetween(
                    $etd->format('Y-m-d H:i:s').' +3 hours',
                    $etd->format('Y-m-d H:i:s').' +4 hours');
            }
            // INBOUND 10 days later
            if ($i >= 291 && $i <= 300) {
                $from_airport = 9;
                $to_airport = 5;
                $etd = $faker->dateTimeBetween('+70 days', '+73 days');
                // flight time is between 3 and 4 hours for Buffalo-Denver
                $eta = $faker->dateTimeBetween(
                    $etd->format('Y-m-d H:i:s').' +3 hours',
                    $etd->format('Y-m-d H:i:s').' +4 hours');
            }

            Flight::create([
                'number' => $carrier->code . $faker->numberBetween(1000,5000),
                'etd' => $etd,
                'eta' => $eta,
                'from_airport_id' => $from_airport,
                'to_airport_id' => $to_airport,
                'carrier_id' => $carrier->id,
                'flightstatus_id' => $flightstatus_id,
                'gate' => strtoupper($faker->randomLetter) . $faker->numberBetween(1,20),
                'boarding' => $faker->boolean(25),
                'price' => $price
            ]);
        }

    }

    function randomDouble()
    {
        $min = 50;
        $max = 500;
        $decimalPlaces = 1;
        $randomNumber = rand($min * pow(10, $decimalPlaces), $max * pow(10, $decimalPlaces)) / pow(10, $decimalPlaces);
        return $randomNumber;
    }

}
