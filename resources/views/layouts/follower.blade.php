<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Twitter Follower</title>
</head>

<body>
@if(Session::has('access_token'))
    @php
        $token = session::get('access_token');
        $screen_name = $token['screen_name'];
        echo '<h3>Twitter Follower (@'.$screen_name.')</h3>';
    @endphp
@else
    <h3>Follower</h3>
@endif

@if(!empty($followers))
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>@handle</th>
            <th>Name</th>
            <th>Followers</th>
            <th>Friends</th>
        </tr>
        </thead>
        <tbody>
        @foreach($followers['users'] as $follower)
            <tr>
                <td>{{$follower['id']}}</td>
                <td>{{$follower['screen_name']}}</td>
                <td>{{$follower['name']}}</td>
                <td>{{$follower['followers_count']}}</td>
                <td>{{$follower['friends_count']}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <p>We are having a problem with our Twitter Feed right now / No Tweets</p>
@endif

</body>
</html>