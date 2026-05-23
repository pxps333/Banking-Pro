<?php

use PHPMailer\PHPMailer\PHPMailer;

// ── SMTP / Email Configuration ────────────────────────────────────────────────
// In cPanel: go to Email Accounts, create an email address such as
// noreply@northwestregisteredonlinebanking.com, then copy the SMTP settings
// shown in the "Connect Devices" section into the constants below.

define('SMTP_HOST',       'mail.northwestregisteredonlinebanking.com'); // << CHANGE if different
define('SMTP_USER',       'noreply@northwestregisteredonlinebanking.com'); // << CHANGE
define('SMTP_PASS',       'YOUR_EMAIL_PASSWORD');  // << CHANGE
define('SMTP_PORT',       465);
define('SMTP_SECURE',     'ssl');
define('SMTP_FROM_EMAIL', 'noreply@northwestregisteredonlinebanking.com'); // << CHANGE
define('SMTP_FROM_NAME',  'Northwest Registered Online Banking');

class message {
    public function send_mail($email, $message, $subject) {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->Port       = SMTP_PORT;
        $mail->SMTPSecure = SMTP_SECURE;

        $mail->isHTML(true);
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($email);
        $mail->AddReplyTo(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        $mail->send();
    }
}
