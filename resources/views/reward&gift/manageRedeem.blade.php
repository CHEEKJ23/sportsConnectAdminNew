@extends('adminlte::page')

@section('title', 'Redemptions')

@section('content_header')
    <h1>Manage Redemptions</h1>
@stop

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    @if($redemptions->isEmpty())
        <div class="alert alert-info">
            No redemption requests available.
        </div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Gift</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($redemptions as $redemption)
                    <tr>
                        <td>{{ $redemption->id }}</td>
                        <td>{{ $redemption->user->name }}</td>
                        <td>{{ $redemption->gift->name }}</td>
                        <td>{{ $redemption->status }}</td>
                        <td>
                            <form action="{{ route('admin.redemptions.updateStatus', $redemption->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <select name="status" class="form-control" required>
                                    <option value="pending" {{ $redemption->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $redemption->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $redemption->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm mt-2">Update Status</button>
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
@stop

@section('js')
    <script> console.log("Manage Redemptions Page Loaded"); </script>
@stop