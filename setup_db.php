<?php
require_once __DIR__ . '/includes/config.php';

try {
    // Connect to MySQL server (no DB selected yet)
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create Database
    echo "Creating database if not exists...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    echo "Database created/verified.\n";
    
    // Select Database
    $pdo->exec("USE " . DB_NAME);
    
    // Read schema.sql
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    
    // Execute Schema
    echo "Importing schema...\n";
    $pdo->exec($sql);
    echo "Schema imported successfully.\n";
    
} catch (PDOException $e) {
    die("DB Setup Failed: " . $e->getMessage() . "\n");
}
