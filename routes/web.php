<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/index', function () {
    return view('index');
});
Route::get('/login/twitter',['as' => 'twitter.login','uses' => 'Auth\AuthController@Login']);

Route::get('/login/twitter/callback',['as' => 'twitter.callback', 'uses' => 'Auth\AuthController@loginCallBack']);

Route::get('/twitter/logout', ['as'=>'twitter.logout','uses'=>'Auth\AuthController@logoutCall']);

Route::get('/loginError',['as'=>'twitter.error','uses'=>'Auth\AuthController@loginError']);

Route::get('/twitter',['as'=>'twitterTimeline','uses' => 'Twitter\TwitterController@twitterTimeline']);

Route::get('/twitter/{user}',['as'=>'twitterUserTimeline','uses' => 'Twitter\TwitterController@twitterUserTimeline']);