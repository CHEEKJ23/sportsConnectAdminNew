@extends('adminlte::page')

@section('title', 'Manage Rental')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<head>
<!-- Bootstrap CSS -->

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</head>
<div class="container">
    <h1>Manage Equipment Rental Returns</h1>

    @if($rentals->where('rentalStatus', 'Pending Return')->isNotEmpty())
        <!-- Rental Information Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>Rental ID</th>
                    <th>Equipment Name</th>
                    <th>User</th>
                    <th>Quantity Rented</th>
                    <th>Deposit Paid</th>
                    <th>Return Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rentals as $rental)
                    @if($rental->rentalStatus === 'Pending Return')
                        <tr>
                            <td>{{ $rental->rentalID }}</td>
                            <td>{{ $rental->equipment->name }}</td>
                            <td>{{ $rental->user->name }}</td>
                            <td>{{ $rental->quantity_rented }}</td>
                            <td>{{ $rental->deposit_paid }}</td>
                            <td>{{ $rental->rentalStatus }}</td>
                            <td>
                                <!-- Button to trigger modal -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#returnModal-{{ $rental->rentalID }}">
                                    Process Return
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="returnModal-{{ $rental->rentalID }}" tabindex="-1" aria-labelledby="returnModalLabel-{{ $rental->rentalID }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="returnModalLabel-{{ $rental->rentalID }}">Process Return for Rental ID: {{ $rental->rentalID }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('rentals.processReturn', $rental->rentalID) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                  
                                                    <div class="mb-3">
                                                        <label for="quantity_returned" class="form-label">Quantity Returned</label>
                                                        <input type="number" name="quantity_returned" class="form-control" min="1" placeholder="Enter quantity returned" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="deposit_returned" class="form-label">Deposit Returned</label>
                                                        <input type="number" name="deposit_returned" class="form-control" step="0.01" placeholder="Enter deposit to return" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Complete Return</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <p>No pending return requests at the moment.</p>
    @endif

    <!-- Display success or error messages -->
    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
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