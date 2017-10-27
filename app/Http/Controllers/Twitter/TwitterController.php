<?php

namespace App\Http\Controllers\Twitter;

use App\Http\Controllers\Mail\MailController;
use PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Mockery\Exception;
use Thujohn\Twitter\Facades\Twitter;

class TwitterController extends Controller
{
    //
    public function twitterTimeline()
    {
        if (Session::has('access_token')) {
            try {
                $tweets = Twitter::getHomeTimeline(['count' => 20, 'format' => 'array']);
                $followers = Twitter::getFollowers(['count' => 20, 'format' => 'array']);
                return view('twitter', compact('tweets', 'followers'));
            } catch (Exception $e) {
                dd(Twitter::logs());
            }
        } else {
            return redirect(route('twitter.login'));
        }
        return null;

    }

    public function twitterUserTimeline($user)
    {
        if (Session::has('access_token')) {
            try {
                $tweets = Twitter::getUserTimeline(['screen_name' => $user, 'count' => 20, 'format' => 'array']);
                $response = view('tweets', compact('tweets'));
                return $response;
            } catch (Exception $e) {
                dd(Twitter::logs());
            }
        } else {
            return redirect(route('twitter.login'));
        }
        return null;

    }

    public function sendMail(Request $request)
    {
        $email = $request->get('email');

        if (Session::has('access_token')) {
            try {
                $token = session::get('access_token');
                $screen_name = $token['screen_name'];
                $tweets = Twitter::getHomeTimeline(['count' => 20, 'format' => 'array']);
                PDF::setOptions(['dpi' => 150, 'defaultFont' => 'serif']);
                $pdf = PDF::loadView('mail.tweets', compact('tweets'));
                $pdf = $pdf->Output('S');
                $pdfName = "Twitter Timeline (@" . $screen_name . ").pdf";
                $mail = new MailController();

                $subject = "Twitter Timeline (@" . $screen_name . ")";

                $body = view('mail.tweets', compact('tweets'));

                $mail1 = $mail->Mail($email, 'Unknown User', $subject, $body, $pdf, $pdfName);

                if (!$mail1) {
                    dd($mail1);
                } else {
                    return view('mail.success');
                }

            } catch (Exception $e) {
                dd(Twitter::logs());
            }
        } else {
            return redirect(route('twitter.login'));
        }

        return null;

    }

}
