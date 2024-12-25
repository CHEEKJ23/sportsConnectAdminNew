@extends('adminlte::page')

@section('title', 'SportsConnect')

@section('content_header')
    <h1>Edit Sport Center</h1>
@stop

@section('content')
<div class="container">
    <h2>Edit Sport Center</h2>
    <form action="{{ route('sportcenters.update', $sportCenter->id) }}" method="POST" enctype="multipart/form-data">

        @csrf
        @method('POST')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $sportCenter->name) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" id="description" rows="4" required>{{ old('description', $sportCenter->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" name="location" class="form-control" id="location" value="{{ old('location', $sportCenter->location) }}" required>
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" name="price" class="form-control" id="price" value="{{ old('price', $sportCenter->price) }}" required>
        </div>

        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" name="image" class="form-control-file" id="image">
            @if($sportCenter->image)
                <img src="{{ asset('storage/' . $sportCenter->image) }}" alt="Sport Center Image" class="img-thumbnail mt-2" width="200">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('sportcenters.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
@stop

@section('js')
    <script> console.log("Manage Redemptions Page Loaded"); </script>
@stop
