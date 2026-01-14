<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Room;
use App\Account;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = Carbon::now()->toDateString();
        
        // Displays reservations (Booked bookings) - those who booked in advance and arriving today or later
        $reservations = Booking::with(['room', 'account'])
                               ->where('status', 'Booked')
                               ->whereDate('check_in_date', '>=', $today)
                               ->orderBy('check_in_date', 'asc')
                               ->get();
        
        // Displays all booked transactions
        $booked = Booking::with(['room', 'account'])
                        ->where('status', 'Booked')
                        ->orderBy('check_in_date', 'asc')
                        ->get();
        
        // Displays check-outs (Checked-In bookings) - those already checked in
        $checkouts = Booking::with(['room', 'account'])
                            ->where('status', 'Checked-In')
                            ->orderBy('check_out_date', 'asc')
                            ->get();
        
        // Displays completed bookings
        $completed = Booking::with(['room', 'account'])
                           ->where('status', 'Completed')
                           ->orderBy('check_out_date', 'desc')
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
        
        return view('bookings.index', compact('reservations', 'booked', 'checkouts', 'completed', 'singleRoom', 'doubleRoom', 'suiteRoom'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $selectedRoomId = request()->input('room_id');
        
        // If a room is pre-selected, filter rooms by that room's type
        if ($selectedRoomId) {
            $selectedRoom = \App\Room::findOrFail($selectedRoomId);
            $roomType = $selectedRoom->room_type;
            $rooms = \App\Room::where('room_type', $roomType)
                              ->where('status', '!=', 'Under Maintenance')
                              ->get();
        } else {
            // Load available rooms (exclude Under Maintenance rooms)
            $rooms = \App\Room::where('status', '!=', 'Under Maintenance')->get();
        }
        
        // Get booked dates for the selected room if provided
        $bookedDates = [];
        if ($selectedRoomId) {
            $bookedBookings = Booking::where('room_id', $selectedRoomId)
                                    ->where('status', 'Booked')
                                    ->get();
            
            foreach ($bookedBookings as $booking) {
                $start = Carbon::parse($booking->check_in_date);
                $end = Carbon::parse($booking->check_out_date);
                
                while ($start->lte($end)) {
                    $bookedDates[] = $start->toDateString();
                    $start->addDay();
                }
            }
        }
        
        return view('bookings.create', compact('rooms', 'selectedRoomId', 'bookedDates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
        'room_id' => 'required',
        'guest_name' => 'required',
        'check_in_date' => 'required|date',
        'check_out_date' => 'required|date|after:check_in_date',
        'total_amount' => 'required|numeric|min:0',
    ]);

    // This logic ensures the booking is tied to the logged-in staff member
    $data = $request->all();
    $data['account_id'] = auth()->user()->account_id ?? auth()->id();
    $data['status'] = 'Booked';
    
    Booking::create($data);
    
    // Update the room status to "Booked" (no longer available)
    $room = Room::findOrFail($data['room_id']);
    $room->update(['status' => 'Booked']);

    return redirect()->route('bookings.index')
                     ->with('success','Booking confirmed.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Display details of a specific booking.
        $booking = Booking::findOrFail($id);
        return view('bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Allows receptionist to modify existing bookings.
        $booking = Booking::findOrFail($id);
        $rooms = Room::all();
        $accounts = Account::all();
        
        // Get all booked dates for calendar display (excluding current booking's dates)
        $allBookings = Booking::where('status', 'Booked')
                              ->where('booking_id', '!=', $id)
                              ->get();
        
        $allBookingDates = [];
        foreach ($allBookings as $b) {
            $start = Carbon::parse($b->check_in_date);
            $end = Carbon::parse($b->check_out_date);
            
            while ($start->lte($end)) {
                $allBookingDates[] = $start->toDateString();
                $start->addDay();
            }
        }
        
        return view('bookings.edit', compact('booking', 'rooms', 'accounts', 'allBookingDates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Save the changes made to a booking.
        $booking = Booking::findOrFail($id);
        $request->validate([
        'room_id' => 'required',
        'guest_name' => 'required',
        'check_in_date' => 'required|date',
        'check_out_date' => 'required|date|after:check_in_date',
        'status' => 'required'
    ]);

        $booking->update($request->all());

        return redirect()->route('bookings.index')
                     ->with('success', 'Booking updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Allows the receptionist to cancel a booking.
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'Cancelled']);
        
        // Mark the room as available
        $booking->room->update(['status' => 'Available']);

        return redirect()->route('bookings.status')
                     ->with('success', 'Booking cancelled successfully. Room is now available for new bookings.');
    }

    /**
     * Display bookings organized by status.
     */
    public function showByStatus()
    {
        // Get all bookings organized by their status
        $bookings = Booking::with(['room', 'account'])
                           ->orderBy('status')
                           ->orderBy('room_id', 'asc')
                           ->orderBy('check_in_date', 'asc')
                           ->get()
                           ->groupBy('status');
        
        // Get all rooms for the Available section
        $rooms = Room::all();
        
        return view('bookings.status', compact('bookings', 'rooms'));
    }

    /**
     * Mark a booking as completed (check out).
     */
    public function checkin(string $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'Checked-In']);
        
        return redirect()->route('bookings.index')
                     ->with('success', 'Guest checked in successfully.');
    }

    public function checkout(string $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'Completed']);
        
        // Mark the room as available
        $booking->room->update(['status' => 'Available']);
        
        // Create a new "Available" booking record for the room
        Booking::create([
            'room_id' => $booking->room_id,
            'account_id' => auth()->user()->account_id,
            'guest_name' => 'Available',
            'check_in_date' => now()->toDateString(),
            'check_out_date' => now()->toDateString(),
            'status' => 'Available'
        ]);

        return redirect()->route('bookings.index')
                     ->with('success', 'Guest checked out successfully. Booking marked as completed and room is now available.');
    }
}
