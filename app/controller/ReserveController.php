<?php


require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../../config/database.php';

class ReservationController {
    private $reservationModel;

    public function __construct($pdo) {
        $this->reservationModel = new Reservation($pdo);
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

            $this->reservationModel->createReservation($tableSize, $day, $time, $name, $email, $imagePath);

            header("Location: menu.php");
        
            exit;
        }
    }
}