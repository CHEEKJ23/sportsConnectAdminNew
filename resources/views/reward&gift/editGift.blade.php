@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    {{-- <h1>Add Gift</h1> --}}
@stop

@section('content')
<div class="container">
    <h1>Edit Gift</h1>
    <form action="{{ route('admin.gifts.update', $gift->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Gift Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $gift->name }}" required>
        </div>
        <div class="mb-3">
            <label for="points_needed" class="form-label">Points Needed</label>
            <input type="number" class="form-control" id="points_needed" name="points_needed" value="{{ $gift->points_needed }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description">{{ $gift->description }}</textarea>
        </div>
        <div class="mb-3">
            <label for="image_path" class="form-label">Gift Image</label>
            <input type="file" class="form-control" id="image_path" name="image_path" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Update Gift</button>
    </form>
</div>

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop
