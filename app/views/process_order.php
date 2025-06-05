<?php
require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = Database::connect();
        
        // Start transaction
        $pdo->beginTransaction();
        
        // Insert order
        $stmt = $pdo->prepare("INSERT INTO orders (customer_name, phone, email, address, payment_method, total_amount, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())");
        $stmt->execute([
            $_POST['customer_name'],
            $_POST['phone'],
            $_POST['email'],
            $_POST['address'],
            $_POST['payment_method'],
            array_reduce($_SESSION['cart'], function($sum, $item) {
                return $sum + ($item['price'] * $item['quantity']);
            }, 0)
        ]);
        
        $orderId = $pdo->lastInsertId();
        
        // Insert order items
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, menu_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($_SESSION['cart'] as $item) {
            $stmt->execute([
                $orderId,
                $item['id'],
                $item['quantity'],
                $item['price']
            ]);
        }
        
        // Commit transaction
        $pdo->commit();
        
        // Clear cart
        $_SESSION['cart'] = [];
        
        // Redirect to success page
        header('Location: order_success.php?order_id=' . $orderId);
        exit;
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        $_SESSION['error'] = "An error occurred while processing your order. Please try again.";
        header('Location: checkout');
        exit;
    }
} else {
    header('Location: menu');
    exit;
} 