<?php

namespace App\Http\Controllers;

use App\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $rooms = Room::latest()->paginate(10);
        return view('rooms.index', compact('rooms'))
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
        return view('rooms.show', compact('room'));
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
}
