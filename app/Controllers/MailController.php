<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 12/9/2019
 * Time: 10:47 PM
 */

namespace App\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;

class MailController extends Controller
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer();
        $this->mail->IsSMTP();
        $this->mail->Host = get_option('smtp_host');
        $this->mail->SMTPAuth = true;
        $this->mail->Username = get_option('smtp_username');
        $this->mail->Password = get_option('smtp_password');
        $this->mail->SMTPSecure = get_option('type_encrytion');
        $this->mail->Port = get_option('smtp_port');

        $this->mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

    }

    public function setEmailFrom($from, $from_label = '')
    {
        $this->mail->setFrom($from, $from_label);
    }

    public function setEmailTo($to)
    {
        $this->mail->AddAddress($to);
    }

    public function setReplyTo($reply_to)
    {
        $this->mail->addReplyTo($reply_to);
    }

    public function sendMail($subject, $body)
    {
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;
        $this->mail->WordWrap = 50;
        $this->mail->IsHTML(true);
        $this->mail->CharSet = 'UTF-8';
        return $this->mail->send();
    }

    public function _emailChecker(Request $request)
    {
        return view('dashboard.screens.'.$this->getFolder().'.email-checker');
    }

    public function _emailCheckerPost(Request $request)
    {
        $email_to = request()->get('email_to');
        $email_content = balanceTags(request()->get('email_content'));
        $email_subject = balanceTags(request()->get('email_subject'));

        if (!is_email($email_to)) {
            return $this->sendJson([
                'status' => 0,
                'message' => __('The email address is incorrect')
            ]);
        }
        $from = get_option('email_from_address');
        $from_name = get_option('email_from');

        $this->setEmailFrom($from, $from_name);
        $this->setEmailTo($email_to);

        $sent = $this->sendMail($email_subject, $email_content);
        if ($sent) {
            return $this->sendJson([
                'status' => 1,
                'title' => __('System Alert'),
                'message' => __('Sent email. Please check your mailbox'),
            ]);
        } else {
            return $this->sendJson([
                'status' => 0,
                'title' => __('System Alert'),
                'message' => $this->mail->ErrorInfo
            ]);
        }
    }
}
