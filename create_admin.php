<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'dolphin_crm';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete existing admin if exists
$conn->query("DELETE FROM Users WHERE email = 'admin@dolphin.com'");

// Create new admin
$admin_password = 'Password123';
$hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

$sql = "INSERT INTO Users (firstname, lastname, email, password, role) 
        VALUES ('Admin', 'User', 'admin@dolphin.com', '$hashed_password', 'Admin')";

if ($conn->query($sql)) {
    echo "Admin created successfully!<br>";
    echo "Email: admin@dolphin.com<br>";
    echo "Password: Password123";
} else {
    echo "Error: " . $conn->error;
}
?>