<?php

namespace App\Controllers;

use App\Models\User;

class AuthController {

    public function login() {
        // Render login view
        require ROOT_PATH . '/src/Views/auth/login.php';
    }

    public function register() {
        // Render register view
        require ROOT_PATH . '/src/Views/auth/register.php';
    }

    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = new User();
            if ($user->findByEmail($email)) {
                if (password_verify($password, $user->password)) {
                    // Start Session
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['user_name'] = $user->name;
                    $_SESSION['user_role'] = $user->role;
                    $_SESSION['user_email'] = $user->email;
                    $_SESSION['last_activity'] = time();

                    // Redirect based on role
                    header("Location: /dashboard");
                    exit;
                } else {
                    $error = "Invalid password.";
                    require ROOT_PATH . '/src/Views/auth/login.php';
                }
            } else {
                $error = "User not found.";
                require ROOT_PATH . '/src/Views/auth/login.php';
            }
        }
    }

    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'student'; // Default to student

            // Basic Validation
            if (empty($name) || empty($email) || empty($password)) {
                $error = "All fields are required.";
                require ROOT_PATH . '/src/Views/auth/register.php';
                return;
            }

            $user = new User();
            
            // Check if email exists
            if ($user->findByEmail($email)) {
                $error = "Email already registered.";
                require ROOT_PATH . '/src/Views/auth/register.php';
                return;
            }

            // Create User
            $user->name = $name;
            $user->email = $email;
            $user->password = $password;
            $user->role = $role;

            if ($user->create()) {
                // Auto login or redirect to login
                header("Location: /login?registered=true");
                exit;
            } else {
                $error = "Registration failed. Try again.";
                require ROOT_PATH . '/src/Views/auth/register.php';
            }
        }
    }

    public function logout() {
        session_destroy();
        header("Location: /login");
        exit;
    }
}
