<?php

namespace Database\Seeders;

use App\Models\Carrier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarrierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Carrier::create(['name' => 'Southern', 'code' => 'ST']);
        Carrier::create(['name' => 'Quebec Air', 'code' => 'QA']);
        Carrier::create(['name' => 'Cardinal Airlines', 'code' => 'CA']);
        Carrier::create(['name' => 'Air West', 'code' => 'AW']);
        Carrier::create(['name' => 'Star Airlines', 'code' => 'SA']);
        Carrier::create(['name' => 'Air U.S.', 'code' => 'US']);
        Carrier::create(['name' => 'Omega International', 'code' => 'OI']);
        Carrier::create(['name' => 'Wings Flights', 'code' => 'WF']);
        Carrier::create(['name' => 'Sky United', 'code' => 'SK']);
    }
}
