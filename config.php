<?php
// config.php - Database connection and global settings
$db_host = "192.168.1.170";
$db_user = "root";
$db_pass = "Anon@121";
$db_name = "badphp";

// Establish database connection without error checking
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Global settings
$app_name = "BadPHP";
$app_version = "1.0";
$debug_mode = true; // Insecurely exposing errors to users

// Display errors for debugging (insecure in production)
if ($debug_mode) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Session setup - Note: Using default settings that are vulnerable to session hijacking
session_start();
?>