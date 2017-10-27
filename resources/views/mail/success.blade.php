@extends('layouts.base')
@section('content')
    <div class="container">
        <!-- Main component for call to action -->
        <h1>Mail Succesfully Sent</h1>
        <a class="btn btn-primary" href="{{route('twitterTimeline')}}">Back</a>
    </div>
@endsection