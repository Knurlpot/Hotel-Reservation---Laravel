# Hotel Reservation System - Step-by-Step Setup Guide

A comprehensive hotel reservation management system built with Laravel that allows receptionists and admins to manage bookings, rooms, and guest operations efficiently.

## Features

- **Room Management**: Manage different room types (Single, Double, Suite) with pricing
- **Booking System**: Create, edit, and manage bookings with calendar date selection
- **Guest Check-in/Check-out**: Track guest arrival and departure
- **Search Functionality**: Search bookings by guest name or room number
- **Role-Based Access**: Admin and Receptionist roles with different permissions
- **Booking Status Tracking**: Track bookings through different statuses (Booked, Checked-In, Completed, Cancelled)

## Prerequisites

Before you begin, ensure you have the following installed:
- PHP 8.0 or higher
- Composer
- MySQL or compatible database
- Node.js and npm (for frontend assets)

## Installation & Setup Steps

### Step 1: Clone the Repository
```bash
git clone <repository-url>
cd HotelReservation
```

### Step 2: Install PHP Dependencies
```bash
composer install
```

### Step 3: Install Node Dependencies
```bash
npm install
```

### Step 4: Configure Environment File
```bash
cp .env.example .env
php artisan key:generate
```

Edit the `.env` file and configure your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hotel_reservation
DB_USERNAME=root
DB_PASSWORD=
```

### Step 5: Create Database
Create a new MySQL database:
```bash
mysql -u root -p
CREATE DATABASE hotel_reservation;
EXIT;
```

### Step 6: Run Database Migrations & Seeders
```bash
php artisan migrate:fresh --seed
```


This will:
- Create all necessary database tables
- Populate sample data (rooms, users, bookings, etc.)

### Step 7: Build Frontend Assets
```bash
npm run build
```

For development with hot reload:
```bash
npm run dev
```

### Step 8: Start the Development Server
```bash
php artisan serve
```

The application will be available at `http://127.0.0.1:8000`

## Default Login Credentials

After seeding, use these credentials to login:

**Admin Account:**
- Email: admin@example.com
- Password: password

**Receptionist Account:**
- Email: receptionist@example.com
- Password: password

## User Workflows

### Receptionist Dashboard (`/bookings`)

**Available Actions:**

1. **View Room Cards**
   - See available room types (Single, Double, Suite)
   - View pricing per night
   - Click to view room details

2. **Search Bookings**
   - Enter guest name or room number in search bar
   - Results filter across all booking sections
   - Click "Clear" to reset search

3. **Manage Reservations** (Guests arriving)
   - View upcoming guest arrivals
   - No action buttons in this section
   - Click EDIT to modify booking details

4. **Manage Booked Transactions** (All booked guests)
   - View all booked reservations
   - Click CHECK IN button to mark guest as arrived
   - Click EDIT button to modify booking details
   - Sorted by earliest to latest check-in date

5. **Manage Check-outs** (Guests to leave)
   - View guests ready to check out
   - Click CHECK OUT button to complete transaction
   - Guest automatically moves to Completed section

6. **View Completed Bookings** (Past transactions)
   - Historical record of completed check-outs
   - View-only section for reference

### Admin Dashboard (`/rooms`)

**Available Actions:**

1. **View Room Cards**
   - See all room types with pricing
   - Click to manage individual rooms

2. **Manage Rooms**
   - View room inventory
   - Create new rooms
   - Edit room details
   - Mark rooms as under maintenance

3. **View Booking Status** (`/bookings-status`)
   - Comprehensive booking overview by status
   - Available, Booked, Checked-In, Completed, Cancelled
   - Filter by room type
   - View, Edit, and manage bookings with full controls

## Step-by-Step: Creating a New Booking

1. Go to `/bookings` (Receptionist Dashboard)
2. Click on a room card to start booking
3. **Select Dates**:
   - Click on calendar to select check-in date
   - Click on calendar to select check-out date
   - (Booked dates shown in gray and disabled)
4. **Fill in Details**:
   - Select room from dropdown
   - Enter guest name
   - System auto-calculates total amount
5. **Confirm**:
   - Click "Confirm Booking" button
   - You're redirected to `/bookings` page

## Step-by-Step: Editing a Booking

1. Navigate to `/bookings`
2. Find the booking in RESERVATIONS or BOOKED section
3. Click EDIT button (yellow button)
4. **Modify Details**:
   - Change guest name
   - Change room assignment
   - **Update Dates**: Click calendar to select new dates (can select current booking dates)
5. **Save**:
   - Click "Save Changes" button
   - Click "Back to Bookings" to return to dashboard

## Step-by-Step: Guest Check-in Process

1. Navigate to `/bookings`
2. Find guest in RESERVATIONS section
3. Click CHECK IN button
4. Confirm action in popup
5. Guest automatically moves to CHECK-OUTS section
6. Booking status changes to "Checked-In"

## Step-by-Step: Guest Check-out Process

1. Navigate to `/bookings`
2. Find guest in CHECK-OUTS section
3. Click CHECK OUT button
4. Confirm action in popup
5. Booking automatically moves to COMPLETED section
6. Room automatically marked as available
7. Booking status changes to "Completed"

## Key Pages & Routes

| Route | Description | Role |
|-------|-------------|------|
| `/bookings` | Receptionist Dashboard | Receptionist |
| `/rooms` | Admin Room Management | Admin |
| `/bookings-status` | Booking Status View | Admin |
| `/bookings/create` | Create New Booking | Both |
| `/bookings/{id}/edit` | Edit Booking Details | Both |
| `/dashboard` | Auto-redirects based on role | Both |
| `/profile` | User Profile Settings | Both |

## Booking Status Flow

```
BOOKED (Initial Status)
   ↓
CHECK IN (Receptionist clicks CHECK IN button)
   ↓
CHECKED-IN
   ↓
CHECK OUT (Receptionist clicks CHECK OUT button)
   ↓
COMPLETED (Final Status)

Alternative:
BOOKED
   ↓
CANCELLED (via Delete/Cancel action)
```

## Search Functionality

- **Search By**: Guest Name or Room Number
- **Scope**: Searches across all booking sections
- **Result**: Filters bookings in real-time
- **Clear**: Click "Clear" button to reset search

## Room Management

### Room Types
- **Single Room**: ₱1,309/night
- **Double Room**: ₱2,500/night
- **Suite**: ₱4,500/night

### Room Status
- **Available**: Room can be booked
- **Booked**: Room is reserved
- **Checked-In**: Guest is occupying room
- **Completed**: Guest has checked out
- **Under Maintenance**: Room unavailable

## Database Schema Summary

### Users Table
- User authentication and role management (Admin/Receptionist)

### Rooms Table
- Room inventory with type, number, price, and status

### Bookings Table
- Guest booking records with dates, amounts, and status

### Accounts Table
- Staff/receptionist account information

## Troubleshooting

### Issue: Migration Error
**Solution:**
```bash
php artisan migrate:reset
php artisan migrate:fresh --seed
```

### Issue: Assets Not Loading
**Solution:**
```bash
npm run build
php artisan optimize:clear
```

### Issue: Database Connection Error
**Solution:**
- Verify MySQL is running
- Check .env database credentials
- Ensure database exists: `CREATE DATABASE hotel_reservation;`

### Issue: 500 Error
**Solution:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Project Structure

```
app/
├── Http/Controllers/
│   ├── BookingController.php      (Booking logic)
│   ├── RoomController.php         (Room management)
│   └── ProfileController.php      (User profile)
├── Models/
│   ├── Booking.php                (Booking model)
│   ├── Room.php                   (Room model)
│   └── User.php                   (User model)

database/
├── migrations/                    (Database schema)
├── seeders/
│   └── HotelSeeder.php           (Sample data)

resources/
├── views/
│   ├── bookings/
│   │   ├── index.blade.php       (Receptionist dashboard)
│   │   ├── create.blade.php      (Create booking form)
│   │   ├── edit.blade.php        (Edit booking form)
│   │   └── status.blade.php      (Admin status view)
│   ├── rooms/
│   │   └── index.blade.php       (Room management)
│   └── layouts/                  (Layout templates)

routes/
├── web.php                        (Main routes)
├── auth.php                       (Auth routes)
└── console.php                    (Console commands)
```

## Common Commands

```bash
# Start development server
php artisan serve

# Run migrations only
php artisan migrate

# Reset and reseed database
php artisan migrate:fresh --seed

# Clear all caches
php artisan optimize:clear

# Build frontend assets
npm run build

# Watch for changes in development
npm run dev

# Create a new migration
php artisan make:migration migration_name

# Create a new model
php artisan make:model ModelName
```

## Support & Documentation

- Laravel Documentation: https://laravel.com/docs
- Project Issues: Create an issue in the repository
- Development Team: Contact team lead

## License

This project is licensed under the MIT License.