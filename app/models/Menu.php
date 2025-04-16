<?php

class Menu {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($foodName, $price, $imagePath, $description) {
        $sql = "INSERT INTO menu_items  (food_name, price, image_path, description) VALUES (:food_name, :price, :image_path, :description)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':food_name' => $foodName,
            ':price' => $price,
            ':image_path' => $imagePath,
            ':description' => $description
        ]);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM menu_items");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $sql = "DELETE FROM menu_items WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
