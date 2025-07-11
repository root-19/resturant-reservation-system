<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Debug: Log the incoming request data
error_log("POST data: " . print_r($_POST, true));
error_log("FILES data: " . print_r($_FILES, true));

$id = $_POST['id'] ?? null;
$day = $_POST['day'] ?? null;
$time = $_POST['time'] ?? null;
$table_size = $_POST['table_size'] ?? null;

if (!$id || !$day || !$time || !$table_size) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

$image_path = null;
if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileTmp = $_FILES['receipt']['tmp_name'];
    $fileName = uniqid('receipt_') . '_' . basename($_FILES['receipt']['name']);
    $targetFile = $uploadDir . $fileName;
    
    if (move_uploaded_file($fileTmp, $targetFile)) {
        $image_path = 'uploads/' . $fileName;
    } else {
        error_log("Failed to move uploaded file. Error: " . error_get_last()['message']);
        echo json_encode(['success' => false, 'message' => 'Failed to upload receipt. Please try again.']);
        exit;
    }
}

try {
    $pdo = Database::connect();
    
    // Get user information for email notification
    $stmt = $pdo->prepare("SELECT name, email FROM reservations WHERE id = ?");
    $stmt->execute([$id]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($image_path) {
        $stmt = $pdo->prepare('UPDATE reservations SET day = ?, time = ?, table_size = ?, image_path = ? WHERE id = ?');
        $result = $stmt->execute([$day, $time, $table_size, $image_path, $id]);
    } else {
        $stmt = $pdo->prepare('UPDATE reservations SET day = ?, time = ?, table_size = ? WHERE id = ?');
        $result = $stmt->execute([$day, $time, $table_size, $id]);
    }
    
    if ($result) {
        // Send email notification if update was successful and reservation exists
        if ($reservation) {
            $mail = new PHPMailer(true);
            $emailSent = false;
            
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; 
                $mail->SMTPAuth = true;
                $mail->Username = 'hperformanceexhaust@gmail.com';
                $mail->Password = 'wolv wvyy chhl rvvm';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                
                // Recipients
                $mail->setFrom('hperformanceexhaust@gmail.com', 'Restaurant Reservation System');
                $mail->addAddress($reservation['email'], $reservation['name']);

                // Content
                $mail->isHTML(false);
                $mail->Subject = "Reservation Updated";
                
                $message = "Hi {$reservation['name']},\n\nYour reservation has been updated successfully!\n\n";
                $message .= "Updated Reservation Details:\n";
                $message .= "Date: " . date('F d, Y', strtotime($day)) . "\n";
                $message .= "Time: " . date('h:i A', strtotime($time)) . "\n";
                $message .= "Table Size: $table_size Pax\n";
                $message .= "Reservation ID: $id\n\n";
                
                if ($image_path) {
                    $message .= "Receipt uploaded successfully. Your reservation is now pending approval.\n\n";
                } else {
                    $message .= "Reservation details updated. Please upload your receipt to complete the process.\n\n";
                }
                
                $message .= "Thank you!";
                
                $mail->Body = $message;

                // Send the email
                $emailSent = $mail->send();
                
                if ($emailSent) {
                    error_log("Email sent successfully to: {$reservation['email']}");
                } else {
                    error_log("Failed to send email to: {$reservation['email']}");
                }
                
            } catch (Exception $e) {
                error_log("Email sending failed for reservation ID: $id, Email: {$reservation['email']}, Error: " . $e->getMessage());
            }
        }
        
        if ($image_path) {
            echo json_encode(['success' => true, 'message' => 'Receipt sent!']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Reservation updated successfully']);
        }
    } else {
        error_log("Database update failed. Error: " . print_r($stmt->errorInfo(), true));
        echo json_encode(['success' => false, 'message' => 'Failed to update reservation. Please try again.']);
    }
} catch (Exception $e) {
    error_log("Exception occurred: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} 







