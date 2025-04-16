<?php



class MenuTable {
    public function up($pdo) {
        $sql = "
            CREATE TABLE IF NOT EXISTS menu_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                food_name VARCHAR(255) NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                image_path VARCHAR(255) NOT NULL,
                description VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ";
        $pdo->exec($sql);
        echo "menu_items table created successfully.";
    }

    public function down($pdo) {
        $sql = "DROP TABLE IF EXISTS menu_items";
        $pdo->exec($sql);
        echo "menu_items table dropped successfully.";
    }
}
