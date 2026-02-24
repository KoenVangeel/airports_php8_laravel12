<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'admin' => true,
                'password' => Hash::make('admin1234'),      // Use a secure password!
                'created_at' => now(),
                'email_verified_at' => now()
            ],
            [
                'name' => 'Jane Doe',
                'email' => 'jane.doe@example.com',
                'admin' => false,
                'password' => Hash::make('user1234'),       // Use a secure password!
                'created_at' => now(),
                'email_verified_at' => now()
            ],
            [
                'name' => 'Koen Vangeel',
                'email' => 'Koen.Vangeel@thomasmore.be',
                'admin' => true,
                'password' => Hash::make('admin1234'),       // Use a secure password!
                'created_at' => now(),
                'email_verified_at' => now()
            ]
        ]);
    }
}
