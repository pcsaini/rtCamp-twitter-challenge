<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}">
    <link rel="apple-touch-icon" href="{{asset('img/apple-touch-icon-192x192.png')}}">
    <title>
        @if(Session::has('access_token'))
            @php
                $token = session::get('access_token');
                $screen_name = $token['screen_name'];
                echo 'Twitter Timeline (@'.$screen_name.')';
            @endphp
        @else
            Twitter Timeline
        @endif
    </title>

    <link rel="stylesheet" type="text/css" href="{{asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/jquery.bxslider.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
</head>

<body>
<div class="navbar">
    <div class="container">
        <div class="navbar-header">
            <button button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" rel="home" href="#">
                Twitter Timeline
            </a>
        </div>

        <div id="navbar" class="collapse navbar-collapse navbar-responsive-collapse">
            <ul class="nav navbar-nav navbar-right">
                @if(Session::has('access_token'))
                    @php
                        $token = session::get('access_token');
                        $screen_name = $token['screen_name'];
                        echo '<li><a href="https://twitter.com/'.$screen_name.'" target="_blank">@'.$screen_name.' </a></li>';
                    @endphp
                    <li><a href="{{route('twitter.logout')}}">Logout</a></li>
                @else
                    <li><a href="{{route('twitter.login')}}">Login</a></li>
                @endif
            </ul>
        </div>
    </div>
</div>

@yield('content')

<footer>

    <div class="footer-bottom">
        <div class="container">
            <div class="text-center"> Copyright © 2017 <a href="https://www.pcsaini" target="_blank">Prem Chand Saini(pcsaini)</a>.  All right reserved.</div>
        </div>
    </div> <!--/.footer-bottom-->
</footer>

<script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/jquery.bxslider.min.js')}}"></script>
<script src="{{asset('js/ajax.js')}}"></script>
</body>

</html>
