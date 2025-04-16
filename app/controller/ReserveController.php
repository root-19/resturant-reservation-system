<?php

require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../../config/database.php';

class ReservationController {
    private $reservationModel;
    private $pdo;

    public function __construct($pdo) {
        $this->reservationModel = new Reservation($pdo);
        $this->pdo = $pdo;
    }

    public function handleReservation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tableSize = $_POST['table_size'];
            $day = $_POST['day'];
            $time = $_POST['time'];
            $name = $_POST['name'];
            $email = $_POST['email'];

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

            // Create the reservation and get its ID
            $reservationId = $this->reservationModel->createReservation($tableSize, $day, $time, $name, $email, $imagePath);

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

            $_SESSION['reservation_success'] = true;
            // header("Location: /logout");
            // exit;
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
