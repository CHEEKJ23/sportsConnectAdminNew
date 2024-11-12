@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

  <div class="container" style="background-color: #fff; margin-top:5%; border-radius:15px; ">
    <div class="row">
        <div class="col-md-12">
        @if(Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Dear {{ Auth::user()->name }},</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <br>
            <h2>User List</h2>
            <br>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form class="form-inline my-2 my-lg-0" action="{{route('userSearch')}}" method="post">
                @csrf
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" id="keyword" name="keyword" style="height:40px;">
                <button class="btn btn-secondary" type="submit" style="height:40px;">Search</button>
            </form>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Create at</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($users))
                        @foreach($users as $user)
                        <tr>
                            <th scope="row">{{$user->id}}</th>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->created_at}}</td>
                            <td>
                            <a class="btn" href="{{route('deleteUser',['id'=>$user->id])}}" onClick="return confirm('Are you sure you want to delete this user?')"><i class="fa fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <th scope="row">--</th>
                            <td>--</td>
                            <td>--</td>
                            <td>--</td>
                            <td>--</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
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