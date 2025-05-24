<?php 
require_once('redirect.php');
allowOnlyAdmin();
?>

<?php
include '../includes/connection.php';

if (!isset($_GET['id'])) {
    die('Invalid request.');
}

$id = intval($_GET['id']); // Get the user ID safely

// Fetch the current status from the database
$query = "SELECT STATUS FROM users WHERE ID = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $currentStatus = $row['STATUS']; // Get current status from DB
} else {
    die('User not found.');
}

// Toggle the status correctly
$newStatus = ($currentStatus === 'Enable') ? 'Disable' : 'Enable';

// Update the user's status in the database
$updateQuery = "UPDATE users SET STATUS = ? WHERE ID = ?";
$updateStmt = mysqli_prepare($db, $updateQuery);
mysqli_stmt_bind_param($updateStmt, "si", $newStatus, $id);

if (mysqli_stmt_execute($updateStmt)) {
    echo "<script>
            alert('User status updated successfully to $newStatus.');
            window.location = 'user.php';
          </script>";
} else {
    echo "<script>
            alert('Error updating status.');
            window.history.back();
          </script>";
}

// Close statements
mysqli_stmt_close($stmt);
mysqli_stmt_close($updateStmt);
?>
