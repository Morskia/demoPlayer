<?php

namespace Mini\Libs;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{

    static public function sendMail($from, $f_name, $to, $t_name, $subject, $body)
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                     // Enable verbose debug output
            // Set mailer to use SMTP
            $mail->SMTPAuth = true;
            $mail->Host = EMAIL_HOST;                        // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = EMAIL;               // SMTP username
            $mail->Password = EMAIL_PASSWORD;                         // SMTP password
            $mail->SMTPSecure = 'tls';                                // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;                                        // TCP port to connect to
            //Recipients
            $mail->setFrom($from, $f_name);
            $mail->addAddress($to, $t_name);     // Add a recipient
            $mail->addReplyTo($from, $f_name);
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->send();
//          echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}