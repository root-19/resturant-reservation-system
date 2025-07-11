<?php
require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Test email functionality
function testEmail() {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'hperformanceexhaust@gmail.com';
        $mail->Password = 'wolv wvyy chhl rvvm';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        
        // Enable debugging
        $mail->SMTPDebug = 2; // Enable verbose debug output
        
        // Recipients
        $mail->setFrom('hperformanceexhaust@gmail.com', 'Restaurant Reservation System');
        $mail->addAddress('test@example.com', 'Test User'); // Replace with your test email

        // Content
        $mail->isHTML(false);
        $mail->Subject = "Test Email - Reservation System";
        $mail->Body = "This is a test email to verify the email functionality is working properly.\n\n";
        $mail->Body .= "If you receive this email, the email notification system is working correctly.\n\n";
        $mail->Body .= "Thank you!";

        // Send the email
        $result = $mail->send();
        
        if ($result) {
            echo "Test email sent successfully!\n";
            echo "Check your email inbox and spam folder.\n";
        } else {
            echo "Failed to send test email.\n";
        }
        
    } catch (Exception $e) {
        echo "Email sending failed. Error: " . $e->getMessage() . "\n";
        echo "PHPMailer Error: {$mail->ErrorInfo}\n";
    }
}

// Run the test
echo "Testing email functionality...\n";
testEmail();
?> 