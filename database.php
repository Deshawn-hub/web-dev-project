<?php
$host = 'localhost';
$username = 'root';  // Your MySQL username
$password = '';      // Your MySQL password
$dbname = 'dolphin_crm';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to ensure proper handling of special characters
$conn->set_charset("utf8mb4");

// Optional: Set timezone if needed
date_default_timezone_set('America/Jamaica');  // Adjust timezone as needed

// Function to handle database errors
function handleDatabaseError($message) {
    error_log("Database Error: " . $message);
    // You can customize the error handling as needed
    die("An error occurred. Please try again later.");
}

// Function to safely escape user input
function sanitizeInput($conn, $input) {
    if (is_array($input)) {
        return array_map(function($item) use ($conn) {
            return mysqli_real_escape_string($conn, trim($item));
        }, $input);
    }
    return mysqli_real_escape_string($conn, trim($input));
}

// Optional: Enable error reporting for development
if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Optional: Set session parameters for security
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
?>