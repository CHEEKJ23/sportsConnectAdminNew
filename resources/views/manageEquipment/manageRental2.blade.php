@extends('adminlte::page')

@section('title', 'Add Equipment')

@section('content_header')
    <h1>Manage Equipment Rentals</h1>
@stop

@section('content')
<div class="container">
    <h1>Equipment Rentals</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
@endif
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User</th>
                <th>Equipment</th>
                <th>Sport Center</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Quantity</th>
                <th>Deposit Required</th>
                <th>Rental Status</th>
                <th>Enter Deposit Paid</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rentals as $rental)
                <tr>
                    <td>{{ $rental->user->name ?? 'N/A' }}</td>
                    <td>{{ $rental->equipment->name }}</td>
                    <td>{{ $rental->sportCenter->name ?? 'N/A' }}</td>
                    <td>{{ $rental->date }}</td>
                    <td>{{ $rental->startTime }}</td>
                    <td>{{ $rental->endTime }}</td>
                    <td>{{ $rental->quantity_rented }}</td>
                    <td>${{ $rental->equipment->deposit_amount }}</td>
                    <td>{{ $rental->rentalStatus }}</td>
                    <td>
                        <form action="{{ route('updateDepositReturned', $rental->rentalID) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="number" name="deposit_paid" class="form-control" step="0.01" placeholder="Enter deposit" required>
                                <button type="submit" class="btn btn-primary" {{ $rental->rentalStatus === 'Successful' ? 'disabled' : '' }}>Update</button>
                            </div>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
@stop

@section('js')
    <script> console.log("Manage Redemptions Page Loaded"); </script>
@stop
