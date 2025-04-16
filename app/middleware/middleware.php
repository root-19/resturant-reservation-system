<?php
namespace root_dev\Middleware;

class AuthMiddleware
{
    public static function check()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
    }

    public static function checkAdmin()
    {
        session_start();

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /dashboard.php'); // redirect non-admins
            exit();
        }
    }

    public static function checkUser()
    {
        session_start();

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
            header('Location: /admin/dashboard.php'); 
            exit();
        }
    }
}
