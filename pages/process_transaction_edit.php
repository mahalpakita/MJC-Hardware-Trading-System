<?php
include '../includes/connection.php';
include '../includes/error_handler.php';

session_start();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate required fields
        if (!isset($_POST['action'])) {
            throw new Exception("Missing action parameter.");
        }

        $transId = $_POST['trans_id'] ?? '';
        $transNo = $_POST['trans_no'] ?? '';

        if (empty($transId) || empty($transNo)) {
            throw new Exception("Transaction ID and number are required.");
        }

        if ($_POST['action'] === 'void') {
            mysqli_autocommit($db, false); // Start transaction

            // Retrieve products and quantities using TRANS_NO
            $query = "SELECT PRODUCT_ID, QTY FROM transaction_details WHERE TRANS_NO = ?";
            $stmt = mysqli_prepare($db, $query);
            mysqli_stmt_bind_param($stmt, "s", $transNo);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                $productId = $row['PRODUCT_ID'];
                $qty = $row['QTY'];

                if ($productId) {
                    // Return stock to inventory
                    $updateQuery = "UPDATE product SET QTY_STOCK = QTY_STOCK + ? WHERE PRODUCT_ID = ?";
                    $updateStmt = mysqli_prepare($db, $updateQuery);
                    mysqli_stmt_bind_param($updateStmt, "ii", $qty, $productId);
                    mysqli_stmt_execute($updateStmt);
                    mysqli_stmt_close($updateStmt);
                }
            }
            mysqli_stmt_close($stmt);

            // Delete transaction first (to prevent foreign key constraint issue)
            $deleteTransactionQuery = "DELETE FROM transaction WHERE TRANS_NO = ?";
            $deleteTransactionStmt = mysqli_prepare($db, $deleteTransactionQuery);
            mysqli_stmt_bind_param($deleteTransactionStmt, "s", $transNo);
            mysqli_stmt_execute($deleteTransactionStmt);
            mysqli_stmt_close($deleteTransactionStmt);

            // Now delete transaction details
            $deleteDetailsQuery = "DELETE FROM transaction_details WHERE TRANS_NO = ?";
            $deleteDetailsStmt = mysqli_prepare($db, $deleteDetailsQuery);
            mysqli_stmt_bind_param($deleteDetailsStmt, "s", $transNo);
            mysqli_stmt_execute($deleteDetailsStmt);
            mysqli_stmt_close($deleteDetailsStmt);

            mysqli_commit($db); // Commit transaction

            echo '<script type="text/javascript">
                    alert("Transaction voided successfully!");
                    window.location = "transaction.php";
                  </script>';
        } elseif ($_POST['action'] === 'update') {
            // Updating the transaction
            $custId = $_POST['cust_id'] ?? '';
            $amountPaid = $_POST['amount_paid'] ?? '';
            $paymentMethod = $_POST['payment_method'] ?? '';
            $refNo = $_POST['ref_no'] ?? '';

            if (empty($custId) || empty($amountPaid) || empty($paymentMethod)) {
                throw new Exception("All fields are required.");
            }

            mysqli_autocommit($db, false); // Start transaction

            $updateQuery = "UPDATE transaction SET CUST_ID = ?, CASH = ?, PAYMENT = ?, REF_NO = ? WHERE TRANS_ID = ?";
            $updateStmt = mysqli_prepare($db, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, "idssi", $custId, $amountPaid, $paymentMethod, $refNo, $transId);
            mysqli_stmt_execute($updateStmt);
            mysqli_stmt_close($updateStmt);

            mysqli_commit($db); // Commit transaction

            echo '<script type="text/javascript">
                    alert("Transaction updated successfully!");
                    window.location = "transaction.php";
                  </script>';
        } else {
            throw new Exception("Invalid action specified.");
        }
    }
} catch (Exception $e) {
    mysqli_rollback($db); // Rollback on error
    showError($e->getMessage());
}
?>
