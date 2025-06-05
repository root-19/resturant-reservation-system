<?php
session_start();

use root_dev\Controller\AuthController;

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/controller/AuthController.php';
require_once __DIR__ . '/../app/controller/ReserveController.php';

// Define routes ass handler_type, action, is_protected, required_role 
$routes = [
    '/' => ['redirect', 'login', false],
    '/login' => [AuthController::class, 'login', false],
    '/logout' => [AuthController::class, 'logout', true],
    '/register' => [AuthController::class, 'register', false],
  '/forget-password' => [AuthController::class, 'forgetPassword', false],


    
    // Public pages
    '/menu' => ['view', 'menu', false],
    '/reservation' => ['view', 'reservation', true, 'user'],


    // Routes accessible to 'user'
    '/dashboard' => ['view', 'dashboard', true, 'user'],
    '/create-menu' => ['view', 'create-menu', true, 'user'],
    '/menu-list' => ['view', 'menu-list', true, 'user'],
    '/about' => ['view', 'about', true, 'user'],
    '/contact' => ['view', 'contact', true, 'user'],
    '/checkout' => ['view', 'checkout', true, 'user'],


    // Routes accessible to 'admin'
    '/admin/dashboard' => ['view', 'admin/dashboard', true, 'admin'],
    '/admin/create-menu' => ['view', 'admin/create-menu', true, 'admin'],
    '/admin/menu-list' => ['view', 'admin/menu-list', true, 'admin'],
    '/admin/reservation-list' => ['view', 'admin/reservation-list', true, 'admin'],
    '/admin/resturant-map' => ['view', 'admin/resturant-map', true, 'admin'],
    '/admin/done-reservation' => ['view', 'admin/done-reservation', true, 'admin'],
    // '/admin/handle-reservation' => ['view', 'admin/handle-reservation', true, 'admin'],

    '/reservation/update' => ['ReservationController', 'updateReservation', true, 'user'],
    '/reservation/edit_reservation_ajax' => ['view', '../reservation/edit_reservation_ajax', true, 'user'],

];

// Get the current path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Route handling logic
if (isset($routes[$uri])) {
    [$handler, $action, $isProtected, $requiredRole] = array_pad($routes[$uri], 4, null);

    // Middleware: Check login
    if ($isProtected) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }

        // Middleware: Check role
        if ($requiredRole && (!isset($_SESSION['role']) || $_SESSION['role'] !== $requiredRole)) {
            http_response_code(403);
            echo "403 Forbidden: You do not have access to this page.";
            exit();
        }
    }

    // Route type logic
    if ($handler === 'redirect') {
        header("Location: ./$action");
        exit();
    } elseif ($handler === 'view') {

        require_once __DIR__ . "/../app/views/$action.php";
    } else {
        if ($handler === 'ReservationController') {
            require_once __DIR__ . '/../config/database.php';
            $pdo = Database::connect();
            $controller = new ReservationController($pdo);
        } else {
            $controller = new $handler();
        }
        $controller->$action();
    }
} else {
    // Fallback for unknown routes
    http_response_code(404);
    echo "404 Not Found: Route [$uri]";
}
