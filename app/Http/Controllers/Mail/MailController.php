<?php

namespace App\Http\Controllers\Mail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PHPMailer\PHPMailer\PHPMailer;

class MailController extends Controller
{
    //
    public function Mail($to, $name, $subject, $body,$pdf,$pdfName, $altBody = "")
    {
        $mail = new PHPMailer;

        try {
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->IsSMTP();  // telling the class to use SMTP
            $mail->Mailer = env('MAIL_DRIVER');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION');
            $mail->Host = env('MAIL_HOST');
            $mail->Port = env('MAIL_PORT');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME'); // SMTP username
            $mail->Password = env('MAIL_PASSWORD'); // SMTP password
            $mail->Priority = 1;

            $mail->setFrom(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
            $mail->addAddress($to, $name);// Add a recipient
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = $altBody;
            $mail->isHTML(true);
            $mail->AddStringAttachment($pdf, $pdfName, 'base64', 'application/pdf');

            $sent = $mail->send();
            return true;
        } catch (Exception $e) {
            return $mail->ErrorInfo;
        }
    }

}
