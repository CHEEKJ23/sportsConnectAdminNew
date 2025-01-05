@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Welcome to Your Dashboard</h1>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                
                <h2 class="mb-3">Hello, {{ Auth::user()->name }}!</h2>
                <p class="lead">We're glad to have you back. Here's what's happening today:</p>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title">Your Bookings</h3>
                    </div>
                    <div class="card-body">
                        <p>Check your upcoming bookings and manage them easily.</p>
                        <a href="{{ route('manageCourtBooking') }}" class="btn btn-primary">View Bookings</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title">Equipment</h3>
                    </div>
                    <div class="card-body">
                        <p>Manage and view your equipment, rentals and returns. </p>
                        <a 
                        href="{{ route('equipment.index') }}"
                            class="btn btn-success">Equipment</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h3 class="card-title">Feedback</h3>
                    </div>
                    <div class="card-body">
                        <p>Read feedback from our customers and share your own.</p>
                        <a href="{{ route('feedback.index') }}" class="btn btn-warning">Feedback</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card {
            margin-bottom: 20px;
        }
        .card-title {
            font-weight: bold;
        }
    </style>
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop