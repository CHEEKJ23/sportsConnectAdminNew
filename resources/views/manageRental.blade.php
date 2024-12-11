@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="container">
    <h1>Rental Return Requests</h1>

    @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    @if($rentals->isEmpty())
   <h3 style="text-align: center;">No rental return requests received.</h3>

@else
    <table class="table">
        <thead>
            <tr>
                <th>Rental ID</th>
                <th>Equipment Name</th>
                <th>User</th>
                <th>Quantity Rented</th>
                <th>Return Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rentals as $rental)
                <tr>
                    <td>{{ $rental->rentalID }}</td>
                    <td>{{ $rental->equipment->name }}</td> 
                    <td>{{ $rental->user->name }}</td>
                    <td>{{ $rental->quantity_rented }}</td>
                    <td>{{ $rental->rentalStatus }}</td>
                    <td>
                        @if($rental->rentalStatus === 'Pending Return')
                            <form action="{{ route('completeReturn') }}" method="POST">
                                @csrf
                                <input type="hidden" name="rentalID" value="{{ $rental->rentalID }}">
                                <input type="text" name="equipmentQuality" placeholder="Enter equipment quality" required>
                                <button type="submit" class="btn btn-success">Complete Return</button>
                            </form>
                        @else
                            <span>Completed</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop