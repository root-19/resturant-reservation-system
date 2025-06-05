<?php

class Reservation {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createReservation($table_size, $day, $time, $name, $email, $image_path) {
        $stmt = $this->pdo->prepare("
            INSERT INTO reservations (table_size, day, time, name, email, image_path, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$table_size, $day, $time, $name, $email, $image_path]);

        // Get the last inserted reservation ID
        $reservation_id = $this->pdo->lastInsertId();
        
        return $reservation_id;  // Return reservation ID to link cart items
    }
}  
