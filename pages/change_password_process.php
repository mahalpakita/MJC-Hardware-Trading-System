<?php 
require_once('redirect.php');
allowOnlyAdmin();
?>


<?php
include '../includes/connection.php';


if (!isset($_SESSION['MEMBER_ID'])) {
    die("<script>alert('Unauthorized access!'); window.location = 'login.php';</script>");
}

$admin_id = $_SESSION['MEMBER_ID'];

// Validate that the user is an admin
$query_admin = "SELECT TYPE_ID FROM users WHERE ID = ?";
$stmt_admin = mysqli_prepare($db, $query_admin);
mysqli_stmt_bind_param($stmt_admin, "i", $admin_id);
mysqli_stmt_execute($stmt_admin);
$result_admin = mysqli_stmt_get_result($stmt_admin);
$row_admin = mysqli_fetch_assoc($result_admin);

if (!$row_admin || $row_admin['TYPE_ID'] != 1) { // 1 = Admin
    die("<script>alert('Unauthorized action!'); window.history.back();</script>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['user_id']) || empty($_POST['user_id'])) {
        die("<script>alert('No user selected!'); window.history.back();</script>");
    }

    $user_id = intval($_POST['user_id']);
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Fetch current stored password hash
    $query = "SELECT PASSWORD FROM users WHERE ID = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if (!$user) {
        die("<script>alert('User not found!'); window.history.back();</script>");
    }

    // Check if the current password is correct
    if (sha1($current_password) !== $user['PASSWORD']) {
        die("<script>alert('Incorrect current password!'); window.history.back();</script>");
    }
    // Ensure new passwords match
    if ($new_password !== $confirm_password) {
        die("<script>alert('New passwords do not match!'); window.history.back();</script>");
    }

    // Hash the new password
    $hashed_password = sha1($new_password);
      // Encrypt new password using SHA1
     


    // Update password in database
    $update_query = "UPDATE users SET PASSWORD = ? WHERE ID = ?";
    $stmt_update = mysqli_prepare($db, $update_query);
    mysqli_stmt_bind_param($stmt_update, "si", $hashed_password, $user_id);

    if (mysqli_stmt_execute($stmt_update)) {
        echo "<script>alert('Password updated successfully!'); window.location = 'user.php';</script>";
    } else {
        die("<script>alert('Error updating password.'); window.history.back();</script>");
    }
}
?>
