<?php

namespace Database\Seeders;

use App\Models\Passenger;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory;

class PassengerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        // And now, let's create a few passengers in our database
        for ($i = 0; $i < 1000; $i++) {
            $country = (rand (1,100) <= 85) ? 'United States of America' : $faker->country;
            Passenger::create([
                'firstname' => $faker->firstName,
                'lastname' => $faker->lastName,
                'email' => $faker->unique()->email,
                'nationality' => $country,
                'passport_number' => strtoupper($faker->languageCode) . '-' . $faker->bankAccountNumber
            ]);
        }
    }
}
