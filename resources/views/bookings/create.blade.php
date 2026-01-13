@extends('layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">Create New Reservation</div>
                <div class="card-body">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('bookings.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Select Room</label>
                            <select name="room_id" class="form-select" required>
                                <option value="">-- Select an Available Room --</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->room_id }}">
                                        Room {{ $room->room_number }} ({{ $room->room_type }}) - ${{ $room->price }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Guest Full Name</label>
                            <input type="text" name="guest_name" class="form-control" placeholder="John Doe" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Check-in Date</label>
                                <input type="date" name="check_in_date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Check-out Date</label>
                                <input type="date" name="check_out_date" class="form-control" required>
                            </div>
                        </div>

                        <input type="hidden" name="account_id" value="{{ auth()->user()->account_id }}">

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Confirm Booking</button>
                            <a href="{{ route('bookings.index') }}" class="btn btn-light">Back to List</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection