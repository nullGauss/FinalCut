<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Seed users
        User::create([
            'name' => 'Admin FinalCut',
            'email' => 'admin@finalcut.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'User Percobaan',
            'email' => 'user@finalcut.test',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // Seed genres
        DB::table('genres')->insert([
            ['name' => 'Action'],
            ['name' => 'Drama'],
            ['name' => 'Horror'],
            ['name' => 'Comedy'],
            ['name' => 'Sci-Fi'],
        ]);

        // Seed cinema
        DB::table('cinemas')->insert([
            'name' => 'FinalCut Cineplex - Paskal',
            'address' => 'Jl. Pasirkaliki No. 25',
            'city' => 'Bandung',
        ]);

        // Seed studio
        DB::table('studios')->insert([
            'cinema_id' => 1,
            'name' => 'Studio 1',
            'capacity' => 40,
        ]);

        // Seed seats
        DB::table('seats')->insert([
            ['studio_id' => 1, 'seat_number' => 'A1', 'seat_type' => 'reguler'],
            ['studio_id' => 1, 'seat_number' => 'A2', 'seat_type' => 'reguler'],
            ['studio_id' => 1, 'seat_number' => 'B1', 'seat_type' => 'vip'],
            ['studio_id' => 1, 'seat_number' => 'B2', 'seat_type' => 'vip'],
        ]);
    }
}
