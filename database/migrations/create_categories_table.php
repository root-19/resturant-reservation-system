<?php
require_once __DIR__ . '/../../config/database.php';

try {
    $pdo = Database::connect();
    
    // Create categories table
    $sql = "CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    
    // Insert some default categories
    $defaultCategories = [
        ['name' => 'Appetizers', 'description' => 'Starters and small dishes'],
        ['name' => 'Main Course', 'description' => 'Main dishes and entrees'],
        ['name' => 'Desserts', 'description' => 'Sweet treats and desserts'],
        ['name' => 'Beverages', 'description' => 'Drinks and refreshments'],
        ['name' => 'Specials', 'description' => 'Chef\'s special dishes']
    ];
    
    $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (:name, :description)");
    
    foreach ($defaultCategories as $category) {
        $stmt->execute([
            ':name' => $category['name'],
            ':description' => $category['description']
        ]);
    }
    
    echo "Categories table created successfully with default categories!";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
} 