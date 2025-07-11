<?php

require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ReservationController {
    private $reservationModel;
    private $pdo;

    public function __construct($pdo) {
        $this->reservationModel = new Reservation($pdo);
        $this->pdo = $pdo;
    }

    private function sendEmailNotification($to, $name, $subject, $message) {
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
            $mail->addAddress($to, $name);

            // Content
            $mail->isHTML(false);
            $mail->Subject = $subject;
            $mail->Body = $message;

            // Send the email
            $emailSent = $mail->send();
            
            if ($emailSent) {
                error_log("Email sent successfully to: $to");
            } else {
                error_log("Failed to send email to: $to");
            }
            
        } catch (Exception $e) {
            error_log("Email sending failed for: $to, Error: " . $e->getMessage());
            error_log("PHPMailer Error: {$mail->ErrorInfo}");
        }
        
        return $emailSent;
    }

    public function handleReservation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if user is logged in
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['error'] = 'Please login to make a reservation';
                header('Location: /login');
                exit;
            }

            $tableSize = $_POST['table_size'];
            $day = $_POST['day'];
            $time = $_POST['time'];

            // Get user information from database
            $stmt = $this->pdo->prepare("SELECT username, email FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $_SESSION['error'] = 'User information not found';
                header('Location: /login');
                exit;
            }

            // Handle image upload
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
                $uploadPath = $uploadDir . $imageName;

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    $imagePath = $uploadPath;
                }
            }

            // Create the reservation using user's information
            $reservationId = $this->reservationModel->createReservation(
                $tableSize, 
                $day, 
                $time, 
                $user['username'], 
                $user['email'], 
                $imagePath
            );

            // Insert cart items into the database, linking them to the new reservation
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $cartItem) {
                    $menuItemId = $cartItem['id'];
                    $quantity = $cartItem['quantity'];
                    $totalPrice = $cartItem['price'] * $quantity;

                    // Insert into `cart_items` table
                    $stmt = $this->pdo->prepare("INSERT INTO cart_items (reservation_id, menu_item_id, quantity, total_price) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$reservationId, $menuItemId, $quantity, $totalPrice]);
                }

                // Clear the cart after processing
                unset($_SESSION['cart']);
            }

            // Send confirmation email to user
            $subject = "Reservation Confirmation";
            $message = "Hi {$user['username']},\n\nYour reservation has been successfully created!\n\n";
            $message .= "Reservation Details:\n";
            $message .= "Date: " . date('F d, Y', strtotime($day)) . "\n";
            $message .= "Time: " . date('h:i A', strtotime($time)) . "\n";
            $message .= "Table Size: $tableSize Pax\n";
            $message .= "Reservation ID: $reservationId\n\n";
            $message .= "Your reservation is currently pending approval. You will receive another email once it's confirmed.\n\n";
            $message .= "Thank you for choosing our restaurant!";

            $this->sendEmailNotification($user['email'], $user['username'], $subject, $message);

            $_SESSION['reservation_success'] = true;
        }
    }

    public function updateReservation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $day = $_POST['day'];
            $time = $_POST['time'];
            $tableSize = $_POST['table_size'];

            // Get user information for email notification
            $stmt = $this->pdo->prepare("SELECT name, email FROM reservations WHERE id = ?");
            $stmt->execute([$id]);
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

            // Handle image upload
            $imagePath = null;
            if (isset($_FILES['receipt_image']) && $_FILES['receipt_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                $imageName = uniqid() . '_' . basename($_FILES['receipt_image']['name']);
                $uploadPath = $uploadDir . $imageName;

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                if (move_uploaded_file($_FILES['receipt_image']['tmp_name'], $uploadPath)) {
                    $imagePath = $uploadPath;
                }
            }

            // Build the update query
            if ($imagePath) {
                $sql = "UPDATE reservations SET day = ?, time = ?, table_size = ?, image_path = ? WHERE id = ?";
                $params = [$day, $time, $tableSize, $imagePath, $id];
            } else {
                $sql = "UPDATE reservations SET day = ?, time = ?, table_size = ? WHERE id = ?";
                $params = [$day, $time, $tableSize, $id];
            }

            $stmt = $this->pdo->prepare($sql);
            $success = $stmt->execute($params);

            // Send email notification if update was successful
            if ($success && $reservation) {
                $subject = "Reservation Updated";
                $message = "Hi {$reservation['name']},\n\nYour reservation has been updated successfully!\n\n";
                $message .= "Updated Reservation Details:\n";
                $message .= "Date: " . date('F d, Y', strtotime($day)) . "\n";
                $message .= "Time: " . date('h:i A', strtotime($time)) . "\n";
                $message .= "Table Size: $tableSize Pax\n";
                $message .= "Reservation ID: $id\n\n";
                
                if ($imagePath) {
                    $message .= "Receipt uploaded successfully. Your reservation is now pending approval.\n\n";
                } else {
                    $message .= "Reservation details updated. Please upload your receipt to complete the process.\n\n";
                }
                
                $message .= "Thank you!";

                $this->sendEmailNotification($reservation['email'], $reservation['name'], $subject, $message);
            }

            header('Content-Type: application/json');
            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update reservation.']);
            }
            exit;
        }
    }
}
?>

<!-- 
CREATE TABLE `cart_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `reservation_id` INT NOT NULL,
    `menu_item_id` INT NOT NULL,
    `quantity` INT NOT NULL,
    `total_price` DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (`reservation_id`) REFERENCES `reservations`(`id`),
    FOREIGN KEY (`menu_item_id`) REFERENCES `menu`(`id`)
); -->
