@extends('adminlte::page')

@section('title', 'SportsConnect')

@section('content_header')
    <h1>Sport Center List</h1>
@stop

@section('content')
<div class="container">
    <h1>Sport Centers</h1>
    <a href="{{ route('sportcenters.create') }}" class="btn btn-primary mb-3">Add Sport Center</a>

    @foreach ($sportCenters as $sportCenter)
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ $sportCenter->name }}</h5>
            <p class="card-text">{{ $sportCenter->description }}</p>
            <p class="card-text">Location: {{ $sportCenter->location }}</p>
            <a href="{{ route('sportcenters.courts.index', $sportCenter->id) }}" class="btn btn-secondary">View Courts</a>
            <a href="{{ route('sportcenters.edit', $sportCenter->id) }}" class="btn btn-warning">Edit</a>
            
            <form action="{{ route('sportcenters.destroy', $sportCenter->id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete()">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
@stop

@section('js')
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this sport center? This action cannot be undone.');
        }
    </script>
@stop
