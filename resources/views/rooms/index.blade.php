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
        <div class="room-card">
            <img src="{{ asset('images/single.jpg') }}">
            <div class="overlay">
                <h3>Single</h3>
            </div>
        </div>

        {{-- Double --}}
        <div class="room-card">
            <img src="{{ asset('images/double.jpg') }}">
            <div class="overlay">
                <h3>Double</h3>
            </div>
        </div>

        {{-- Suite --}}
        <div class="room-card">
            <img src="{{ asset('images/suite.jpg') }}">
            <div class="overlay">
                <h3>Suite</h3>
            </div>
        </div>

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
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>101</td>
                <td><strong>Stephen Malkmus</strong></td>
                <td>Single</td>
                <td>DEC 24, 2025</td>
                <td>DEC 25, 2025</td>
                <td>₱ 1,309</td>
                <td>
                    <button class="btn-primary">CHECK OUT</button>
                </td>
            </tr>

            <tr>
                <td>102</td>
                <td><strong>Kali Uchis</strong></td>
                <td>Suite</td>
                <td>DEC 24, 2025</td>
                <td>DEC 28, 2025</td>
                <td>₱ 2,309</td>
                <td>
                    <button class="btn-primary">CHECK OUT</button>
                </td>
            </tr>
        </tbody>
    </table>
</section>

@endsection