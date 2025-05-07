<?php
include '../includes/connection.php';

if (isset($_POST['transNo'])) {
    $transNo = trim($_POST['transNo']); // Trim whitespace

    // Check if the transaction number exists
    $checkQuery = "SELECT TRANS_NO FROM transaction WHERE TRANS_NO = ?";
    $stmt = mysqli_prepare($db, $checkQuery);
    mysqli_stmt_bind_param($stmt, "s", $transNo);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo json_encode(["status" => "duplicate"]);
    } else {
        echo json_encode(["status" => "unique"]);
    }

    mysqli_stmt_close($stmt);
}
?>
