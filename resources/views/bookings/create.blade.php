@extends('layouts.admin')

@section('content')

{{-- HERO --}}
<section class="admin-hero">
    <div class="hero-text">
        <h1>CREATE BOOKING</h1>
        <p>Fill in the details to create a new reservation.</p>
    </div>
</section>

{{-- CREATE BOOKING FORM --}}
<section class="admin-section">
    <small>New Reservation</small>
    <h2>BOOKING DETAILS</h2>

    @if ($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            <strong>Errors:</strong>
            <ul style="margin-bottom: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: start;">
        {{-- Form Section --}}
        <form action="{{ route('bookings.store') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 25px;">
                @if($selectedRoomId)
                    {{-- If room is pre-selected, display it as read-only --}}
                    <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">Selected Room</label>
                    <div style="padding: 12px; background: #f0f0f0; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; color: #555;">
                        @php
                            $selectedRoom = $rooms->firstWhere('room_id', $selectedRoomId);
                            $roomInfo = $selectedRoom ? "Room {$selectedRoom->room_number} ({$selectedRoom->room_type}) - ₱" . number_format($selectedRoom->price, 0) : 'Room not found';
                        @endphp
                        <strong>{{ $roomInfo }}</strong>
                        <input type="hidden" id="preSelectedRoomType" value="{{ $selectedRoom->room_type ?? '' }}">
                        <input type="hidden" id="preSelectedPrice" value="{{ $selectedRoom->price ?? 0 }}">
                    </div>
                    <input type="hidden" name="room_id" value="{{ $selectedRoomId }}">
                @else
                    {{-- If no room is pre-selected, show the dropdown --}}
                    <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">Select Room</label>
                    <select name="room_id" id="roomSelect" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;" required onchange="updateRoomImage(); calculateTotal()">
                        <option value="">-- Select an Available Room --</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->room_id }}" data-room-type="{{ $room->room_type }}" data-price="{{ $room->price }}" @if($selectedRoomId == $room->room_id) selected @endif>
                                Room {{ $room->room_number }} ({{ $room->room_type }}) - ₱{{ number_format($room->price, 0) }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">Guest Full Name</label>
                <input type="text" name="guest_name" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;" placeholder="John Doe" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                <div>
                    <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">Check-in Date</label>
                    <input type="date" name="check_in_date" id="checkInDate" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;" required onchange="calculateTotal()">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">Check-out Date</label>
                    <input type="date" name="check_out_date" id="checkOutDate" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;" required onchange="calculateTotal()">
                </div>
            </div>

            <div style="margin-bottom: 25px; padding: 15px; background: #f0f8ff; border-radius: 6px; border: 1px solid #bae6fd;">
                <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">Total Amount</label>
                <div style="font-size: 28px; font-weight: 700; color: #3ba0ff;">
                    ₱<span id="totalAmount">0</span>
                </div>
                <small style="color: #666;">Calculated based on room price and number of nights</small>
                <input type="hidden" name="total_amount" id="totalAmountInput" value="0">
            </div>

            <input type="hidden" name="account_id" value="{{ auth()->user()->account_id }}">

            <div style="display: flex; gap: 15px; margin-top: 30px;">
                <button type="submit" class="btn-primary">Confirm Booking</button>
                <a href="{{ route('bookings.status') }}" style="background: #6c757d; color: #fff; padding: 10px 18px; border-radius: 6px; border: none; font-size: 12px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; transition: background 0.3s ease;" onmouseover="this.style.background='#5a6268'" onmouseout="this.style.background='#6c757d'">Back to Bookings</a>
            </div>
        </form>

        {{-- Room Image Section --}}
        <div>
            <img id="roomImage" src="{{ asset('images/single.jpg') }}" alt="Room" style="width: 100%; height: 400px; border-radius: 12px; object-fit: cover;">
            <p id="roomInfo" style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 6px; text-align: center; font-size: 14px; color: #666;">
                Select a room to view details
            </p>
        </div>
    </div>
</section>

<script>
function updateRoomImage() {
    const preSelectedRoomType = document.getElementById('preSelectedRoomType');
    const roomSelect = document.getElementById('roomSelect');
    let roomType = null;
    
    // Check if room is pre-selected
    if (preSelectedRoomType && preSelectedRoomType.value) {
        roomType = preSelectedRoomType.value;
    } 
    // Otherwise get from dropdown
    else if (roomSelect) {
        const selectedOption = roomSelect.options[roomSelect.selectedIndex];
        roomType = selectedOption.getAttribute('data-room-type');
    }
    
    const roomImage = document.getElementById('roomImage');
    const roomInfo = document.getElementById('roomInfo');
    
    const roomImages = {
        'Single': '{{ asset("images/single.jpg") }}',
        'Double': '{{ asset("images/double.jpg") }}',
        'Suite': '{{ asset("images/suite.jpg") }}'
    };
    
    const roomDescriptions = {
        'Single': 'Single Room - Perfect for solo travelers seeking comfort and privacy.',
        'Double': 'Double Room - Spacious room ideal for couples or those seeking extra space.',
        'Suite': 'Suite - Premium luxury accommodations with separate living and sleeping areas.'
    };
    
    if (roomType && roomImages[roomType]) {
        roomImage.src = roomImages[roomType];
        roomInfo.textContent = roomDescriptions[roomType];
    }
    
    calculateTotal();
}

function calculateTotal() {
    const preSelectedPrice = document.getElementById('preSelectedPrice');
    const roomSelect = document.getElementById('roomSelect');
    const checkInDate = document.getElementById('checkInDate').value;
    const checkOutDate = document.getElementById('checkOutDate').value;
    
    let price = 0;
    
    // Check if room is pre-selected
    if (preSelectedPrice && preSelectedPrice.value) {
        price = parseFloat(preSelectedPrice.value) || 0;
    } 
    // Otherwise get from dropdown
    else if (roomSelect) {
        const selectedOption = roomSelect.options[roomSelect.selectedIndex];
        price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
    }
    
    if (checkInDate && checkOutDate && price > 0) {
        const checkIn = new Date(checkInDate);
        const checkOut = new Date(checkOutDate);
        const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
        
        if (nights > 0) {
            const total = price * nights;
            document.getElementById('totalAmount').textContent = total.toLocaleString('en-PH');
            document.getElementById('totalAmountInput').value = total;
            return;
        }
    }
    
    document.getElementById('totalAmount').textContent = '0';
    document.getElementById('totalAmountInput').value = '0';
}

// Initialize on page load if a room is pre-selected
window.addEventListener('load', function() {
    updateRoomImage();
});
</script>

@endsection