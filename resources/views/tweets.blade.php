

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
        <p>No Tweets</p>
    @endif
</div>

<script src="{{asset('js/jquery.bxslider.min.js')}}"></script>
<script src="{{asset('js/ajax.js')}}"></script>