<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Account; 
use App\Room;    
use Illuminate\Support\Facades\Hash;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Sample Admin Account
        Account::create([
            'first_name' => 'System',
            'last_name' => 'Admin',
            'email' => 'admin@hotel.com',
            'password' => Hash::make('password123'),
            'role' => 'Admin',
        ]);

        // 2. Create Sample Receptionist Account
        Account::create([
            'first_name' => 'Jane',
            'last_name' => 'Staff',
            'email' => 'jane@hotel.com',
            'password' => Hash::make('password123'),
            'role' => 'Receptionist',
        ]);

        // 3. Create Sample Rooms
        Room::create(['room_number' => '101', 'room_type' => 'Single', 'price' => 100.00, 'status' => 'Available']);
        Room::create(['room_number' => '102', 'room_type' => 'Double', 'price' => 150.00, 'status' => 'Available']);
    }
}