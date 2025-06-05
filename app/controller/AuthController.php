<?php
namespace root_dev\Controller;

require_once __DIR__ . '/../models/User.php'; 
use root_dev\Models\User;  

class AuthController {

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
    
            $user = new User();
    
            if ($user->emailExists($email)) {
                $userData = $user->getUserByEmail($email);
    
                if (password_verify($password, $userData['password'])) {
                    // Store user session
                    $_SESSION['user_id'] = $userData['id'];
                    $_SESSION['username'] = $userData['username'];
                    $_SESSION['role'] = $userData['role']; 

                    // /role directionjk
                       if ($userData['role'] === 'admin') {
                        header('Location: /admin/dashboard');  
                    } else {
                        header('Location: /dashboard');
                    }
                    exit();
                } else {
                    $error = "Invalid password.";
                    require_once __DIR__ . '/../../public/login.php';
                }
            } else {
                $error = "Email not found.";
                require_once __DIR__ . '/../../public/login.php';
            }
        } else {
            require_once __DIR__ . '/../../public/login.php';
        }
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            

            $role = isset($_POST['role']) ? $_POST['role'] : 'user'; 
            $user = new User();
    
            if ($user->emailExists($email)) {
                $error = "Email is already registered.";
                require_once __DIR__ . '/../views/register.php';
            } else {
                if ($user->register($username, $email, $password, $role)) {
                    $userData = $user->getUserByEmail($email);
                    $_SESSION['user_id'] = $userData['id'];
                    $_SESSION['username'] = $userData['username'];
                    $_SESSION['role'] = $userData['role']; 
                    
                    // Role-based redirection after registration
                    if ($userData['role'] === 'admin') {
                        header('Location: /admin/dashboard');
                    } else {
                        header('Location: /dashboard');
                    }
                    exit();
                } else {
                    $error = "Failed to register. Please try again.";
                    require_once __DIR__ . '/../../public/register.php';
                }
            }
        } else {
            require_once __DIR__ . '/../../public/register.php';
        }
    }
    

    // Handle user logout
    public function logout() {
        session_start();
        session_destroy();
        header('Location: /login');
        exit();


    }

    public function forgetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $newPassword = $_POST['new_password'];
            $retypePassword = $_POST['retype_password'];
    
            $user = new User();
    
            if (!$user->emailExists($email)) {
                $error = "Email does not exist.";
                require_once __DIR__ . '/../../public/forget-password.php';
                return;
            }
    
            if ($newPassword !== $retypePassword) {
                $error = "Passwords do not match.";
                require_once __DIR__ . '/../../public/forget-password.php';
                return;
            }
    
            if ($user->updatePasswordByEmail($email, $newPassword)) {
                $success = "Password updated successfully. You can now log in.";
            } else {
                $error = "Failed to update password.";
            }
    
            require_once __DIR__ . '/../../public/forget-password.php';
        } else {
            require_once __DIR__ . '/../../public/forget-password.php';
        }
    }
}   
