@extends('adminlte::page')

@section('title', 'Overview')

@section('content_header')
    <h1>Overview of Court Availability</h1>
@stop

@section('content')
<div class="container">
    {{-- <form method="GET" action="{{ route('admin.courtAvailability') }}"> --}}
<form method="POST" action="{{ route('admin.overviewForm') }}" >
    @csrf   
        <div class="row">
            <div class="col-md-3">
                <input type="text" class="form-control" name="sport_center_name" placeholder="Sport Center Name" value="{{ old('sport_center_name', $validated['sport_center_name'] ?? '') }}">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="court_type" placeholder="Court Type" value="{{ old('court_type', $validated['court_type'] ?? '') }}">
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="date" required value="{{ old('date', $validated['date'] ?? '') }}">
            </div>
            <div class="col-md-2">
                <input type="time" class="form-control" name="startTime" required value="{{ old('startTime', $validated['startTime'] ?? '') }}">
            </div>
            <div class="col-md-2">
                <input type="time" class="form-control" name="endTime" required value="{{ old('endTime', $validated['endTime'] ?? '') }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Search</button>
    </form>

    <div class="mt-5">
        <h3>Results</h3>
        <div class="row">
            @foreach($courts as $court)
                <div class="col-md-4 mb-4">
                    <div class="card {{ $court->bookings->isEmpty() ? 'border-success' : 'border-danger' }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $court->sportCenter->name }}</h5>
                            <p class="card-text">
                                <strong>Court Type:</strong> {{ $court->type }}<br>
                                <strong>Court Number:</strong> {{ $court->number }}
                            </p>
                            <span class="badge {{ $court->bookings->isEmpty() ? 'bg-success' : 'bg-danger' }}">
                                {{ $court->bookings->isEmpty() ? 'Available' : 'Unavailable' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: scale(1.05);
    }
</style>
@stop

@section('js')
    <script> console.log("Overview Page Loaded"); </script>
@stop