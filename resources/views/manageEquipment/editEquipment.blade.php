@extends('adminlte::page')

@section('title', 'Edit Equipment')

@section('content_header')
    <h1>Edit Equipment</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('equipment.update', $equipment->equipmentID) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="{{ $equipment->name }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control">{{ $equipment->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="price_per_hour">Price per Hour</label>
            <input type="number" name="price_per_hour" class="form-control" value="{{ $equipment->price_per_hour }}" required>
        </div>
        <div class="form-group">
            <label for="quantity_available">Quantity Available</label>
            <input type="number" name="quantity_available" class="form-control" value="{{ $equipment->quantity_available }}" required>
        </div>
        <div class="form-group">
            <label for="condition">Condition</label>
            <input type="text" name="condition" class="form-control" value="{{ $equipment->condition }}" required>
        </div>
        <div class="form-group">
            <label for="deposit_amount">Deposit Amount</label>
            <input type="number" name="deposit_amount" class="form-control" value="{{ $equipment->deposit_amount }}" required>
        </div>
        <div class="form-group">
            <label for="image_path">Image</label>
            <input type="file" name="image_path" class="form-control">
            @if($equipment->image_path)
                <img src="{{ asset('images/' . $equipment->image_path) }}" alt="{{ $equipment->name }}" class="img-fluid mt-2" style="max-width: 200px;">
            @endif
        </div>
        <div class="form-group">
            <label for="sport_center_id">Sport Center</label>
            <select name="sport_center_id" class="form-control" required>
                <option value="">Select a Sport Center</option>
                @foreach($sportCenters as $sportCenter)
                    <option value="{{ $sportCenter->id }}">{{ $sportCenter->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Equipment</button>
    </form>
</div>
@stop