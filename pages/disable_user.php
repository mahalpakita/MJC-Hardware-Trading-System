<?php 
require_once('redirect.php');
allowOnlyAdmin();
?>

<?php
include '../includes/connection.php';

if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);

    // Update user status to 'Disable'
    $query = "UPDATE users SET STATUS = 'Disable' WHERE ID = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "i", $userId);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
                alert('User has been disabled.');
                window.location = 'user.php';
              </script>";
    } else {
        echo "<script>
                alert('Error disabling user.');
                window.history.back();
              </script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($db);
}
?>
