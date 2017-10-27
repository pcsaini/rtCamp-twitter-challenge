@extends('layouts.base')
@section('content')
    <div class="container">
        <center><img src="{{asset('img/apple-touch-icon-192x192.png')}}">
        <h1 class="text-center">Twitter Timeline Challenge</h1>
        <center><a class="btn btn-info btn-lg" href="{{route('twitter.login')}}">Login With Twitter</a></center>
    </div>
@endsection