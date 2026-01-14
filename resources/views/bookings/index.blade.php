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

{{-- ROOMS AVAILABLE --}}
<section class="reception-section">
    <small>Rooms Available</small>
    <h2>ROOMS</h2>

    <div class="room-cards">

        {{-- Single --}}
        <a href="{{ route('rooms.show', $singleRoom->room_id ?? '#') }}" class="room-card" style="text-decoration: none;">
            <img src="{{ asset('images/single.jpg') }}">
            <div class="overlay">
                <h3>Single</h3>
                <p style="font-size: 14px; margin-top: 5px;">₱{{ number_format($singleRoom->price ?? 0, 0) }} <span style="font-size: 12px;">/night</span></p>
                <p style="margin-top: 15px; font-size: 13px; font-weight: 600;">Book Now ></p>
            </div>
        </a>

        {{-- Double --}}
        <a href="{{ route('rooms.show', $doubleRoom->room_id ?? '#') }}" class="room-card" style="text-decoration: none;">
            <img src="{{ asset('images/double.jpg') }}">
            <div class="overlay">
                <h3>Double</h3>
                <p style="font-size: 14px; margin-top: 5px;">₱{{ number_format($doubleRoom->price ?? 0, 0) }} <span style="font-size: 12px;">/night</span></p>
                <p style="margin-top: 15px; font-size: 13px; font-weight: 600;">Book Now ></p>
            </div>
        </a>

        {{-- Suite --}}
        <a href="{{ route('rooms.show', $suiteRoom->room_id ?? '#') }}" class="room-card" style="text-decoration: none;">
            <img src="{{ asset('images/suite.jpg') }}">
            <div class="overlay">
                <h3>Suite</h3>
                <p style="font-size: 14px; margin-top: 5px;">₱{{ number_format($suiteRoom->price ?? 0, 0) }} <span style="font-size: 12px;">/night</span></p>
                <p style="margin-top: 15px; font-size: 13px; font-weight: 600;">Book Now ></p>
            </div>
        </a>

    </div>
</section>

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
            @forelse($reservations as $booking)
                <tr>
                    <td>{{ $booking->booking_id }}</td>
                    <td><strong>{{ $booking->guest_name }}</strong></td>
                    <td>{{ $booking->room->room_type ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</td>
                    <td>₱{{ number_format($booking->total_amount ?? 0, 0) }}</td>
                    <td>
                        <a href="{{ route('bookings.edit', $booking->booking_id) }}" class="btn-edit" style="display: inline-block; padding: 6px 10px; font-size: 10px; background: #ffc107; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; text-decoration: none; transition: background 0.3s ease;" onmouseover="this.style.background='#e0a800'" onmouseout="this.style.background='#ffc107'">EDIT</a>
                    </td>
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

{{-- BOOKED TRANSACTIONS --}}
<section class="reception-section">
    <small>All Booked Transactions</small>
    <h2>BOOKED</h2>

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
            @forelse($booked as $booking)
                <tr>
                    <td>{{ $booking->booking_id }}</td>
                    <td><strong>{{ $booking->guest_name }}</strong></td>
                    <td>{{ $booking->room->room_type ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</td>
                    <td>₱{{ number_format($booking->total_amount ?? 0, 0) }}</td>
                    <td>
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <a href="{{ route('bookings.edit', $booking->booking_id) }}" class="btn-edit" style="display: inline-block; padding: 6px 10px; font-size: 10px; background: #ffc107; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; text-decoration: none; transition: background 0.3s ease;" onmouseover="this.style.background='#e0a800'" onmouseout="this.style.background='#ffc107'">EDIT</a>
                            <form action="{{ route('bookings.checkin', $booking->booking_id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-primary">CHECK IN</button>
                            </form>
                        </div>
                    </td>
                </tr>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">No booked transactions found</td>
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
            @forelse($checkouts as $booking)
                <tr>
                    <td>{{ $booking->booking_id }}</td>
                    <td><strong>{{ $booking->guest_name }}</strong></td>
                    <td>{{ $booking->room->room_type ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</td>
                    <td>₱{{ number_format($booking->total_amount ?? 0, 0) }}</td>
                    <td>
                        <form action="{{ route('bookings.checkout', $booking->booking_id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-primary" onclick="return confirm('Are you sure you want to check out this guest?')">CHECK OUT</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">No check-outs scheduled</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</section>

<hr>

{{-- COMPLETED --}}
<section class="reception-section">
    <small>Completed Transactions</small>
    <h2>COMPLETED</h2>

    <table class="reception-table">
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
            @forelse($completed as $booking)
                <tr>
                    <td>{{ $booking->booking_id }}</td>
                    <td><strong>{{ $booking->guest_name }}</strong></td>
                    <td>{{ $booking->room->room_type ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</td>
                    <td>₱{{ number_format($booking->total_amount ?? 0, 0) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">No completed transactions found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</section>

@endsection
