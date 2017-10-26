<?php

namespace App\Http\Controllers\Twitter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Mockery\Exception;
use Symfony\Component\EventDispatcher\Tests\TestWithDispatcher;
use Thujohn\Twitter\Facades\Twitter;

class TwitterController extends Controller
{
    //
    public function twitterTimeline()
    {
        if(Session::has('access_token'))
        {
            try{
                $tweets = Twitter::getHomeTimeline(['count' => 20, false, true]);
                $followers = Twitter::getFollowers(['count' => 20, false, true]);
                //dd($followers);
                //$twwetsa = Twitter::getUserTimeline(['screen_name' => 'ansarimofid_', 'count' => 20, 'format' => 'array']);
                //dd($twwetsa);
                return view('twitter', compact('tweets','followers'));
            }
            catch (Exception $e){
                dd(Twitter::logs());
            }
        }
        else {
            return redirect(route('twitter.login'));
        }
        return null;
    }

    public function twitterUserTimeline($user){
        dd($user);
    }

}
