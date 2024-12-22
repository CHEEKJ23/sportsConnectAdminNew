@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    {{-- <h1>Add Gift</h1> --}}
@stop

@section('content')
<div class="container">
   <h1>Gifts</h1>
   <a href="{{ route('admin.gifts.create') }}" class="btn btn-primary mb-3">Add Gift</a>
   @if($gifts->isEmpty())
       <p>No gifts available.</p> <!-- Message when no gifts are available -->
   @else
       <table class="table table-bordered">
           <thead>
               <tr>
                   <th>Name</th>
                   <th>Points Required</th>
                   <th>Description</th>
                   <th>Image</th> <!-- New column for image -->
                   <th>Actions</th>
               </tr>
           </thead>
           <tbody>
               @foreach ($gifts as $gift)
                   <tr>
                       <td>{{ $gift->name }}</td>
                       <td>{{ $gift->points_needed }}</td>
                       <td>{{ $gift->description }}</td>
                       <td>
                           @if($gift->image_path)
                               <img src="{{ asset('images/' . $gift->image_path) }}" alt="{{ $gift->name }}" style="width: 100px; height: auto;">
                           @else
                               No Image
                           @endif
                       </td>
                       <td>
                           <a href="{{ route('admin.gifts.edit', $gift->id) }}" class="btn btn-warning btn-sm">Edit</a>
                           <form action="{{ route('admin.gifts.destroy', $gift->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete();">
                               @csrf
                               @method('DELETE')
                               <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                           </form>
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
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");

        function confirmDelete() {
            return confirm('Are you sure you want to delete this gift? This action cannot be undone.');
        }
    </script>
@stop