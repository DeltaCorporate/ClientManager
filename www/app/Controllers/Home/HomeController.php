<?php

namespace App\Controllers\Home;

use App\Exceptions\ModelColumnNotfound;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;


class HomeController
{

    /**
     * @throws ModelColumnNotfound
     */
    public function index()
    {

        return render('home.accueil');
    }

    public function test()
    {
        $css = file_get_contents("assets/css/app.css");
        $message = render("emails.register", ["css"=>$css], true);


        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = 'progMail';
            $mail->SMTPAuth = false;
//            $mail->Username = '4410207e6257e428b8e3c3a50df7465b';
//            $mail->Password = 'bede9b87b737c0cd556ce4187fa09dcb';
            $mail->Port = 25;
            $mail->setFrom('from@example.com', 'Mailer');
            $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
            $mail->addAddress('ellen@example.com');               //Name is optional
            $mail->addReplyTo('info@example.com', 'Information');

            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Here is the subject';
            $mail->Body = $message;

            $mail->send();
            echo 'Message has been sent';

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }


    }

}