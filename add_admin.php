<?php
require 'config.php';

$admin_username = 'admin';
$admin_password = password_hash('admin123', PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
$stmt->bind_param("ss", $admin_username, $admin_password);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Admin user created successfully!";
} else {
    echo "Error creating admin user.";
}
$stmt->close();
$conn->close();
?>