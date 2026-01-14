<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Account; 
use App\Room;
use App\Booking;    
use Illuminate\Support\Facades\Hash;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        Booking::truncate();
        Room::truncate();
        Account::truncate();

        // 1. Create Sample Admin Account
        $admin = Account::create([
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
        $room1 = Room::create(['room_number' => '101', 'room_type' => 'Single', 'price' => 1500.00, 'status' => 'Available']);
        $room2 = Room::create(['room_number' => '102', 'room_type' => 'Double', 'price' => 2200.00, 'status' => 'Available']);
        $room3 = Room::create(['room_number' => '103', 'room_type' => 'Single', 'price' => 1500.00, 'status' => 'Available']);
        $room4 = Room::create(['room_number' => '104', 'room_type' => 'Suite', 'price' => 3000.00, 'status' => 'Available']);
        $room5 = Room::create(['room_number' => '105', 'room_type' => 'Single', 'price' => 1500.00, 'status' => 'Available']);

        // 4. Create Available Bookings for each room
        Booking::create([
            'room_id' => $room1->room_id,
            'account_id' => $admin->account_id,
            'guest_name' => 'Available',
            'check_in_date' => now()->toDateString(),
            'check_out_date' => now()->toDateString(),
            'status' => 'Available',
            'total_amount' => 0
        ]);

        Booking::create([
            'room_id' => $room2->room_id,
            'account_id' => $admin->account_id,
            'guest_name' => 'Available',
            'check_in_date' => now()->toDateString(),
            'check_out_date' => now()->toDateString(),
            'status' => 'Available',
            'total_amount' => 0
        ]);

        Booking::create([
            'room_id' => $room3->room_id,
            'account_id' => $admin->account_id,
            'guest_name' => 'Available',
            'check_in_date' => now()->toDateString(),
            'check_out_date' => now()->toDateString(),
            'status' => 'Available',
            'total_amount' => 0
        ]);

        Booking::create([
            'room_id' => $room4->room_id,
            'account_id' => $admin->account_id,
            'guest_name' => 'Available',
            'check_in_date' => now()->toDateString(),
            'check_out_date' => now()->toDateString(),
            'status' => 'Available',
            'total_amount' => 0
        ]);

        Booking::create([
            'room_id' => $room5->room_id,
            'account_id' => $admin->account_id,
            'guest_name' => 'Available',
            'check_in_date' => now()->toDateString(),
            'check_out_date' => now()->toDateString(),
            'status' => 'Available',
            'total_amount' => 0
        ]);
    }
}