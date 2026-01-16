@extends('layouts.app')

@section('content')
<div class="room-page">

    {{-- Breadcrumb --}}
    <p class="breadcrumb">Home > <strong>Rooms & Suites</strong></p>

    <div class="room-container">
        {{-- LEFT INFO --}}
        <div class="room-info">
            <h2>{{ $room->room_type }} Bedroom</h2>

            <h4>About</h4>
            <p>
                {!! nl2br($description) !!}
            </p>

            <p class="price">₱ {{ number_format($room->price, 0) }} <span>/ night</span></p>
            <p class="price-note"><em>*excluding taxes</em></p>

            {{-- Book Now Button for Receptionists --}}
            @if(auth()->check() && auth()->user()->role == 'Receptionist')
                <a href="{{ route('bookings.create', ['room_id' => $room->room_id]) }}" class="book-now-btn" style="display: inline-block; background: #3ba0ff; color: #fff; padding: 12px 30px; border-radius: 6px; text-decoration: none; font-weight: 600; margin-top: 20px; transition: background 0.3s ease;">
                    Book Now
                </a>
            @endif
        </div>

        {{-- RIGHT IMAGES --}}
        <div class="room-images">
            <img src="{{ asset($images['main']) }}" class="main-img">

            <div class="thumbs">
                <img src="{{ asset($images['thumb1']) }}">
                <img src="{{ asset($images['thumb2']) }}">
            </div>
        </div>
    </div>
</div>

{{-- Checkout Modal --}}
@include('rooms.checkout-modal')

@endsection