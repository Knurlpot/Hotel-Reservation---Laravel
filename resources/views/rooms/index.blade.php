@extends('layouts.admin')

@section('content')

{{-- HERO --}}
<section class="admin-hero">
    <div class="hero-text">
        <h1>ADMIN</h1>
        <p>Manage daily operations, guests, and bookings efficiently.</p>
    </div>
</section>

{{-- ROOMS AVAILABLE --}}
<section class="admin-section">
    <small>Rooms Available</small>
    <h2>ROOMS</h2>

    <div class="room-cards">

        {{-- Single --}}
        <a href="{{ route('rooms.show', $singleRoom->room_id ?? '#') }}" class="room-card" style="text-decoration: none;">
            <img src="{{ asset('images/single.jpg') }}">
            <div class="overlay">
                <h3>Single</h3>
            </div>
        </a>

        {{-- Double --}}
        <a href="{{ route('rooms.show', $doubleRoom->room_id ?? '#') }}" class="room-card" style="text-decoration: none;">
            <img src="{{ asset('images/double.jpg') }}">
            <div class="overlay">
                <h3>Double</h3>
            </div>
        </a>

        {{-- Suite --}}
        <a href="{{ route('rooms.show', $suiteRoom->room_id ?? '#') }}" class="room-card" style="text-decoration: none;">
            <img src="{{ asset('images/suite.jpg') }}">
            <div class="overlay">
                <h3>Suite</h3>
            </div>
        </a>

    </div>
</section>

{{-- GUEST LIST --}}
<section class="admin-section">
    <small>Guests Staying</small>
    <h2>GUEST LIST</h2>

    <table class="guest-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>NAME</th>
                <th>ROOM</th>
                <th>CHECK IN</th>
                <th>CHECK OUT</th>
                <th>AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            @if($currentGuests->count() > 0)
                @foreach($currentGuests as $guest)
                    <tr>
                        <td>{{ $guest->booking_id }}</td>
                        <td><strong>{{ $guest->guest_name }}</strong></td>
                        <td>{{ $guest->room->room_type }}</td>
                        <td>{{ \Carbon\Carbon::parse($guest->check_in_date)->format('M d, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($guest->check_out_date)->format('M d, Y') }}</td>
                        <td>₱ {{ number_format($guest->total_amount, 0) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">No current guests</td>
                </tr>
            @endif
        </tbody>
    </table>
</section>

@endsection