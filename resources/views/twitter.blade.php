@extends('layouts.base')
@section('content')
    <div class="container">
        <center><img src="{{asset('img/apple-touch-icon-192x192.png')}}">
            <h1 class="text-center">Twitter Timeline</h1>
            <a href="/twitter/follower/BSKFanClub/csv">Followers</a>
            <button data-toggle="modal" data-target="#Mail" class="btn btn-primary">Email Tweets as PDF</button>
            <button data-toggle="modal" data-target="#Follower" class="btn btn-primary">Download @handler's Followers</button>
        </center>
        <br>
        <div class='row'>
            <div class='col-md-offset-2 col-md-8 col-sm-12 twitter'>

                <div class="slider">
                    @if(!empty($tweets))
                        @foreach($tweets as $tweet)
                            <div>
                                <div class="tweet">
                                    <div class="row">
                                        <div class="col-xs-2">
                                            <div class="profile_pic">
                                                <img src="{{$tweet['user']['profile_image_url']}}">
                                            </div>
                                        </div>
                                        <div class="col-xs-10">
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
                    <h4 class="modal-title">Email Tweets as PDF</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form role="form" id="sendMail">
                                <div class="form-group col-lg-12">
                                    <label>Email Address:</label>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email Address" required>
                                </div>
                                <center><button type="submit" class="btn btn-info">GO</button></center>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div id="response" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Results</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="loader">
                                <img src="{{asset('img/loader.gif')}}">
                            </div>
                            <div class="response"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div id="Follower" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Download @handler's Follower</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form role="form" id="getFollower">
                                <div class="form-group col-lg-12">
                                    <label>User Name</label>
                                    <input type="text" class="form-control" name="userHandler" id="userHandler" placeholder="@handler" required>
                                </div>
                                <div class="form-group col-lg-12">
                                    <label>Select Download Type</label>
                                    <select class="form-control" name="type" id="type" required>
                                        <option selected disabled>Select Download Type</option>
                                        <option value="csv">CSV</option>
                                        <option value="xls">XLS</option>
                                        <option value="google">Google Spreadsheet</option>
                                        <option value="pdf">PDF</option>
                                        <option value="xml">XML</option>
                                        <option value="json">JSON</option>
                                    </select>
                                </div>
                                <center><button type="submit" class="btn btn-info">Download</button></center>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection