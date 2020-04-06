@extends('layouts.default')

@section('content')
        <label>User info</label>
        <div class="row">
            <div class="col">Name:</div>
            <div class="col">{{ $user['name'] }}</div>
        </div>
        <div class="row">
            <div class="col">Email:</div>
            <div class="col">{{ $user['email'] }}</div>
        </div>
        <div class="row">
            <div class="col">Birthday:</div>
            <div class="col">{{ $user['birthday'] }}</div>
        </div>
        <div class="row">
            <div class="col">Phone number:</div>
            <div class="col">{{ $user['phone_number'] }}</div>
        </div>
@stop
