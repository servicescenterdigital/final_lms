<?php
// public/index.php

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';

// Simple routing logic
$parts = explode('/', $url);
$page = $parts[0] ?: 'home';

// Handle API requests
if ($page === 'api') {
    require_once __DIR__ . '/../api/router.php';
    exit;
}

// Handle Authentication Routes
if (in_array($page, ['login', 'register', 'logout'])) {
    require_once __DIR__ . '/../includes/auth_controller.php';
    exit;
}

// Check if it's a portal route
$portals = ['student', 'instructor', 'org-admin', 'platform-admin'];
if (in_array($page, $portals)) {
    // Check auth
    if (!is_logged_in()) {
        header('Location: /login');
        exit;
    }
    
    // Check role
    $role = str_replace('-', '_', $page);
    if (!has_role($role)) {
        header('Location: /');
        exit;
    }

    require_once __DIR__ . "/../templates/{$role}/index.php";
    exit;
}

// Public pages
switch ($page) {
    case 'home':
        require_once __DIR__ . '/../templates/public/home.php';
        break;
    case 'courses':
        require_once __DIR__ . '/../templates/public/courses.php';
        break;
    case 'course-details':
        require_once __DIR__ . '/../templates/public/course-details.php';
        break;
    case 'verify-certificate':
        require_once __DIR__ . '/../templates/public/verify-certificate.php';
        break;
    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
