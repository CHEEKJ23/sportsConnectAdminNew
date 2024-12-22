@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Manage Feedback</h1>
@stop

@section('content')
<div class="container">
    <h1>User Feedback</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Feedback</th>
                    <th>Reply</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($feedbacks as $feedback)
                    <tr>
                        <td>{{ $feedback->id }}</td>
                        <td>{{ $feedback->user->name }}</td>
                        <td>{{ $feedback->feedback }}</td>
                        <td>{{ $feedback->reply ?? 'No reply yet' }}</td>
                        <td>
                            @if(!$feedback->reply)
                                <form action="{{ route('feedback.reply', $feedback->id) }}" method="POST">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" name="reply" class="form-control" placeholder="Type reply" required>
                                        <button type="submit" class="btn btn-primary">Reply</button>
                                    </div>
                                </form>
                            @else
                                <span class="text-success">Replied</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop
