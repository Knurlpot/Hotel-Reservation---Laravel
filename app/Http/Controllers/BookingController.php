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
        return view('bookings.create', compact('rooms'));
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
    ]);

    // This logic ensures the booking is tied to the logged-in staff member
    Booking::create($request->all());

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
}
