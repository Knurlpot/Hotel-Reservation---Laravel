@extends('layouts.admin')

@section('content')

{{-- HERO --}}
<section class="admin-hero">
    <div class="hero-text">
        <h1>BOOKINGS</h1>
        <p>View all bookings organized by their status.</p>
    </div>
</section>

{{-- BOOKINGS BY STATUS --}}
<section class="admin-section">
    <small>Booking Management</small>
    <h2>ALL BOOKINGS</h2>

    @if($bookings->isEmpty())
        <p style="text-align: center; padding: 40px; color: #999;">No bookings found.</p>
    @else
        @forelse($bookings as $status => $statusBookings)
            <div style="margin-bottom: 50px;">
                <h3 style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #3ba0ff;">
                    {{ ucfirst($status) }} ({{ count($statusBookings) }})
                </h3>

                @if($statusBookings->isEmpty())
                    <p style="color: #999; padding: 20px;">No bookings with status "{{ $status }}"</p>
                @else
                    {{-- Filter Buttons --}}
                    <div style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
                        <button class="filter-btn filter-all" onclick="filterByRoomType(this, '{{ $status }}', 'all')" style="background: #3ba0ff; color: white; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600;">
                            All
                        </button>
                        <button class="filter-btn" onclick="filterByRoomType(this, '{{ $status }}', 'Single')" style="background: #f0f0f0; color: #333; padding: 8px 16px; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; transition: all 0.3s;">
                            Single
                        </button>
                        <button class="filter-btn" onclick="filterByRoomType(this, '{{ $status }}', 'Double')" style="background: #f0f0f0; color: #333; padding: 8px 16px; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; transition: all 0.3s;">
                            Double
                        </button>
                        <button class="filter-btn" onclick="filterByRoomType(this, '{{ $status }}', 'Suite')" style="background: #f0f0f0; color: #333; padding: 8px 16px; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; transition: all 0.3s;">
                            Suite
                        </button>
                    </div>
                    @if($status === 'Available')
                        {{-- Available Rooms Table --}}
                        <table class="guest-table">
                            <thead>
                                <tr>
                                    <th>ROOM ID</th>
                                    <th>ROOM TYPE</th>
                                    <th>ROOM PRICE</th>
                                    <th>ROOM STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($statusBookings as $booking)
                                    @php
                                        $statusBadgeClass = 'available';
                                    @endphp
                                    <tr>
                                        <td>{{ $booking->room_id }}</td>
                                        <td>{{ $booking->room->room_type ?? 'N/A' }}</td>
                                        <td>₱ {{ number_format($booking->room->price ?? 0, 0) }}</td>
                                        <td>
                                            <span style="padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #d4edda; color: #155724;">
                                                Available
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('bookings.create', ['room_id' => $booking->room_id]) }}" class="btn-primary" style="display: inline-block; text-decoration: none;">MAKE A BOOKING</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        @if($status === 'Booked')
                            {{-- Booked Bookings Table with Action Column --}}
                            <table class="guest-table">
                                <thead>
                                    <tr>
                                        <th>ROOM ID</th>
                                        <th>GUEST NAME</th>
                                        <th>ROOM</th>
                                        <th>CHECK IN</th>
                                        <th>CHECK OUT</th>
                                        <th>AMOUNT</th>
                                        <th>STAFF</th>
                                        <th>STATUS</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($statusBookings as $booking)
                                        @php
                                            $statusBadgeClass = 'booked';
                                        @endphp
                                        <tr>
                                            <td>{{ $booking->room_id }}</td>
                                            <td><strong>{{ $booking->guest_name }}</strong></td>
                                            <td>{{ $booking->room->room_type ?? 'N/A' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</td>
                                            <td>₱ {{ number_format($booking->room->price ?? 0, 0) }}</td>
                                            <td>{{ $booking->account->first_name ?? 'N/A' }} {{ $booking->account->last_name ?? '' }}</td>
                                            <td>
                                                <span style="padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #fff3cd; color: #856404;">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ route('bookings.checkout', $booking->booking_id) }}" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn-primary" onclick="return confirm('Are you sure you want to check out this guest?')">CHECK OUT</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($status === 'Completed')
                            {{-- Completed Bookings Table without Action Column --}}
                            <table class="guest-table">
                                <thead>
                                    <tr>
                                        <th>ROOM ID</th>
                                        <th>GUEST NAME</th>
                                        <th>ROOM</th>
                                        <th>CHECK IN</th>
                                        <th>CHECK OUT</th>
                                        <th>AMOUNT</th>
                                        <th>STAFF</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($statusBookings as $booking)
                                        @php
                                            $statusBadgeClass = 'completed';
                                        @endphp
                                        <tr>
                                            <td>{{ $booking->room_id }}</td>
                                            <td><strong>{{ $booking->guest_name }}</strong></td>
                                            <td>{{ $booking->room->room_type ?? 'N/A' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</td>
                                            <td>₱ {{ number_format($booking->room->price ?? 0, 0) }}</td>
                                            <td>{{ $booking->account->first_name ?? 'N/A' }} {{ $booking->account->last_name ?? '' }}</td>
                                            <td>
                                                <span style="padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; background: #d1ecf1; color: #0c5460;">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    @endif
                @endif
            </div>
        @empty
            <p style="text-align: center; padding: 40px; color: #999;">No bookings available.</p>
        @endforelse
    @endif
</section>

<script>
function filterByRoomType(button, status, roomType) {
    // Update button styles
    const buttons = document.querySelectorAll(`button.filter-btn[onclick*="'${status}'"]`);
    buttons.forEach(btn => {
        btn.style.background = '#f0f0f0';
        btn.style.color = '#333';
        btn.style.border = '1px solid #ddd';
    });
    
    button.style.background = '#3ba0ff';
    button.style.color = 'white';
    button.style.border = 'none';
    
    // Filter table rows
    const tables = document.querySelectorAll('table.guest-table');
    tables.forEach(table => {
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const roomTypeCell = row.querySelector('td:nth-child(2)');
            if (!roomTypeCell) return;
            
            const cellRoomType = roomTypeCell.textContent.trim();
            
            if (roomType === 'all') {
                row.style.display = '';
            } else {
                row.style.display = cellRoomType === roomType ? '' : 'none';
            }
        });
    });
}
</script>

@endsection
