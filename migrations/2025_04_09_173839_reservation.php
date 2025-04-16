<?php

class Reservation {
    // Method to create the reservations table
    public function up($pdo) {
        $sql = "
            CREATE TABLE IF NOT EXISTS reservations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                pax INT NOT NULL,                          -- Number of people for the reservation
                table_size INT NOT NULL,                   -- Table size for the reservation
                reservation_date DATE NOT NULL,            -- Reservation date
                reservation_time TIME NOT NULL,            -- Reservation time
                name VARCHAR(255) NOT NULL,                -- Customer name
                email VARCHAR(255) NOT NULL,               -- Customer email
                image_path VARCHAR(255) DEFAULT NULL,      -- Optional image path (can be NULL)
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Automatically filled timestamp
            );
        ";
        
        try {
            $pdo->exec($sql);  // Executes the SQL query to create the table
        } catch (PDOException $e) {
            echo "Error creating table: " . $e->getMessage(); // Handling errors
        }
    }

    // Method to drop the reservations table
    public function down($pdo) {
        try {
            $pdo->exec("DROP TABLE IF EXISTS reservations"); // Drops the reservations table if it exists
        } catch (PDOException $e) {
            echo "Error dropping table: " . $e->getMessage(); // Handling errors
        }
    }
}
