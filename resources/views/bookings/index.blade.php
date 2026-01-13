@extends('layouts.receptionist')

@section('content')

{{-- HERO --}}
<section class="reception-hero">
    <div class="hero-text">
        <h1>RECEPTION</h1>
        <p>Manage daily operations, guests, and bookings efficiently.</p>
    </div>
</section>

{{-- SEARCH --}}
<div class="reception-search">
    <input type="text" placeholder="Search Guests & Rooms">
    <button>Search</button>
</div>

{{-- RESERVATIONS --}}
<section class="reception-section" id="reservations">
    <small>Guests arriving</small>
    <h2>RESERVATIONS</h2>

    <table class="reception-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>NAME</th>
                <th>ROOM</th>
                <th>CHECK IN</th>
                <th>CHECK OUT</th>
                <th>AMOUNT</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->booking_id }}</td>
                    <td><strong>{{ $booking->guest->first_name ?? 'N/A' }} {{ $booking->guest->last_name ?? '' }}</strong></td>
                    <td>{{ $booking->room->room_type ?? 'N/A' }}</td>
                    <td>{{ optional($booking->check_in_date)->format('M d, Y') }}</td>
                    <td>{{ optional($booking->check_out_date)->format('M d, Y') }}</td>
                    <td>₱ {{ number_format($booking->payment->amount ?? 0, 0) }}</td>
                    <td><button class="btn-primary">CHECK IN</button></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">No reservations found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</section>

<hr>

{{-- CHECKOUTS --}}
<section class="reception-section">
    <small>Guests leaving</small>
    <h2>CHECK-OUTS</h2>

    <table class="reception-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>NAME</th>
                <th>ROOM</th>
                <th>CHECK IN</th>
                <th>CHECK OUT</th>
                <th>AMOUNT</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->booking_id }}</td>
                    <td><strong>{{ $booking->guest->first_name ?? 'N/A' }} {{ $booking->guest->last_name ?? '' }}</strong></td>
                    <td>{{ $booking->room->room_type ?? 'N/A' }}</td>
                    <td>{{ optional($booking->check_in_date)->format('M d, Y') }}</td>
                    <td>{{ optional($booking->check_out_date)->format('M d, Y') }}</td>
                    <td>₱ {{ number_format($booking->payment->amount ?? 0, 0) }}</td>
                    <td><button class="btn-primary">CHECK OUT</button></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">No check-outs scheduled</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</section>

@endsection
