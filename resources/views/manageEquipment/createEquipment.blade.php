@extends('adminlte::page')

@section('title', 'Add Equipment')

@section('content_header')
    <h1>Add Equipment</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('equipment.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="price_per_hour">Price per Hour</label>
            <input type="number" name="price_per_hour" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="quantity_available">Quantity Available</label>
            <input type="number" name="quantity_available" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="condition">Condition</label>
            <input type="text" name="condition" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="deposit_amount">Deposit Amount</label>
            <input type="number" name="deposit_amount" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="image_path">Image</label>
            <input type="file" name="image_path" class="form-control">
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
        <button type="submit" class="btn btn-primary">Add Equipment</button>
    </form>
</div>
@stop