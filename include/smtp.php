<?php

use PHPMailer\PHPMailer\PHPMailer;

// MESSAGE & EMAIL CONFIGURATION FOR SCRIPT
class message{
    private $conn;
    public function send_mail($email, $message, $subject){

        $mail = new PHPMailer();
        //SMTP Settings
        $mail->isSMTP();
        $mail->Host = "#"; // Change Email Host
        $mail->SMTPAuth = true;
        $mail->Username = "#"; // Change Email Address
        $mail->Password = '#'; // Change Email Password
        $mail->Port = 465; //587
        $mail->SMTPSecure = "ssl"; //tls

        //Email Settings
        $mail->isHTML(true);
        $mail->setFrom('support@dirtyscripts.shop','Ofofonobs Developer'); // Change
        $mail->addAddress($email);
        $mail->AddReplyTo("support@dirtyscripts.shop", "Ofofonobs Developer"); // Change
        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        $mail->Send();


    }

}


?>