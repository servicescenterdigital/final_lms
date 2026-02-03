<?php
// includes/config.php

define('DB_HOST', 'localhost');
define('DB_NAME', 'lms_db');
define('DB_USER', 'root');
define('DB_PASS', '');

define('SITE_NAME', 'SmartLMS');
define('BASE_URL', 'http://localhost'); // Change this in production

// Payment API Config
define('PAYMENT_USERNAME', 'your_username');
define('PAYMENT_PASSWORD', 'your_password');
define('PAYMENT_KEY', 'your_api_key');
define('PAYMENT_URL', 'https://payment.schooldream.co.rw/pay_v2');

// SMS API Config
define('SMS_API_KEY', 'your_sms_key');
define('SMS_SENDER_ID', 'SmartLMS');

session_start();

function get_db() {
    static $db;
    if (!$db) {
        try {
            $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    return $db;
}
