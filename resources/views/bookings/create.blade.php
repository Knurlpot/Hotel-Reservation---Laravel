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

    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 40px;">
        {{-- Calendar Section --}}
        <div>
            <h3 style="font-size: 16px; font-weight: 600; color: #333; margin-bottom: 15px;">Select Dates</h3>
            <div style="border: 1px solid #ddd; border-radius: 6px; padding: 15px; background: #f9f9f9;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <button type="button" onclick="previousMonth()" style="background: none; border: none; cursor: pointer; font-size: 18px; padding: 5px 10px; color: #3ba0ff; font-weight: 700;">&lt;</button>
                    <span id="monthYear" style="font-weight: 600; font-size: 14px;">January 2026</span>
                    <button type="button" onclick="nextMonth()" style="background: none; border: none; cursor: pointer; font-size: 18px; padding: 5px 10px; color: #3ba0ff; font-weight: 700;">&gt;</button>
                </div>

                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <thead>
                        <tr>
                            <th style="padding: 10px 5px; text-align: center; font-weight: 600; color: #666; border-bottom: 1px solid #e5e7eb;">Sun</th>
                            <th style="padding: 10px 5px; text-align: center; font-weight: 600; color: #666; border-bottom: 1px solid #e5e7eb;">Mon</th>
                            <th style="padding: 10px 5px; text-align: center; font-weight: 600; color: #666; border-bottom: 1px solid #e5e7eb;">Tue</th>
                            <th style="padding: 10px 5px; text-align: center; font-weight: 600; color: #666; border-bottom: 1px solid #e5e7eb;">Wed</th>
                            <th style="padding: 10px 5px; text-align: center; font-weight: 600; color: #666; border-bottom: 1px solid #e5e7eb;">Thu</th>
                            <th style="padding: 10px 5px; text-align: center; font-weight: 600; color: #666; border-bottom: 1px solid #e5e7eb;">Fri</th>
                            <th style="padding: 10px 5px; text-align: center; font-weight: 600; color: #666; border-bottom: 1px solid #e5e7eb;">Sat</th>
                        </tr>
                    </thead>
                    <tbody id="calendarDays">
                        @php
                            $today = \Carbon\Carbon::now();
                            $year = $today->year;
                            $month = $today->month;
                            $firstDay = \Carbon\Carbon::createFromDate($year, $month, 1)->dayOfWeek;
                            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                            $dayCounter = 0;
                        @endphp
                        @for ($week = 0; $week < 6; $week++)
                            <tr>
                                @for ($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++)
                                    @if ($week === 0 && $dayOfWeek < $firstDay)
                                        <td style="padding: 8px; text-align: center; height: 40px;"></td>
                                    @elseif ($dayCounter < $daysInMonth)
                                        @php
                                            $dayCounter++;
                                            $dateStr = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($dayCounter, 2, '0', STR_PAD_LEFT);
                                            $isToday = $dayCounter == $today->day;
                                            $isBooked = in_array($dateStr, $bookedDates ?? []);
                                        @endphp
                                        @if ($isToday)
                                            <td style="padding: 8px; text-align: center; height: 40px; border-radius: 4px; background: #e8f1ff; color: #3ba0ff; font-weight: 700; border: 2px solid #3ba0ff; cursor: pointer;" class="calendar-cell" data-date="{{ $dateStr }}">{{ $dayCounter }}</td>
                                        @elseif ($isBooked)
                                            <td style="padding: 8px; text-align: center; height: 40px; border-radius: 4px; background: #f5f5f5; color: #999; text-decoration: line-through; cursor: not-allowed;" data-booked="true">{{ $dayCounter }}</td>
                                        @else
                                            <td style="padding: 8px; text-align: center; height: 40px; border-radius: 4px; background: #fff; color: #333; cursor: pointer;" class="calendar-cell" data-date="{{ $dateStr }}">{{ $dayCounter }}</td>
                                        @endif
                                    @else
                                        <td style="padding: 8px; text-align: center; height: 40px;"></td>
                                    @endif
                                @endfor
                            </tr>
                            @if ($dayCounter >= $daysInMonth)
                                @break
                            @endif
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Form Section --}}
        <form action="{{ route('bookings.store') }}" method="POST" style="display: flex; flex-direction: column;">
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

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px;">
                <div>
                    <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">Check-in Date</label>
                    <div id="checkInDateDisplay" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: #f9f9f9; color: #999; min-height: 20px;">
                        Select from calendar
                    </div>
                    <input type="hidden" name="check_in_date" id="checkInDate">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: #333; margin-bottom: 8px;">Check-out Date</label>
                    <div id="checkOutDateDisplay" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: #f9f9f9; color: #999; min-height: 20px;">
                        Select from calendar
                    </div>
                    <input type="hidden" name="check_out_date" id="checkOutDate">
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

            <div style="display: flex; gap: 15px; margin-top: auto;">
                <button type="submit" class="btn-primary">Confirm Booking</button>
                <a href="{{ route('bookings.status') }}" style="background: #6c757d; color: #fff; padding: 10px 18px; border-radius: 6px; border: none; font-size: 12px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; transition: background 0.3s ease;" onmouseover="this.style.background='#5a6268'" onmouseout="this.style.background='#6c757d'">Back to Bookings</a>
            </div>
        </form>

        {{-- Room Image Section --}}
        <div>
            <h3 style="font-size: 16px; font-weight: 600; color: #333; margin-bottom: 15px;">Room Preview</h3>
            <img id="roomImage" src="{{ asset('images/single.jpg') }}" alt="Room" style="width: 100%; height: 300px; border-radius: 12px; object-fit: cover; margin-bottom: 15px;">
            <p id="roomInfo" style="padding: 15px; background: #f8f9fa; border-radius: 6px; text-align: center; font-size: 14px; color: #666;">
                Select a room to view details
            </p>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click handlers to calendar cells
    const cells = document.querySelectorAll('.calendar-cell');
    cells.forEach(cell => {
        cell.addEventListener('click', function() {
            const dateStr = this.getAttribute('data-date');
            if (dateStr) {
                selectDate(dateStr);
            }
        });
        
        cell.addEventListener('mouseover', function() {
            this.style.background = '#f0f8ff';
            this.style.fontWeight = '600';
        });
        
        cell.addEventListener('mouseout', function() {
            const dateStr = this.getAttribute('data-date');
            if (dateStr) {
                this.style.background = '#fff';
                this.style.fontWeight = '400';
            }
        });
    });
    
    // Add event listeners to booked dates to show tooltip/message
    const bookedDates = document.querySelectorAll('[data-booked="true"]');
    bookedDates.forEach(cell => {
        cell.addEventListener('mouseover', function() {
            this.title = 'This date is already booked';
        });
    });
    
    updateRoomImage();
});

function selectDate(dateStr) {
    const checkInDate = document.getElementById('checkInDate').value;
    const checkOutDate = document.getElementById('checkOutDate').value;
    const checkInDisplay = document.getElementById('checkInDateDisplay');
    const checkOutDisplay = document.getElementById('checkOutDateDisplay');
    
    const dateObj = new Date(dateStr + 'T00:00:00');
    const dateFormatted = dateObj.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    
    if (!checkInDate) {
        document.getElementById('checkInDate').value = dateStr;
        checkInDisplay.textContent = dateFormatted;
        checkInDisplay.style.color = '#333';
    } else if (checkInDate && !checkOutDate) {
        const checkIn = new Date(checkInDate + 'T00:00:00');
        const selectedDate = new Date(dateStr + 'T00:00:00');
        
        if (selectedDate <= checkIn) {
            document.getElementById('checkInDate').value = dateStr;
            document.getElementById('checkOutDate').value = '';
            checkInDisplay.textContent = dateFormatted;
            checkInDisplay.style.color = '#333';
            checkOutDisplay.textContent = 'Select from calendar';
            checkOutDisplay.style.color = '#999';
        } else {
            document.getElementById('checkOutDate').value = dateStr;
            checkOutDisplay.textContent = dateFormatted;
            checkOutDisplay.style.color = '#333';
            calculateTotal();
            highlightSelectedDates();
        }
    } else if (checkInDate && checkOutDate) {
        document.getElementById('checkInDate').value = dateStr;
        document.getElementById('checkOutDate').value = '';
        checkInDisplay.textContent = dateFormatted;
        checkInDisplay.style.color = '#333';
        checkOutDisplay.textContent = 'Select from calendar';
        checkOutDisplay.style.color = '#999';
        document.getElementById('totalAmount').textContent = '0';
        highlightSelectedDates();
    }
}

function highlightSelectedDates() {
    const checkInDate = document.getElementById('checkInDate').value;
    const checkOutDate = document.getElementById('checkOutDate').value;
    
    // Clear previous highlights
    const allCells = document.querySelectorAll('.calendar-cell, [data-booked="true"]');
    allCells.forEach(cell => {
        cell.style.background = cell.getAttribute('data-booked') === 'true' ? '#f5f5f5' : '#fff';
    });
    
    // Highlight selected date range
    if (checkInDate && checkOutDate) {
        const checkIn = new Date(checkInDate + 'T00:00:00');
        const checkOut = new Date(checkOutDate + 'T00:00:00');
        
        const cells = document.querySelectorAll('.calendar-cell');
        cells.forEach(cell => {
            const cellDate = new Date(cell.getAttribute('data-date') + 'T00:00:00');
            if (cellDate >= checkIn && cellDate <= checkOut) {
                cell.style.background = '#cce5ff';
                cell.style.fontWeight = '600';
                cell.style.color = '#0066cc';
            }
        });
    } else if (checkInDate && !checkOutDate) {
        // Highlight only check-in date
        const cells = document.querySelectorAll('.calendar-cell');
        cells.forEach(cell => {
            const cellDate = cell.getAttribute('data-date');
            if (cellDate === checkInDate) {
                cell.style.background = '#e8f1ff';
                cell.style.fontWeight = '700';
                cell.style.color = '#3ba0ff';
                cell.style.border = '2px solid #3ba0ff';
            }
        });
    }
}

function updateRoomImage() {
    const preSelectedRoomType = document.getElementById('preSelectedRoomType');
    const roomSelect = document.getElementById('roomSelect');
    let roomType = null;
    
    if (preSelectedRoomType && preSelectedRoomType.value) {
        roomType = preSelectedRoomType.value;
    } else if (roomSelect) {
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
    
    if (preSelectedPrice && preSelectedPrice.value) {
        price = parseFloat(preSelectedPrice.value) || 0;
    } else if (roomSelect) {
        const selectedOption = roomSelect.options[roomSelect.selectedIndex];
        price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
    }
    
    if (checkInDate && checkOutDate && price > 0) {
        const checkIn = new Date(checkInDate + 'T00:00:00');
        const checkOut = new Date(checkOutDate + 'T00:00:00');
        const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
        const days = nights + 1; // Include the check-in day
        
        if (days > 0) {
            const total = price * days;
            document.getElementById('totalAmount').textContent = total.toLocaleString('en-PH');
            document.getElementById('totalAmountInput').value = total;
            return;
        }
    }
    
    document.getElementById('totalAmount').textContent = '0';
    document.getElementById('totalAmountInput').value = '0';
}
</script>

@endsection