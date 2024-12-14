<?php 
$host = 'localhost';
$username = 'lab5_user'; 
$password = 'password123';
$dbname = 'dolphin_crm';




$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
$data = $conn->query(" SELECT password FROM password_db WHERE email = ?");

$email;
?>
