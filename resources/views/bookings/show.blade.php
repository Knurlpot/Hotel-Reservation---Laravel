@extends('layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Reservation Details</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Guest Name</h6>
                            <p class="lead">{{ $booking->guest_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Reservation Status</h6>
                            <p>
                                <span class="badge bg-{{ $booking->status == 'Booked' ? 'success' : ($booking->status == 'Pending' ? 'warning' : 'danger') }}">
                                    {{ $booking->status }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Room Number</h6>
                            <p class="lead">{{ $booking->room->room_number ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Room Type</h6>
                            <p class="lead">{{ $booking->room->room_type ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Price per Night</h6>
                            <p class="lead">${{ number_format($booking->room->price ?? 0, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Staff Member</h6>
                            <p class="lead">{{ $booking->account->first_name ?? 'N/A' }} {{ $booking->account->last_name ?? '' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Check-in Date</h6>
                            <p class="lead">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Check-out Date</h6>
                            <p class="lead">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="mt-4">
                        <a href="{{ route('bookings.edit', $booking->booking_id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Reservation
                        </a>
                        <a href="{{ route('bookings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        <form action="{{ route('bookings.destroy', $booking->booking_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i> Delete Reservation
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
