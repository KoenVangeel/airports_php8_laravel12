<?php

namespace Database\Seeders;

use App\Models\Seatclass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeatclassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Seatclass::create(['name' => 'First Class']);
        Seatclass::create(['name' => 'Business Class']);
        Seatclass::create(['name' => 'Coach']);
    }
}
