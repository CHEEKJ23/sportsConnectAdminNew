@extends('adminlte::page')

@section('title', 'SportsConnect')

@section('content_header')
    <h1>Manage Courts</h1>
@stop

@section('content')
<div class="container">
    <h1>Courts for {{ $sportCenter->name }}</h1>
    <a href="{{ route('sportcenters.courts.create', $sportCenter->id) }}" class="btn btn-primary mb-3">Add Court</a>

    @if($courts->isEmpty())
        <div class="alert alert-info">
            No courts available for this sport center.
        </div>
    @else
        @foreach ($courts as $court)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Court {{ $court->number }}</h5>
                <p class="card-text">Type: {{ $court->type }}</p>
                {{-- <p class="card-text">Availability: {{ $court->availability ? 'Available' : 'Not Available' }}</p> --}}
             
                <a href="{{ route('sportcenters.courts.edit', ['sportcenter' => $sportCenter->id, 'court' => $court->id]) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('sportcenters.courts.destroy',  ['sportcenter' => $sportCenter->id, 'court' => $court->id]) }}" method="POST" class="d-inline" onsubmit="return confirmDelete()">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
        @endforeach
    @endif
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
@stop

@section('js')
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this court? This action cannot be undone.');
        }
    </script>
@stop