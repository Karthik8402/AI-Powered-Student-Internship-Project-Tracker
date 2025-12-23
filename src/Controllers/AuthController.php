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
                    // Regenerate session ID for security
                    session_regenerate_id(true);
                    
                    // Start Session
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['user_name'] = $user->name;
                    $_SESSION['user_role'] = $user->role;
                    $_SESSION['user_email'] = $user->email;
                    $_SESSION['last_activity'] = time();
                    
                    // Save session explicitly
                    session_write_close();

                    // Redirect using JavaScript as fallback for shared hosting
                    $dashboardUrl = BASE_URL . "/dashboard";
                    echo "<script>window.location.href = '{$dashboardUrl}';</script>";
                    echo "<noscript><meta http-equiv='refresh' content='0;url={$dashboardUrl}'></noscript>";
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
                header("Location: " . BASE_URL . "/login?registered=true");
                exit;
            } else {
                $error = "Registration failed. Try again.";
                require ROOT_PATH . '/src/Views/auth/register.php';
            }
        }
    }

    public function logout() {
        session_destroy();
        header("Location: " . BASE_URL . "/login");
        exit;
    }

    public function changePassword() {
        // Render change password view
        require ROOT_PATH . '/src/Views/auth/change-password.php';
    }

    public function handleChangePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validation
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $error = "All fields are required.";
                require ROOT_PATH . '/src/Views/auth/change-password.php';
                return;
            }

            if (strlen($newPassword) < 6) {
                $error = "New password must be at least 6 characters.";
                require ROOT_PATH . '/src/Views/auth/change-password.php';
                return;
            }

            if ($newPassword !== $confirmPassword) {
                $error = "New passwords do not match.";
                require ROOT_PATH . '/src/Views/auth/change-password.php';
                return;
            }

            $user = new User();
            
            // Get current user
            if (!$user->findById($_SESSION['user_id'])) {
                $error = "User not found.";
                require ROOT_PATH . '/src/Views/auth/change-password.php';
                return;
            }

            // Verify current password
            if (!password_verify($currentPassword, $user->password)) {
                $error = "Current password is incorrect.";
                require ROOT_PATH . '/src/Views/auth/change-password.php';
                return;
            }

            // Update password
            if ($user->updatePassword($_SESSION['user_id'], $newPassword)) {
                $success = "Password changed successfully!";
                require ROOT_PATH . '/src/Views/auth/change-password.php';
            } else {
                $error = "Failed to update password. Please try again.";
                require ROOT_PATH . '/src/Views/auth/change-password.php';
            }
        }
    }
}
