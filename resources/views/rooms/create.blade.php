@extends('layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Add New Room</div>
                <div class="card-body">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('rooms.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Room Number</label>
                            <input type="text" class="form-control" disabled value="{{ $nextRoomNumber }}">
                            <small class="text-muted">Room number will be assigned automatically</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Room Type</label>
                            <select name="room_type" class="form-select">
                                <option value="Single">Single</option>
                                <option value="Double">Double</option>
                                <option value="Deluxe">Deluxe</option>
                                <option value="Suite">Suite</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Price per Night</label>
                            <input type="number" name="price" class="form-control" step="0.01" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="Available">Available</option>
                                <option value="Under Maintenance">Under Maintenance</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success">Save Room</button>
                        <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection