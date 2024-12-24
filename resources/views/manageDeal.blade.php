@extends('adminlte::page')

@section('title', 'Manage Deals')

@section('content_header')
    <h1>Manage Deals</h1>
@stop

@section('content')
<div class="container">
    @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if($deals->isEmpty())
        <h3 style="text-align: center;">No deals available.</h3>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Deal ID</th>
                        <th>User</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Location</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deals as $deal)
                        <tr>
                            <td>{{ $deal->dealID }}</td>
                            <td>{{ $deal->user ? $deal->user->name : 'N/A' }}</td>
                            <td>{{ $deal->title }}</td>
                            <td>{{ $deal->description }}</td>
                            <td>RM {{ number_format($deal->price, 2) }}</td>
                            <td>{{ $deal->location }}</td>
                            <td>
                                @if($deal->image_path)
                                    @if(filter_var($deal->image_path, FILTER_VALIDATE_URL))
                                        <img src="{{ $deal->image_path }}" alt="Deal Image" style="max-width: 100px;">
                                    @else
                                        <img src="{{ asset('images/' . $deal->image_path) }}" alt="Deal Image" style="max-width: 100px;">
                                    @endif
                                @else
                                    No Image
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $deal->status === 'Approved' ? 'success' : ($deal->status === 'Rejected' ? 'danger' : 'warning') }}">
                                    {{ $deal->status }}
                                </span>
                            </td>
                            <td>
                                @if($deal->status === 'Pending')
                                    <div class="btn-group" role="group">
                                        <form action="{{ route('approveDeal', $deal->dealID) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this deal?')">
                                                Approve
                                            </button>
                                        </form>

                                        <form action="{{ route('rejectDeal', $deal->dealID) }}" method="POST" style="display: inline; margin-left: 5px;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reject this deal?')">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@stop

@section('css')
<style>
    .table-responsive {
        margin-top: 20px;
    }
    .badge {
        padding: 8px 12px;
        font-size: 0.9em;
    }
    .btn-group {
        display: flex;
        gap: 5px;
    }
    .alert {
        margin-top: 20px;
    }
    img {
        border-radius: 5px;
        object-fit: cover;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Add any JavaScript functionality here
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 3000); // Hide alert after 3 seconds
    });
</script>
@stop