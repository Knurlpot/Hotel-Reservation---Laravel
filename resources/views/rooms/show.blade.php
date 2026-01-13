@extends('layout')

@section('content')
<div class="container">
    <h2>Room Details</h2>
    <hr>
    <p><strong>Room Number:</strong> {{ $room->room_number }}</p>
    <p><strong>Type:</strong> {{ $room->room_type }}</p>
    <p><strong>Price:</strong> ${{ number_format($room->price, 2) }}</p>
    <p><strong>Status:</strong> 
        <span class="badge {{ $room->status == 'Available' ? 'bg-success' : 'bg-danger' }}">
            {{ $room->status }}
        </span>
    </p>
    <a href="{{ route('rooms.index') }}" class="btn btn-primary">Back</a>
</div>
@endsection