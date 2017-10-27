<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Twitter Timeline</title>
</head>

<body>
@if(Session::has('access_token'))
    @php
        $token = session::get('access_token');
        $screen_name = $token['screen_name'];
        echo '<h3>Twitter Timeline (@'.$screen_name.')</h3>';
    @endphp
@else
    <h3>Twitter Timeline</h3>
@endif
@if(!empty($tweets))
    @foreach($tweets as $tweet)
        <div class="tweet-header">
            <span class="user_name">{{$tweet['user']['name']}}</span> -
            <span>{!! Twitter::linkify('@'.$tweet['user']['screen_name']) !!}</span>
            <span
                    class="time">{!! Twitter::ago($tweet['created_at']) !!}</span>
        </div>
        <div class="tweet-body">
            <p>{!! Twitter::linkify($tweet['text']) !!}</p>
        </div>
        <br>
        <hr>
        <br>


    @endforeach
@else
    <p>We are having a problem with our Twitter Feed right now / No Tweets</p>
@endif

</body>
</html>
