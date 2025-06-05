<?php

class Menu {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($foodName, $price, $imagePath, $description, $categoryId) {
        $sql = "INSERT INTO menu_items (food_name, price, image_path, description, category_id) 
                VALUES (:food_name, :price, :image_path, :description, :category_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':food_name' => $foodName,
            ':price' => $price,
            ':image_path' => $imagePath,
            ':description' => $description,
            ':category_id' => $categoryId
        ]);
    }

    public function getAll() {
        $sql = "SELECT m.*, c.name as category_name 
                FROM menu_items m 
                LEFT JOIN categories c ON m.category_id = c.id 
                ORDER BY c.name, m.food_name";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCategory($categoryId) {
        $sql = "SELECT m.*, c.name as category_name 
                FROM menu_items m 
                LEFT JOIN categories c ON m.category_id = c.id 
                WHERE m.category_id = :category_id 
                ORDER BY m.food_name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':category_id' => $categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategories() {
        $sql = "SELECT * FROM categories ORDER BY name";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $sql = "DELETE FROM menu_items WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
