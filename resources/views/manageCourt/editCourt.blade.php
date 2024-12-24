@extends('adminlte::page')

@section('title', 'SportsConnect')

@section('content_header')
<div class="container">
    <h2>Edit Court</h2>
    <form action="{{ route('sportcenters.courts.update', [$sportCenter->id, $court->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="number">Court Number</label>
            <input type="text" name="number" class="form-control" id="number" value="{{ old('number', $court->number) }}" required>
        </div>

        <div class="form-group">
            <label for="type">Type</label>
            <input type="text" name="type" class="form-control" id="type" value="{{ old('type', $court->type) }}" required>
        </div>

        <div class="form-group">
            <label for="availability">Availability</label>
            <select name="availability" id="availability" class="form-control">
                <option value="1" {{ old('availability', $court->availability) == 1 ? 'selected' : '' }}>Available</option>
                <option value="0" {{ old('availability', $court->availability) == 0 ? 'selected' : '' }}>Not Available</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('sportcenters.courts.index', $sportCenter->id) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
@stop

@section('js')
    <script> console.log("Manage Redemptions Page Loaded"); </script>
@stop
