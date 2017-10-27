@extends('layouts.base')
@section('content')
    <div class="container">
        <center><img src="{{asset('img/apple-touch-icon-192x192.png')}}">
            <h1 class="text-center">Twitter Timeline</h1>
            <button data-toggle="modal" data-target="#Mail" class="btn btn-primary">Send Mail all Tweets as a PDF</button>
        </center>
        <br>
        <div class='row'>
            <div class='col-md-offset-2 col-md-8 twitter'>

                <div class="slider">
                    @if(!empty($tweets))
                        @foreach($tweets as $tweet)
                            <div>
                                <div class="tweet">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="profile_pic">
                                                <img src="{{$tweet['user']['profile_image_url']}}">
                                            </div>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="tweet-header">
                                                <span class="user_name">{{$tweet['user']['name']}}</span> -
                                                <span>{!! Twitter::linkify('@'.$tweet['user']['screen_name']) !!}</span>
                                                <span
                                                        class="time">{!! Twitter::ago($tweet['created_at']) !!}</span>
                                            </div>

                                            <div class="tweet-body">
                                                <p>{!! Twitter::linkify($tweet['text']) !!}</p>

                                                @if(!empty($tweet['extended_entities']['media']))
                                                    <div class="photoContainer">
                                                        @foreach($tweet['extended_entities']['media'] as $v)
                                                            <img src="{{ $v['media_url_https']}}">
                                                        @endforeach
                                                    </div>
                                                @endif

                                            </div>

                                            <div class="tweet-footer">
                                                <ul class="footer-icon">
                                                    <li>
                                                        <a href="https://twitter.com/intent/tweet?in_reply_to={{$tweet['id']}}"><i
                                                                    class="glyphicon glyphicon-comment"></i></a></li>
                                                    <li>
                                                        <a href="https://twitter.com/intent/retweet?tweet_id={{$tweet['id']}}"><i
                                                                    class="glyphicon glyphicon-retweet"> {{ $tweet['retweet_count'] }}</i></a>
                                                    </li>
                                                    <li>
                                                        <a href="https://twitter.com/intent/favorite?tweet_id={{$tweet['id']}}"><i
                                                                    class="glyphicon glyphicon-heart"> {{ $tweet['favorite_count'] }}</i></a>
                                                    </li>
                                                </ul>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>We are having a problem with our Twitter Feed right now.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class='row'>
            <div class='col-md-offset-2 col-md-8'>
                <h3>Followers</h3>
                <label>Search Follower</label>
                <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search Follower">

                <ul id="myUL">
                    @if(!empty($followers))
                        @foreach($followers['users'] as $follower)
                            <li><a data-id="{{$follower['screen_name']}}" class="follower"><img
                                            src="{{$follower['profile_image_url_https']}}"> {{$follower['name']}}
                                    - {{'@'.$follower['screen_name']}}</a></li>
                        @endforeach
                    @else
                        <p>We are having a problem with our Twitter Feed right now.</p>
                    @endif
                </ul>
            </div>
        </div>

    </div>
    <div id="Mail" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Send Mail all Tweets as a PDF</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form role="form" method="POST" action="{{route('sendMail')}}">
                                {{ csrf_field() }}
                                <div class="form-group col-lg-12">
                                    <label>Email Address:</label>
                                    <input type="email" class="form-control" name="email" placeholder="Enter Email Address" required>
                                </div>
                                <center><button type="submit" class="btn btn-info">GO</button></center>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection