<?php



class Reservation {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createReservation($table_size, $day, $time, $name, $email, $image_path) {
        $stmt = $this->pdo->prepare("
            INSERT INTO reservations (table_size, day, time, name, email, image_path)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$table_size, $day, $time, $name, $email, $image_path]);
    }

    public function getAvailableTables() {
        // Optional: Add logic if you want to check availability
    }
}

