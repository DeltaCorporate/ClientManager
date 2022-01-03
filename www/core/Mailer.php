<?php

namespace Core;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mailer
{
    private PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->mailer->SMTPDebug = SMTP::DEBUG_OFF;
        $this->mailer->isSMTP();
        $this->mailer->Host = 'progMail';
        $this->mailer->SMTPAuth = false;
        $this->mailer->Port = 25;
        $this->mailer->isHTML(true);
        return $this->mailer;
    }



    public function send(array $from, array $to,string $subject,string $body,array $data = [],array $moreAddress = [])
    {
        $content = render($body, $data, true);
        $mail = $this->mailer;
        try {
            $mail->setFrom($from['email'], $from['name'] ?? null);
            $mail->addAddress($to['email'], $to['name']??null);
            if(!empty($moreAddress)){
                foreach ($moreAddress as $address){
                    $mail->addAddress($address['email'], $address['name']??null);
                }
            }
            $mail->Subject = $subject;
            $mail->Body = $content;

            $mail->send();

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$e->getMessage()}";
        }
    }

}