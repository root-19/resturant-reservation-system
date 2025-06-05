<?php
require_once __DIR__ . '/../config/database.php';

try {
    $pdo = Database::connect();
    
    // Create categories table
    $pdo->exec("CREATE TABLE IF NOT EXISTS categories (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Check if category_id column exists
    $result = $pdo->query("SHOW COLUMNS FROM menu_items LIKE 'category_id'");
    if ($result->rowCount() == 0) {
        // Add category_id to menu_items table
        $pdo->exec("ALTER TABLE menu_items ADD COLUMN category_id INT");
        
        // Add foreign key
        $pdo->exec("ALTER TABLE menu_items 
                    ADD CONSTRAINT fk_category 
                    FOREIGN KEY (category_id) REFERENCES categories(id)");
    }

    // Insert default categories if they don't exist
    $categories = ['Appetizers', 'Main Course', 'Desserts', 'Beverages', 'Specialties'];
    $stmt = $pdo->prepare("INSERT IGNORE INTO categories (name) VALUES (?)");
    
    foreach ($categories as $category) {
        $stmt->execute([$category]);
    }

    echo "Database updated successfully!\n";
} catch (PDOException $e) {
    die("Error updating database: " . $e->getMessage() . "\n");
} 