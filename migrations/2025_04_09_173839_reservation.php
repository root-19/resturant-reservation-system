<?php

class Reservation {
    public function up($pdo) {
        $sql = "
            CREATE TABLE IF NOT EXISTS reservations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                pax INT NOT NULL,
                table_number INT NOT NULL,
                reservation_date DATE NOT NULL,
                reservation_time TIME NOT NULL,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                image_path VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ";
        $pdo->exec($sql);
    }

    public function down($pdo) {
        $pdo->exec("DROP TABLE IF EXISTS reservations");
    }
}
