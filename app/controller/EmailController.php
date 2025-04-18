<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

class EmailController {
    public function sendEmail($to, $subject, $message) {
      $mail = new PHPMailer(true);
  
      try {
        $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'hperformanceexhaust@gmail.com';
            $mail->Password = 'wolv wvyy chhl rvvm';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
  
            $mail->setFrom('hperformanceexhaust@gmail.com', 'HPerformance');

        $mail->addAddress($to);
  
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
  
        $mail->send();
      } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }
    }
  }