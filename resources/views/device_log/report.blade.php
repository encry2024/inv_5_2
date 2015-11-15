@extends('...app')

@section('header')
    @include('util.m-topbar')
    <div class="container">
        <div class="col-lg-12">
            <ol class="breadcrumb" style=" margin-left: 1.5rem; ">
                <li><label>Inventory</label>
                <li><a href="{{ route('home') }}" class="active">Home</a></li>
                <li><label>Report</label>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="container">
    <div class="col-lg-3">
        <div class="btn-group-vertical col-lg-12" role="group">
            <a role="button" class="btn btn-default col-lg-12 text-left" href="{{ route('home')  }}"><span class="glyphicon glyphicon-chevron-left"></span> Return to Home</a>
        </div>
    </div>
    <div class="col-lg-9 col-md-offset-center-2" >
        <form class="form-horizontal">
            <div class="form-group">
                <label class="left" for="" style="margin-top: 0.5rem; margin-left: 1.5rem;">Filter By: </label>
                <select name="categoryLabel" id="categoryLabel" class="btn btn-default left" style="margin-left: 1.5rem; margin-top: 0.35rem;">
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <div class="col-lg-4">
                    <input type="search" class="form-control" id="filter" name="filter" placeholder="Choose date on the Calendar" readonly>
                </div>
                <button type="submit" class="btn btn-default">Get Report</button>
                <a role="button" class="btn btn-default" href="{{ route('report') }}">Clear filter</a>
            </div>
        </form>
        <br>
        @if ( !Request::has('filter'))
            <div id="calendar"></div>
        @else
            <table class="table table-condensed">
                <thead>
                <tr>
                    <td>Owner</td>
                    <td>Device</td>
                    <td>Released By</td>
                    <td>Action</td>
                    <td>Released Date</td>
                </tr>
                </thead>
                <tbody>
                @foreach ($deviceLogs as $deviceLog)
                    <tr>
                        <td>
                            <a href="{!! route('owner.show', $deviceLog->owner->slug) !!}">{{ $deviceLog->owner->fullName() }}</a>
                        </td>
                        <td>
                            <a href="{{ route('device.edit', $deviceLog->device->slug) }}">{{ $deviceLog->device->name}}</a>
                        </td>
                        <td>
                            <a href="{{ route('user.show', $deviceLog->user->id) }}">{{ $deviceLog->user->name}}</a>
                        </td>
                        <td>{{ $deviceLog->action }}</td>
                        <td>{{ date('M d, F h:i A', strtotime($deviceLog->created_at)) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@stop

@section('script')
<script>
    $(document).ready(function() {
        var elem = document.getElementById("filter");
        $("#calendar").fullCalendar({
            contentHeight: 350,
            height: 350,
            aspectRatio: 2,
            selectable: true,
            header: {
                left: 'today prev,next',
                center: 'title',
                right: 'month',
            },
            select: function( start, end) {
                elem.value = start.format() + ' - ' + end.format();
            }
        })
    });
</script>
@stop