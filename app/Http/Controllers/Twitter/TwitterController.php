<?php

namespace App\Http\Controllers\Twitter;

use App\Http\Controllers\Mail\MailController;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use File;
use Sheets;
use Google;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Mockery\Exception;
use Thujohn\Twitter\Facades\Twitter;
use XMLWriter;

ini_set('max_execution_time', '300');

class TwitterController extends Controller
{
    //

    public function twitterTimeline()
    {
        if (Session::has('access_token')) {
            try {
                $tweets = Twitter::getHomeTimeline(['count' => 10, 'format' => 'array']);
                $followers = Twitter::getFollowers(['count' => 10, 'format' => 'array']);
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
                $tweets = Twitter::getUserTimeline(['screen_name' => $user, 'count' => '10', 'format' => 'array']);
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

    public function mail($email)
    {

        if (Session::has('access_token')) {
            try {
                $token = session::get('access_token');
                $screen_name = $token['screen_name'];
                $tweets = Twitter::getUserTimeline(['screen_name' => $screen_name, 'count' => 3200, 'format' => 'array']);
                PDF::setOptions(['dpi' => 150, 'defaultFont' => 'serif']);
                $pdf = PDF::loadView('mail.tweets', compact('tweets'));
                $pdf = $pdf->Output('S');
                $pdfName = "Twitter Timeline (@" . $screen_name . ").pdf";
                $mail = new MailController();

                $subject = "Twitter Timeline (@" . $screen_name . ")";

                $body = "Twitter Timeline (@" . $screen_name . ")  -pfa";

                $mail1 = $mail->Mail($email, 'Unknown User', $subject, $body, $pdf, $pdfName);

                if (!$mail1) {
                    dd($mail1);
                } else {
                    $response = [
                        'done' => true,
                    ];
                    return $response;
                }

            } catch (Exception $e) {
                dd(Twitter::logs());
            }
        } else {
            return redirect(route('twitter.login'));
        }

        return null;
    }

    public function followers_temp($handler, $type, Request $request)
    {
        if (Session::has('access_token')) {
            try {
                $followers = Twitter::getFollowersIds(['screen_name' => $handler, 'count' => 5000, 'format' => 'array']);
                $users = $followers['ids'];

                $ticks = 0;
                while ($ticks < 9) {
                    $ticks++;
                    if (isset($followers['next_cursor']) !== 0) {
                        try {
                            $next_cursor = $followers['next_cursor'];
                            $followers = Twitter::getFollowersIds(['screen_name' => $handler, 'format' => 'array', 'count' => 5000, 'next_cursor'=> $next_cursor]);
                            if (!$followers) break;

                            $more_users = $followers['ids'];
                            $users = array_merge($users,$more_users);
                        } catch (Exception $e) {
                             dd(Twitter::logs());
                        }
                    }else{
                        $ticks = 0;
                        break;
                    }
                }
                
                if ($type == 'csv') {
                    $file = time() . "_followers.csv";
                    $filename = "upload/" . time() . "_followers.csv";
                    $handle = fopen($filename, 'w+');
                    fputcsv($handle, array('ID'));

                    foreach ($users as $follower) {
                        fputcsv($handle, array($follower));
                    }
                    fclose($handle);

                    $headers = array(
                        'Content-Type' => 'text/csv',
                    );

                    response()->download($filename, $file, $headers);
                    $response = [
                        'done' => true,
                        'type' => 'csv',
                        'link' => $file
                    ];
                    return $response;

                } elseif ($type == 'xls') {
                    $followerArray[] = array('ID');
                    foreach ($users as $follower) {
                        $followerArray[] = array($follower);
                    }
                    $filename = time() . '_follower';

                    Excel::create($filename, function ($excel) use ($followerArray) {

                        $excel->setTitle('Followers');
                        $excel->setCreator('Twitter')->setCompany('pcsaini');
                        $excel->setDescription('Follower File');

                        $excel->sheet('followers', function ($sheet) use ($followerArray) {
                            $sheet->fromArray($followerArray, null, 'A1', false, false);
                        });

                    })->store('xls', 'upload/');
                    $response = [
                        'done' => true,
                        'type' => 'xls',
                        'link' => $filename . '.xls'
                    ];
                    return $response;

                } elseif ($type == 'pdf') {
                    PDF::setOptions(['dpi' => 150, 'defaultFont' => 'serif']);
                    $filename = time() . '_follower.pdf';
                    $pdf = PDF::loadView('layouts.followerIds', compact('users'));
                    $pdf->save('upload/' . $filename);

                    $response = [
                        'done' => true,
                        'type' => 'pdf',
                        'link' => $filename
                    ];

                    return $response;

                } elseif ($type == 'xml') {
                    $filename = "followers.xml";
                    $xml = new XMLWriter();
                    $xml->openURI('upload/followers.xml');
                    $xml->startDocument();
                    $xml->startElement('followers');
                    foreach ($users as $follower) {
                        $xml->startElement('data');
                        $xml->writeAttribute('id', $follower);
                        $xml->endElement();
                    }
                    $xml->endElement();
                    $xml->endDocument();

                    $xml->flush();

                    $response = [
                        'done' => true,
                        'type' => 'xml',
                        'link' => $filename
                    ];

                    return $response;

                } elseif ($type == 'json') {
                    $followerArray = array();
                    foreach ($users as $follower) {
                        $followerArray[] = array("id" => $follower);
                    }
                    $data = json_encode($followerArray);
                    $file = time() . '_followers.json';
                    $destinationPath = public_path() . "/upload/";
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }
                    File::put($destinationPath . $file, $data);
                    response()->download($destinationPath . $file);

                    $response = [
                        'done' => true,
                        'type' => 'json',
                        'link' => $file
                    ];
                    return $response;

                } elseif ($type == 'google') {
                    $google_redirect_url = route('glogin');
                    $gClient = new \Google_Client();
                    $gClient->setAuthConfigFile(public_path('google.json'));
                    $gClient->setRedirectUri($google_redirect_url);
                    $gClient->setScopes("https://www.googleapis.com/auth/drive", "https://www.googleapis.com/auth/drive.file", "https://www.googleapis.com/auth/drive.appdata");
                    session_start();
                    $service = new \Google_Service_Drive($gClient);
                    if ($request->get('code')) {
                        $gClient->authenticate($request->get('code'));
                        $request->session()->put('token', $gClient->getAccessToken());
                    }
                    if ($request->session()->get('token')) {
                        $gClient->setAccessToken($request->session()->get('token'));
                    } else {
                        $authUrl = $gClient->createAuthUrl();

                        $response = [
                            'done' => 'login',
                            'type' => 'google',
                            'link' => $authUrl,
                        ];
                        return $response;
                    }
                    //Create New Spreadsheet
                    $fileMetadata = new \Google_Service_Drive_DriveFile(array(
                        'name' => 'Followers',
                        'mimeType' => 'application/vnd.ms-excel'));

                    // Get the contents of the file uploaded
                    $data = fopen('followers.xlsx', 'w');
                    $header = array("id");
                    fputcsv($data, $header, "\t");
                    foreach ($users as $follower) {
                        fputcsv($data, array($follower), "\t");
                    }

                    fclose($data);
                    $data = file_get_contents('followers.xlsx');

                    $driveInfo = $service->files->create($fileMetadata, array(
                        'data' => $data,
                        'mimeType' => 'application/vnd.ms-excel',
                    ));
                    unlink('followers.xlsx');
                    $response = [
                        'done' => true,
                        'type' => 'google',
                        'link' => $driveInfo['id']
                    ];
                    return $response;
                } else {
                    echo "No";
                }
            } catch (Exception $e) {
                dd(Twitter::logs());
            }
        } else {
            return redirect(route('twitter.login'));
        }
        return null;
    }

    public function followers($handler, $type, Request $request)
    {
        if (Session::has('access_token')) {
            try {
                $followers = Twitter::getFollowers(['screen_name' => $handler, 'count' => 200, 'format' => 'array']);
                $users = $followers['users'];

                $ticks = 0;
                while ($ticks < 8) {
                    $ticks++;
                    if (isset($followers['next_cursor']) !== 0) {
                        try {
                            $next_cursor = $followers['next_cursor'];
                            $followers = Twitter::getFollowers(['screen_name' => $handler, 'format' => 'array', 'count' => 200, 'next_cursor'=> $next_cursor]);
                            if (!$followers) break;

                            $more_users = $followers['users'];
                            $users = array_merge($users,$more_users);
                        } catch (Exception $e) {
                             dd(Twitter::logs());
                        }
                    }else{
                        $ticks = 0;
                        break;
                    }
                }

                //$followers = Twitter::getFollowers(['screen_name' => $handler, 'format' => 'array']);
                if ($type == 'csv') {
                    $file = time() . "_followers.csv";
                    $filename = "upload/" . time() . "_followers.csv";
                    $handle = fopen($filename, 'w+');
                    fputcsv($handle, array('ID', '@handle', 'Name', 'Followers', 'Friends'));

                    foreach ($users as $follower) {
                        fputcsv($handle, array($follower['id'], $follower['screen_name'], $follower['name'], $follower['followers_count'], $follower['friends_count']));
                    }
                    fclose($handle);

                    $headers = array(
                        'Content-Type' => 'text/csv',
                    );

                    response()->download($filename, $file, $headers);
                    $response = [
                        'done' => true,
                        'type' => 'csv',
                        'link' => $file
                    ];
                    return $response;

                } elseif ($type == 'xls') {
                    $followerArray[] = array('ID', 'Screen_name', 'Name', 'Followers', 'Friends');
                    foreach ($users as $follower) {
                        $followerArray[] = array($follower['id'], $follower['screen_name'], $follower['name'], $follower['followers_count'], $follower['friends_count']);
                    }
                    $filename = time() . '_follower';

                    Excel::create($filename, function ($excel) use ($followerArray) {

                        $excel->setTitle('Followers');
                        $excel->setCreator('Twitter')->setCompany('pcsaini');
                        $excel->setDescription('Follower File');

                        $excel->sheet('followers', function ($sheet) use ($followerArray) {
                            $sheet->fromArray($followerArray, null, 'A1', false, false);
                        });

                    })->store('xls', 'upload/');
                    $response = [
                        'done' => true,
                        'type' => 'xls',
                        'link' => $filename . '.xls'
                    ];
                    return $response;

                } elseif ($type == 'pdf') {
                    PDF::setOptions(['dpi' => 150, 'defaultFont' => 'serif']);
                    $filename = time() . '_follower.pdf';
                    $pdf = PDF::loadView('layouts.follower', compact('users'));
                    $pdf->save('upload/' . $filename);

                    $response = [
                        'done' => true,
                        'type' => 'pdf',
                        'link' => $filename
                    ];

                    return $response;

                } elseif ($type == 'xml') {
                    $filename = "followers.xml";
                    $xml = new XMLWriter();
                    $xml->openURI('upload/followers.xml');
                    $xml->startDocument();
                    $xml->startElement('followers');
                    foreach ($users as $follower) {
                        $xml->startElement('data');
                        $xml->writeAttribute('id', $follower['id']);
                        $xml->writeAttribute('screen_name', $follower['screen_name']);
                        $xml->writeAttribute('name', $follower['name']);
                        $xml->writeAttribute('follower', $follower['followers_count']);
                        $xml->writeAttribute('friends', $follower['friends_count']);
                        $xml->endElement();
                    }
                    $xml->endElement();
                    $xml->endDocument();

                    $xml->flush();

                    $response = [
                        'done' => true,
                        'type' => 'xml',
                        'link' => $filename
                    ];

                    return $response;

                } elseif ($type == 'json') {
                    $followerArray = array();
                    foreach ($users as $follower) {
                        $followerArray[] = array("id" => $follower['id'], "screen_name" => $follower['screen_name'], "name" => $follower['name'], "follower" => $follower['followers_count'], "friends" => $follower['friends_count']);
                    }
                    $data = json_encode($followerArray);
                    $file = time() . '_followers.json';
                    $destinationPath = public_path() . "/upload/";
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }
                    File::put($destinationPath . $file, $data);
                    response()->download($destinationPath . $file);

                    $response = [
                        'done' => true,
                        'type' => 'json',
                        'link' => $file
                    ];
                    return $response;

                } elseif ($type == 'google') {
                    $google_redirect_url = route('glogin');
                    $gClient = new \Google_Client();
                    $gClient->setAuthConfigFile(public_path('google.json'));
                    $gClient->setRedirectUri($google_redirect_url);
                    $gClient->setScopes("https://www.googleapis.com/auth/drive", "https://www.googleapis.com/auth/drive.file", "https://www.googleapis.com/auth/drive.appdata");
                    session_start();
                    $service = new \Google_Service_Drive($gClient);
                    if ($request->get('code')) {
                        $gClient->authenticate($request->get('code'));
                        $request->session()->put('token', $gClient->getAccessToken());
                    }
                    if ($request->session()->get('token')) {
                        $gClient->setAccessToken($request->session()->get('token'));
                    } else {
                        $authUrl = $gClient->createAuthUrl();

                        $response = [
                            'done' => 'login',
                            'type' => 'google',
                            'link' => $authUrl,
                        ];
                        return $response;
                    }
                    //Create New Spreadsheet
                    $fileMetadata = new \Google_Service_Drive_DriveFile(array(
                        'name' => 'Followers',
                        'mimeType' => 'application/vnd.ms-excel'));

                    // Get the contents of the file uploaded
                    $data = fopen('followers.xlsx', 'w');
                    $header = array("id", "screen_name", "name", "followers", "friends");
                    fputcsv($data, $header, "\t");
                    foreach ($users as $follower) {
                        fputcsv($data, array($follower['id'], $follower['screen_name'], $follower['name'], $follower['followers_count'], $follower['friends_count']), "\t");
                    }

                    fclose($data);
                    $data = file_get_contents('followers.xlsx');

                    $driveInfo = $service->files->create($fileMetadata, array(
                        'data' => $data,
                        'mimeType' => 'application/vnd.ms-excel',
                    ));
                    unlink('followers.xlsx');
                    $response = [
                        'done' => true,
                        'type' => 'google',
                        'link' => $driveInfo['id']
                    ];
                    return $response;
                } else {
                    echo "No";
                }
            } catch (Exception $e) {
                dd(Twitter::logs());
            }
        } else {
            return redirect(route('twitter.login'));
        }
        return null;
    }

    public function googleLogin(Request $request)
    {
        $google_redirect_url = route('glogin');
        $gClient = new \Google_Client();
        $gClient->setAuthConfigFile(public_path('google.json'));
        $gClient->setRedirectUri($google_redirect_url);
        $gClient->setScopes("https://www.googleapis.com/auth/drive", "https://www.googleapis.com/auth/drive.file", "https://www.googleapis.com/auth/drive.appdata");
        $google_oauthV2 = new \Google_Service_Oauth2($gClient);
        if ($request->get('code')) {
            $gClient->authenticate($request->get('code'));
            $request->session()->put('token', $gClient->getAccessToken());
        }
        if ($request->session()->get('token')) {
            $gClient->setAccessToken($request->session()->get('token'));
        }
        if ($gClient->getAccessToken()) {
            return redirect()->route('twitterTimeline');
        } else {
            //For Guest user, get google login url
            $authUrl = $gClient->createAuthUrl();
            return redirect()->to($authUrl);
        }
    }
}
