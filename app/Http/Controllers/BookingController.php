<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Room;
use App\Account;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Displays a list of all reservations.
        $bookings = Booking::with(['room', 'account'])->latest()->paginate(10);
        return view('bookings.index', compact('bookings'))
        ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Load available rooms and accounts for booking creation.
        $rooms = \App\Room::where('status', 'Available')->get();
        $selectedRoomId = request()->input('room_id');
        return view('bookings.create', compact('rooms', 'selectedRoomId'));
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

    return redirect()->route('rooms.index')
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
        return view('bookings.edit', compact('booking', 'rooms', 'accounts'));
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
        $booking->delete();

        return redirect()->route('bookings.index')
                     ->with('success', 'Booking deleted successfully');
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
        
        return view('bookings.status', compact('bookings'));
    }

    /**
     * Mark a booking as completed (check out).
     */
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

        return redirect()->route('bookings.status')
                     ->with('success', 'Guest checked out successfully. Booking marked as completed and room is now available.');
    }
}
