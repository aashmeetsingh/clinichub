<?php
require_once "config/database.php";
$email = "admin@aashmeetsingh.xyz";
$password = password_hash("Admin@123", PASSWORD_DEFAULT);
$stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
if ($stmt->get_result()->num_rows) {
    echo "Admin already exists.";
} else {
    $stmt = $conn->prepare("INSERT INTO users(name,email,password,role) VALUES('MediBook Admin',?,?, 'ADMIN')");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    echo "Admin created.";
}
?>