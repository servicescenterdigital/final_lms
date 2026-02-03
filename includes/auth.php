<?php
// includes/auth.php

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function get_logged_in_user_id() {
    return $_SESSION['user_id'] ?? null;
}

function get_user_role() {
    return $_SESSION['role'] ?? null;
}

function has_role($role) {
    return get_user_role() === $role || get_user_role() === 'platform_admin';
}

function login($email, $password) {
    $db = get_db();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['org_id'] = $user['org_id'];
        return true;
    }
    return false;
}

function logout() {
    session_destroy();
}
