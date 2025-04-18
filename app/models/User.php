<?php

namespace root_dev\Models;


require_once __DIR__ . '/../../config/database.php';
use \Database;



class User {

    // Check if email exists
    public function emailExists($email) {
        $db = Database::connect();
        $query = "SELECT COUNT(*) FROM users WHERE email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    // Get user data by email
    public function getUserByEmail($email) {
        $db = Database::connect();
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC); // Use \PDO here
    }

    // Register a new user
    public function register($username, $email, $password) {
        $db = Database::connect();
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);
        return $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);
    }

    public function updatePasswordByEmail($email, $newPassword) {
        $db = Database::connect();
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
        $query = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $db->prepare($query);
    
        return $stmt->execute([$hashedPassword, $email]);
    }
    
}
