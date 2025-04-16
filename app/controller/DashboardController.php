<?php
namespace root_dev\Controller;

class DashboardController {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
        require_once __DIR__ . '/../views/dashboard.php';
    }
}