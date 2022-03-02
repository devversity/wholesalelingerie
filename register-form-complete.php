<?php

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer;

$message = "<p>";
foreach ($_POST as $key => $value) {

    if (is_array($value)) {
        $value = implode(", ", $value);
    }
    $message.= "<b>".ucwords(str_replace("_", " ", $key))."</b>: ".strip_tags($value)."<br/>";
}
$message .= "</p>";


try {
    // Recipients
    $mail->setFrom('noreply@wholesalelingerie.co.uk', 'Wholesale Lingerie');
    $mail->addAddress('sales@kevco.co.uk');
    //$mail->addAddress('james@garveys.co.uk');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = 'Registration Sign Up';
    $mail->Body = $message;

    if (!empty($_FILES)) {
        foreach ($_FILES as $key => $file) {
            if (!empty($file['tmp_name'])) {
                $mail->addAttachment($file['tmp_name'], $key."_".$file['name']);    // Optional name
            }

        }
    }

    $mail->send();
    header("Location: register-form.php?success=Y");

} catch (Exception $ex) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}