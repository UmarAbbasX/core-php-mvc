<?php

namespace App\Controllers;

use Core\Model;

class AuthController
{
    public function login(): void
    {
        require base_path('app/Views/auth/login.php');
    }

    public function loginPost(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // CSRF check
        if (empty($_POST['_csrf']) || $_POST['_csrf'] !== ($_SESSION['_csrf'] ?? '')) {
            $_SESSION['error'] = 'Invalid CSRF token.';
            redirect('/login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $_SESSION['error'] = 'Email and password are required.';
            redirect('/login');
        }

        $user = Model::fetch("SELECT * FROM users WHERE email = :email", ['email' => $email]);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;

            // Regenerate session ID on login to prevent fixation
            session_regenerate_id(true);

            redirect('/dashboard');
        }

        $_SESSION['error'] = 'Invalid credentials.';
        redirect('/login');
    }

    public function register(): void
    {
        require base_path('app/Views/auth/register.php');
    }

    public function registerPost(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // CSRF check
        if (empty($_POST['_csrf']) || $_POST['_csrf'] !== ($_SESSION['_csrf'] ?? '')) {
            $_SESSION['error'] = 'Invalid CSRF token.';
            redirect('/register');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $passwordRaw = $_POST['password'] ?? '';

        if (!$name || !$email || !$passwordRaw) {
            $_SESSION['error'] = 'All fields are required.';
            redirect('/register');
        }

        // Basic email validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Invalid email address.';
            redirect('/register');
        }

        // Check if email exists
        $existing = Model::fetch("SELECT id FROM users WHERE email = :email", ['email' => $email]);
        if ($existing) {
            $_SESSION['error'] = 'Email already registered.';
            redirect('/register');
        }

        $password = password_hash($passwordRaw, PASSWORD_DEFAULT);

        Model::insert('users', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $_SESSION['success'] = 'Registration successful. Please log in.';
        redirect('/login');
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        redirect('/login');
    }
}
