<?php

use PHPMailer\PHPMailer\PHPMailer;

class message{
    private $conn;
    public function send_mail($email, $message, $subject){

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = getenv('SMTP_HOST') ?: 'localhost';
        $mail->SMTPAuth = true;
        $mail->Username = getenv('SMTP_USER') ?: '';
        $mail->Password = getenv('SMTP_PASS') ?: '';
        $mail->Port = intval(getenv('SMTP_PORT') ?: 465);
        $mail->SMTPSecure = getenv('SMTP_SECURE') ?: 'ssl';

        $from_email = getenv('SMTP_FROM_EMAIL') ?: getenv('SMTP_USER') ?: 'noreply@example.com';
        $from_name  = getenv('SMTP_FROM_NAME')  ?: 'Bankpro Banking';

        $mail->isHTML(true);
        $mail->setFrom($from_email, $from_name);
        $mail->addAddress($email);
        $mail->AddReplyTo($from_email, $from_name);
        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        $mail->Send();
    }
}

?>
