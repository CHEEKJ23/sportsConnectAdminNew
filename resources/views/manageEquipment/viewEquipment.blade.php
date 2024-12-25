@extends('adminlte::page')

@section('title', 'Equipment List')

@section('content_header')
    <h1>Equipment List</h1>
@stop

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Equipment</h1>
        <a href="{{ route('equipment.create') }}" class="btn btn-primary">Add Equipment</a>
    </div>

    @if($equipment->isEmpty())
        <div class="alert alert-info">No equipment available.</div>
    @else
        <div class="row">
            @foreach ($equipment as $item)
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    @if($item->image_path)
                        <img src="{{ asset('images/' . $item->image_path) }}" alt="{{ $item->name }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->name }}</h5>
                        <p class="card-text">{{ Str::limit($item->description, 100) }}</p>
                        <p class="card-text"><strong>Price per Hour:</strong> ${{ $item->price_per_hour }}</p>
                        <p class="card-text"><strong>Quantity Available:</strong> {{ $item->quantity_available }}</p>
                        <p class="card-text"><strong>Condition:</strong> {{ $item->condition }}</p>
                        <p class="card-text"><strong>Deposit Amount:</strong> ${{ $item->deposit_amount }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('equipment.edit', ['equipment' => $item->equipmentID]) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('equipment.destroy', ['equipment' => $item->equipmentID]) }}" method="POST" class="d-inline" onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
@stop

@section('js')
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this equipment? This action cannot be undone.');
        }
    </script>
@stop