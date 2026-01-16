<?php

namespace App\Http\Controllers;

use App\Room;
use App\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $rooms = Room::latest()->paginate(10);
        
        // Get current guests with "Checked-In" status
        $currentGuests = Booking::where('status', 'Checked-In')
            ->with(['room', 'account'])
            ->get();
        
        // Get one room of each type for the room cards
        $singleRoom = Room::where('room_type', 'Single')->first();
        if (!$singleRoom) {
            $singleRoom = Room::create(['room_number' => '101', 'room_type' => 'Single', 'price' => 1309, 'status' => 'Available']);
        }
        
        $doubleRoom = Room::where('room_type', 'Double')->first();
        if (!$doubleRoom) {
            $doubleRoom = Room::create(['room_number' => '102', 'room_type' => 'Double', 'price' => 2500, 'status' => 'Available']);
        }
        
        $suiteRoom = Room::where('room_type', 'Suite')->first();
        if (!$suiteRoom) {
            $suiteRoom = Room::create(['room_number' => '103', 'room_type' => 'Suite', 'price' => 4500, 'status' => 'Available']);
        }
        
        return view('rooms.index', compact('rooms', 'currentGuests', 'singleRoom', 'doubleRoom', 'suiteRoom'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $lastRoom = Room::orderBy('room_id', 'desc')->first();
        $nextRoomNumber = ($lastRoom ? intval($lastRoom->room_number) : 100) + 1;
        return view('rooms.create', compact('nextRoomNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'room_type' => 'required',
        'price' => 'required|numeric',
    ]);

    // Auto-generate room number based on the next available number
    $lastRoom = Room::orderBy('room_id', 'desc')->first();
    $nextRoomNumber = ($lastRoom ? intval($lastRoom->room_number) : 100) + 1;

    $data = $request->all();
    $data['room_number'] = (string)$nextRoomNumber;

    Room::create($data);

    return redirect()->route('rooms.index')
                     ->with('success','Room created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $room = Room::findOrFail($id);
        
        // Room descriptions
        $descriptions = [
            'Single' => 'Our Single Room is thoughtfully designed for guests who value comfort, privacy, and simplicity. Featuring cozy interiors and modern amenities, it\'s ideal for solo travelers seeking relaxation after a long day.

Enjoy essential amenities including air conditioning, a private bathroom, complimentary Wi-Fi, and a dedicated workspace. Whether you\'re unwinding after a long day or preparing for tomorrow\'s plans, the Single Room offers a peaceful retreat at an exceptional value.',
            'Double' => 'Our Double Room offers spacious comfort perfect for couples or those seeking extra space. With elegant furnishings and modern amenities, it\'s designed for guests who want to enjoy a luxurious retreat with plenty of room to relax.

Complete with premium bedding, en-suite bathroom with premium toiletries, high-speed Wi-Fi, and a comfortable seating area. The Double Room combines style and comfort for an unforgettable stay.',
            'Suite' => 'Our Suite is the ultimate in luxury and spaciousness. Perfect for those seeking premium accommodations with an exclusive experience. Featuring separate living and sleeping areas, elegant décor, and high-end amenities.

Enjoy a private balcony or terrace, full bathroom with spa features, premium entertainment systems, concierge service, and exclusive access to our premium facilities. The Suite sets the standard for luxury hospitality.',
        ];
        
        // Room images based on type
        $roomImages = [
            'Single' => [
                'main' => 'images/single.jpg',
                'thumb1' => 'images/room-main.jpg',
                'thumb2' => 'images/room-2.jpg',
            ],
            'Double' => [
                'main' => 'images/double.jpg',
                'thumb1' => 'images/room-3.jpg',
                'thumb2' => 'images/room-4.jpg',
            ],
            'Suite' => [
                'main' => 'images/suite.jpg',
                'thumb1' => 'images/room-5.jpg',
                'thumb2' => 'images/room-6.jpg',
            ],
        ];
        
        $description = $descriptions[$room->room_type] ?? 'Experience comfort and luxury in our thoughtfully designed rooms.';
        $images = $roomImages[$room->room_type] ?? $roomImages['Single'];
        
        // Get all bookings for this room to prevent double booking
        $bookings = Booking::where('room_id', $id)->get();
        $bookedDates = [];
        foreach ($bookings as $booking) {
            $startDate = new \DateTime($booking->check_in_date);
            $endDate = new \DateTime($booking->check_out_date);
            
            while ($startDate < $endDate) {
                $bookedDates[] = $startDate->format('Y-m-d');
                $startDate->modify('+1 day');
            }
        }
        
        return view('rooms.show', compact('room', 'description', 'images', 'bookedDates'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $room = Room::findOrFail($id);
        return view('rooms.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $room = Room::findOrFail($id);
        $request->validate([
        'room_number' => 'required|unique:rooms,room_number,'.$room->room_id.',room_id',
        'room_type' => 'required',
        'price' => 'required|numeric',
        'status' => 'required',
    ]);

    $room->update($request->all());

        return redirect()->route('rooms.index')
                     ->with('success', 'Room updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()->route('rooms.index')
                     ->with('success', 'Room deleted successfully');
    }

    /**
     * Get all available rooms (API endpoint)
     */
    public function getAvailableRooms()
    {
        $rooms = Room::where('status', 'Available')->get();
        return response()->json($rooms);
    }

    /**
     * Toggle room maintenance status
     */
    public function toggleMaintenance(string $id)
    {
        $room = Room::findOrFail($id);
        
        // Toggle between Available and Under Maintenance
        $newStatus = $room->status === 'Available' ? 'Under Maintenance' : 'Available';
        $room->update(['status' => $newStatus]);

        return redirect()->route('bookings.status')
                     ->with('success', 'Room status updated to ' . $newStatus);
    }
}
