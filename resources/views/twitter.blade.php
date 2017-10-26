@extends('layouts.base')
@section('content')
    <div class="twitter">
        @if(!empty($tweets))
            @foreach($tweets as $tweet)
                <div class="twitter-block">
                    {{$tweet->user->name}} ({!! Twitter::linkify('@'.$tweet->user->screen_name) !!})
                    <p>{!! Twitter::linkify($tweet->text) !!}</p>
                    @if(!empty($tweet->extended_entities->media))

                        @foreach($tweet->extended_entities->media as $v)

                            <img src="{{ $v->media_url_https}}" style="width:100px;">

                        @endforeach

                    @endif
                    <span class="timeago" title="{{$tweet->created_at}}">{{ date('H:i, M d', strtotime($tweet->created_at)) }}</span>
                    <span class="tintent">
                    <a href="https://twitter.com/intent/tweet?in_reply_to={{$tweet->id}}">Reply</a>
                    <a href="https://twitter.com/intent/retweet?tweet_id={{$tweet->id}}">Retweet</a>
                    <a href="https://twitter.com/intent/favorite?tweet_id={{$tweet->id}}">Favorite</a>
                </span>
                </div>
            @endforeach
        @else
            <p>We are having a problem with our Twitter Feed right now.</p>
        @endif
    </div>
    @if(!empty($followers))
        @foreach($followers->users as $follower)
            <p><a data-id="{{$follower->screen_name}}" class="follower">{{$follower->name}}</a></p>
        @endforeach
    @else
        <p>We are having a problem with our Twitter Feed right now.</p>
    @endif

@endsection