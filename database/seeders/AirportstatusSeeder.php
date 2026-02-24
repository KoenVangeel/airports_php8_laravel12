<?php

namespace Database\Seeders;

use App\Models\Airportstatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AirportstatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Airportstatus::create(['name' => 'normal']);
        Airportstatus::create(['name' => 'caution']);
        Airportstatus::create(['name' => 'closed']);
    }
}
