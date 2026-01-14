<link rel="stylesheet" href="{{ asset('css/checkout-modal.css') }}">

<div id="checkoutModal" class="checkout-modal" style="display: none;">
    <div class="checkout-modal-content">
        <div class="checkout-header">
            <h2><img src="{{ asset('images/cart.jpg') }}" alt="Cart" class="cart-icon"> CHECKOUT COUNTER</h2>
            <button class="close-btn" onclick="closeCheckout()">✕</button>
        </div>

        <p class="room-title"><strong id="modal-room-title">Single Bedroom</strong></p>

        <div class="checkout-container">
            {{-- Calendar Section --}}
            <div class="calendar-section">
                <div class="calendar-nav">
                    <button class="prev-month">&lt;</button>
                    <span class="month-year" id="monthYear">December 2025</span>
                    <button class="next-month">&gt;</button>
                </div>

                <table class="calendar-table" id="calendarTable">
                    <thead>
                        <tr>
                            <th>Sun</th>
                            <th>Mon</th>
                            <th>Tue</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                            <th>Sat</th>
                        </tr>
                    </thead>
                    <tbody id="calendarDays">
                    </tbody>
                </table>
            </div>

            {{-- Summary Section --}}
            <div class="summary-section">
                <form method="POST" action="{{ route('bookings.store') }}">
                    @csrf

                    <input type="hidden" name="room_id" id="modal-room-id" value="">

                    <div class="form-field">
                        <label>CHECK IN:</label>
                        <input type="text" id="checkInDisplay" readonly>
                        <input type="date" name="check_in_date" id="checkInDate" required style="display: none;">
                    </div>

                    <div class="form-field">
                        <label>CHECK OUT:</label>
                        <input type="text" id="checkOutDisplay" readonly>
                        <input type="date" name="check_out_date" id="checkOutDate" required style="display: none;">
                    </div>

                    <div class="form-field">
                        <label for="guest_name">Guest Name:</label>
                        <input type="text" id="guest_name" name="guest_name" required placeholder="Enter guest name">
                    </div>

                    <hr class="divider">

                    <div class="rate-section">
                        <p class="rate-label">Rate per night:</p>
                        <p class="rate-amount">₱ <span id="ratePerNight">0</span></p>
                    </div>

                    <div class="total-section">
                        <p class="total-label">GRAND TOTAL:</p>
                        <h3 class="total-amount">₱ <span id="totalAmount">0</span></h3>
                    </div>

                    <button type="submit" class="btn-book-now">Book Now</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let bookedDatesGlobal = [];

    function openCheckout(roomId, roomType, roomPrice, bookedDates = []) {
        // Set booked dates globally
        bookedDatesGlobal = bookedDates;
        
        // Set room information
        document.getElementById('modal-room-id').value = roomId;
        document.getElementById('modal-room-title').textContent = roomType + ' Bedroom';
        
        // Display rate per night
        document.getElementById('ratePerNight').textContent = roomPrice.toLocaleString();
        
        // Show modal
        document.getElementById('checkoutModal').style.display = 'flex';
        
        // Initialize calendar and calculations
        generateCalendar();
        updateTotalAmount(roomPrice);
    }

    function closeCheckout() {
        document.getElementById('checkoutModal').style.display = 'none';
    }

    function generateCalendar() {
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth();
        
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'];
        
        document.getElementById('monthYear').textContent = monthNames[month] + ' ' + year;
        
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        
        let html = '';
        let dayCounter = 0;
        
        // Create weeks
        for (let week = 0; week < 6; week++) {
            html += '<tr>';
            for (let dayOfWeek = 0; dayOfWeek < 7; dayOfWeek++) {
                if (week === 0 && dayOfWeek < firstDay) {
                    // Empty cells before month starts
                    html += '<td></td>';
                } else if (dayCounter < daysInMonth) {
                    dayCounter++;
                    const dateStr = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(dayCounter).padStart(2, '0');
                    const isToday = dayCounter === today.getDate() ? 'today' : '';
                    const isBooked = bookedDatesGlobal.includes(dateStr) ? 'disabled' : '';
                    const onclickAttr = isBooked ? '' : `onclick="selectDate('${dateStr}')"`;
                    html += `<td class="${isToday} ${isBooked}" ${onclickAttr}>${dayCounter}</td>`;
                } else {
                    // Empty cells after month ends
                    html += '<td></td>';
                }
            }
            html += '</tr>';
            
            if (dayCounter >= daysInMonth) {
                break;
            }
        }
        
        document.getElementById('calendarDays').innerHTML = html;
    }

    function selectDate(dateStr) {
        const checkInDate = document.getElementById('checkInDate').value;
        const checkOutDate = document.getElementById('checkOutDate').value;
        const roomPriceSpan = document.getElementById('ratePerNight').textContent;
        const roomPrice = parseInt(roomPriceSpan.replace(/,/g, ''));
        
        if (!checkInDate) {
            // First selection - set check-in date
            document.getElementById('checkInDate').value = dateStr;
            document.getElementById('checkInDisplay').value = formatDate(dateStr);
            highlightSelectedDates();
        } else if (checkInDate && !checkOutDate) {
            // Second selection - set check-out date
            const checkIn = new Date(checkInDate);
            const selectedDate = new Date(dateStr);
            
            if (selectedDate <= checkIn) {
                // If selected date is before check-in, reset and make it the new check-in
                document.getElementById('checkInDate').value = dateStr;
                document.getElementById('checkInDisplay').value = formatDate(dateStr);
                document.getElementById('checkOutDate').value = '';
                document.getElementById('checkOutDisplay').value = '';
            } else {
                // Set check-out date
                document.getElementById('checkOutDate').value = dateStr;
                document.getElementById('checkOutDisplay').value = formatDate(dateStr);
                // Update total amount after checkout date is set
                updateTotalAmount(roomPrice);
            }
            highlightSelectedDates();
        } else if (checkInDate && checkOutDate) {
            // Both dates selected - reset and start over
            document.getElementById('checkInDate').value = dateStr;
            document.getElementById('checkInDisplay').value = formatDate(dateStr);
            document.getElementById('checkOutDate').value = '';
            document.getElementById('checkOutDisplay').value = '';
            document.getElementById('totalAmount').textContent = '0';
            highlightSelectedDates();
        }
    }

    function highlightSelectedDates() {
        const checkInDate = document.getElementById('checkInDate').value;
        const checkOutDate = document.getElementById('checkOutDate').value;
        
        const cells = document.getElementById('calendarDays').querySelectorAll('td');
        cells.forEach(cell => {
            cell.classList.remove('selected', 'in-range');
            const cellText = cell.textContent.trim();
            
            if (checkInDate && cellText) {
                const dateStr = checkInDate.split('-')[0] + '-' + checkInDate.split('-')[1] + '-' + String(cellText).padStart(2, '0');
                
                if (dateStr === checkInDate || dateStr === checkOutDate) {
                    cell.classList.add('selected');
                } else if (checkOutDate && dateStr > checkInDate && dateStr < checkOutDate) {
                    cell.classList.add('in-range');
                }
            }
        });
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'];
        return monthNames[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear();
    }

    function updateTotalAmount(pricePerNight) {
        const checkInDate = document.getElementById('checkInDate').value;
        const checkOutDate = document.getElementById('checkOutDate').value;
        
        if (checkInDate && checkOutDate) {
            const checkIn = new Date(checkInDate);
            const checkOut = new Date(checkOutDate);
            const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
            const total = nights * pricePerNight;
            document.getElementById('totalAmount').textContent = total.toLocaleString();
        }
    }
</script>
