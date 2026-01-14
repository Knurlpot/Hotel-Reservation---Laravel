<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Update the dashboard route to redirect users based on their role
Route::get('/dashboard', function () {
    if (auth()->user()->role == 'Admin') {
        return redirect()->route('rooms.index');
    }
    return redirect()->route('bookings.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Routes for both Admin and Receptionist
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Booking Routes (Both Receptionist and Admin can access)
    Route::resource('bookings', BookingController::class);
    Route::get('/bookings-status', [BookingController::class, 'showByStatus'])->name('bookings.status');
    Route::patch('/bookings/{id}/checkin', [BookingController::class, 'checkin'])->name('bookings.checkin');
    Route::patch('/bookings/{id}/checkout', [BookingController::class, 'checkout'])->name('bookings.checkout');
    Route::patch('/rooms/{id}/maintenance', [RoomController::class, 'toggleMaintenance'])->name('rooms.maintenance');

    // Room Routes (Only Admin can manage rooms)
    Route::resource('rooms', RoomController::class)->middleware('role:Admin');
    
    // API endpoint for available rooms
    Route::get('/api/available-rooms', [RoomController::class, 'getAvailableRooms']);
});

require __DIR__.'/auth.php';