@extends('layouts.app')

@section('content')
<div class="room-page">

    {{-- Breadcrumb --}}
    <p class="breadcrumb">Home > <strong>Booking Details</strong></p>

    <div class="room-container">
        {{-- LEFT INFO --}}
        <div class="room-info">
            <h2>Booking #{{ $booking->booking_id }}</h2>

            <h4>Guest Details</h4>
            <p><strong>Guest Name:</strong> {{ $booking->guest_name }}</p>
            <p><strong>Room Type:</strong> {{ $booking->room->room_type ?? 'N/A' }}</p>
            <p><strong>Room Number:</strong> {{ $booking->room->room_number ?? 'N/A' }}</p>

            <h4>Booking Dates</h4>
            <p><strong>Check-in Date:</strong> {{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</p>
            <p><strong>Check-out Date:</strong> {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</p>

            <h4>Booking Amount</h4>
            <p class="price">₱ {{ number_format($booking->total_amount, 0) }} <span>/ total</span></p>

            <h4>Booking Status</h4>
            <p>
                <span style="padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 600; 
                    @if($booking->status == 'Booked')
                        background: #fff3cd; color: #856404;
                    @elseif($booking->status == 'Checked-In')
                        background: #d1ecf1; color: #0c5460;
                    @elseif($booking->status == 'Completed')
                        background: #d4edda; color: #155724;
                    @elseif($booking->status == 'Cancelled')
                        background: #f8d7da; color: #721c24;
                    @endif
                ">
                    {{ ucfirst($booking->status) }}
                </span>
            </p>

            <h4>Staff Member</h4>
            <p>{{ $booking->account->first_name ?? 'N/A' }} {{ $booking->account->last_name ?? '' }}</p>

            <hr>

            <div class="mt-4" style="display: flex; gap: 10px; flex-wrap: wrap;">
                @if($booking->status === 'Booked')
                    {{-- Booked Status: Show Check In, Back to Bookings, Cancel Booking --}}
                    <form method="POST" action="{{ route('bookings.checkin', $booking->booking_id) }}" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to check in this guest?')" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                            Check In
                        </button>
                    </form>
                    <a href="{{ route('bookings.status') }}" class="btn btn-secondary" style="display: inline-block; padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; font-weight: 600;">
                        Back to Bookings
                    </a>
                    <form action="{{ route('bookings.destroy', $booking->booking_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')" style="padding: 10px 20px; background: #dc3545; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                            Cancel Booking
                        </button>
                    </form>
                @elseif($booking->status === 'Checked-In')
                    {{-- Checked-In Status: Show Edit Booking, Back to Bookings --}}
                    <a href="{{ route('bookings.edit', $booking->booking_id) }}" class="btn btn-warning" style="display: inline-block; padding: 10px 20px; background: #ffc107; color: #333; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; font-weight: 600;">
                        Edit Booking
                    </a>
                    <a href="{{ route('bookings.status') }}" class="btn btn-secondary" style="display: inline-block; padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; font-weight: 600;">
                        Back to Bookings
                    </a>
                @elseif($booking->status === 'Cancelled')
                    {{-- Cancelled Status: Show only Back to Bookings --}}
                    <a href="{{ route('bookings.status') }}" class="btn btn-secondary" style="display: inline-block; padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; font-weight: 600;">
                        Back to Bookings
                    </a>
                @else
                    {{-- Default: Show Edit Booking, Back to Bookings, Cancel Booking --}}
                    <a href="{{ route('bookings.edit', $booking->booking_id) }}" class="btn btn-warning" style="display: inline-block; padding: 10px 20px; background: #ffc107; color: #333; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; font-weight: 600;">
                        Edit Booking
                    </a>
                    <a href="{{ route('bookings.status') }}" class="btn btn-secondary" style="display: inline-block; padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; font-weight: 600;">
                        Back to Bookings
                    </a>
                    @if($booking->status !== 'Completed')
                        <form action="{{ route('bookings.destroy', $booking->booking_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')" style="padding: 10px 20px; background: #dc3545; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                                Cancel Booking
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        {{-- RIGHT IMAGES --}}
        <div class="room-images">
            @php
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
                $images = $roomImages[$booking->room->room_type] ?? $roomImages['Single'];
            @endphp
            <img src="{{ asset($images['main']) }}" class="main-img">

            <div class="thumbs">
                <img src="{{ asset($images['thumb1']) }}">
                <img src="{{ asset($images['thumb2']) }}">
            </div>
        </div>
    </div>
</div>

@endsection
