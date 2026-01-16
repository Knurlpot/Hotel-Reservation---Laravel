@extends('layout')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">Edit Room: {{ $room->room_number }}</div>
        <div class="card-body">
            <form action="{{ route('rooms.update', $room->room_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Room Number</label>
                    <input type="text" name="room_number" value="{{ $room->room_number }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Room Type</label>
                    <select name="room_type" class="form-select">
                        <option value="Single" {{ $room->room_type == 'Single' ? 'selected' : '' }}>Single</option>
                        <option value="Double" {{ $room->room_type == 'Double' ? 'selected' : '' }}>Double</option>
                        <option value="Deluxe" {{ $room->room_type == 'Deluxe' ? 'selected' : '' }}>Deluxe</option>
                        <option value="Suite" {{ $room->room_type == 'Suite' ? 'selected' : '' }}>Suite</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" name="price" value="{{ $room->price }}" class="form-control" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="Available" {{ $room->status == 'Available' ? 'selected' : '' }}>Available</option>
                        <option value="Under Maintenance" {{ $room->status == 'Under Maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Room</button>
                <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection