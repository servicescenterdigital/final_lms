<?php
// includes/auth_controller.php

if ($page === 'login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (login($email, $password)) {
            $role = get_user_role();
            $target = '/' . str_replace('_', '-', $role);
            redirect($target);
        } else {
            $error = "Invalid credentials";
        }
    }
    require_once __DIR__ . '/../templates/public/login.php';
} elseif ($page === 'register') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = sanitize($_POST['name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
        $role = 'student'; // Default role
        
        $db = get_db();
        try {
            $stmt = $db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $password, $role]);
            redirect('/login');
        } catch (PDOException $e) {
            $error = "Registration failed: " . $e->getMessage();
        }
    }
    require_once __DIR__ . '/../templates/public/register.php';
} elseif ($page === 'logout') {
    logout();
    redirect('/login');
}
