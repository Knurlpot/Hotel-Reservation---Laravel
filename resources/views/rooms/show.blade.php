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