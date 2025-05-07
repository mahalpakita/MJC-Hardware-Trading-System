<?php
include '../includes/connection.php'; // Include database connection

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$id = $_GET['id'];
$new_password = "admin123"; // Default password
$hashed_password = sha1($new_password);// Securely hash the password

// Update password in the database
$query = "UPDATE users SET PASSWORD = ? WHERE ID = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("si", $hashed_password, $id);

if ($stmt->execute()) {
    echo "<script>alert('Password has been reset'); window.location.href='user.php';</script>";
} else {
    echo "<script>alert('Error resetting password.'); window.location.href='user.php';</script>";
}
?>
