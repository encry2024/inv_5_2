@extends('app')


@section('header')
    @include('util.m-topbar')
    <div class="container">
        <div class="col-lg-12">
            <ol class="breadcrumb" style=" margin-left: 1.5rem; ">
                <li><label>Inventory</label>
                <li><a href="{{ route('home') }}" class="active">Home</a></li>
                <li><a href="{{ route('user.index') }}" class="active">Manage Users</a></li>
                <li><label for="">{{ $user->name }}</label></li>
            </ol>
        </div>
    </div>
@stop


@section('content')
    <div class="container">
        <div class="col-lg-3 col-md-3 col-sm-3">
            <div class="btn-group-vertical col-lg-12" style="width: 100%;" role="group">
                <a href="{{ URL::to('/')  }}" class="btn btn-default col-lg-12 text-left size-13" role="button">
                    <span class="glyphicon glyphicon-refresh"></span> Reset Password</a>
                <a class="btn btn-default col-lg-12 text-left size-13" role="button" href="{{ route('user.index') }}">
                    <span class="glyphicon glyphicon-chevron-left"></span> Back</a>
            </div>
        </div>
    </div>
@stop