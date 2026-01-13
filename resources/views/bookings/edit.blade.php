@extends('layout')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-success text-white">Modify Booking #{{ $booking->booking_id }}</div>
        <div class="card-body">
            <form action="{{ route('bookings.update', $booking->booking_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Room</label>
                    <select name="room_id" class="form-select">
                        @foreach($rooms as $room)
                            <option value="{{ $room->room_id }}" {{ $booking->room_id == $room->room_id ? 'selected' : '' }}>
                                Room {{ $room->room_number }} ({{ $room->room_type }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Guest Name</label>
                    <input type="text" name="guest_name" value="{{ $booking->guest_name }}" class="form-control" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Check-in</label>
                        <input type="date" name="check_in_date" value="{{ $booking->check_in_date }}" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Check-out</label>
                        <input type="date" name="check_out_date" value="{{ $booking->check_out_date }}" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="Pending" {{ $booking->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Booked" {{ $booking->status == 'Booked' ? 'selected' : '' }}>Booked</option>
                        <option value="Available" {{ $booking->status == 'Available' ? 'selected' : '' }}>Cancelled/Checked Out</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Save Changes</button>
                <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection