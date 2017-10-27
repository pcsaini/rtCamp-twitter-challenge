<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Thujohn\Twitter\Facades\Twitter;


class AuthController extends Controller
{
    //
    public function index(){
        return view('index');
    }

    public function login(){
        try {
            if(Session::has('access_token')) {
                return redirect(route('twitterTimeline'));
            }
            else {
                $sign_in = true;
                $force_login = false;
                Twitter::reconfig(['token'=> '', 'secret'=> '']);
                $token = Twitter::getRequestToken(route('twitter.callback'));
                if(isset($token['oauth_token_secret'])) {
                    $url = Twitter::getAuthorizeURL($token, $sign_in, $force_login);
                    Session::put('oauth_state', 'start');
                    Session::put('oauth_request_token', $token['oauth_token']);
                    Session::put('oauth_request_token_secret', $token['oauth_token_secret']);
                    return redirect($url);
                }
                return redirect(route('index'));
            }
        }
        catch (Exception $e) {
            dd($e);
        }

        return null;
    }

    public function loginCallBack()
    {
        if(Session::has('oauth_request_token')) {
            $request_token = [
                'token'=>Session::get('oauth_request_token'),
                'secret'=>Session::get('oauth_request_token_secret'),
            ];

            Twitter::reconfig($request_token);

            $oauth_verifier = false;

            if(Input::has('oauth_verifier')) {
                $oauth_verifier = Input::get('oauth_verifier');
                $token = Twitter::getAccessToken($oauth_verifier);
            }

            if(!isset($token['oauth_token_secret'])) {
                return redirect(route('index'));
            }

            $credentials = Twitter::getCredentials(['format'=>'array']);

            if(is_array($credentials) && !isset($credentials->error))
            {
                try {
                    Session::put('access_token', $token);
                    return redirect(route('twitterTimeline'));
                } catch (\Exception $e) {
                    dd($e);
                }
            }
            return redirect(route('index'));
        }

        return null;
    }
    public function logoutCall()
    {
        Session::flush();
        //flash()->success('Logged out Successfully');
        return redirect(route('index'));
    }
    public function loginError()
    {
        return redirect(route('index'));
    }
}
