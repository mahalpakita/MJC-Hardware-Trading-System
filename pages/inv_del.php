<?php 
require_once('redirect.php');
allowOnlyAdmin();
?>

<?php
include '../includes/connection.php';

// Check if 'action' is set to 'delete' and 'id' is provided
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $productId = intval($_GET['id']);
    
    // Step 1: Fetch product details before deleting
    $fetchQuery = "SELECT PRODUCT_CODE, NAME, DESCRIPTION, QTY_STOCK, PRICE, SUPPLIER_ID, BRANCH_ID FROM product WHERE PRODUCT_ID = ?";
    $stmt = mysqli_prepare($db, $fetchQuery);
    mysqli_stmt_bind_param($stmt, "i", $productId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Store product details for history logging
        $pc = $row['PRODUCT_CODE'];
        $name = $row['NAME'];
        $desc = $row['DESCRIPTION'];
        $qty = $row['QTY_STOCK'];
        $pr = $row['PRICE'];
        $supp = $row['SUPPLIER_ID'];
        $branch = $row['BRANCH_ID'];
        
        // Step 2: Log product details into history table BEFORE deletion
        $user = 'Admin'; // Change this to the logged-in user

        $historyQuery = "INSERT INTO product_history 
            (PRODUCT_ID, PRODUCT_CODE, NAME, DESCRIPTION, QTY_STOCK, PRICE, SUPPLIER_ID, BRANCH_ID, ACTION_TYPE, USER, ACTION_DATE)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Deleted', ?, NOW())";

        $historyStmt = mysqli_prepare($db, $historyQuery);
        mysqli_stmt_bind_param($historyStmt, "isssdisis", $productId, $pc, $name, $desc, $qty, $pr, $supp, $branch, $user);
        mysqli_stmt_execute($historyStmt);
        mysqli_stmt_close($historyStmt);
    }
    mysqli_stmt_close($stmt);

    // Step 3: Delete the product from the database
    $deleteQuery = "DELETE FROM product WHERE PRODUCT_ID = ?";
    $deleteStmt = mysqli_prepare($db, $deleteQuery);
    mysqli_stmt_bind_param($deleteStmt, "i", $productId);
    $deleteResult = mysqli_stmt_execute($deleteStmt);
    mysqli_stmt_close($deleteStmt);

    // Redirect based on deletion success
    if ($deleteResult) {
        echo '<script type="text/javascript">
                alert("Product deleted successfully.");
                window.location = "inventory.php";
              </script>';
    } else {
        echo '<script type="text/javascript">
                alert("Error deleting product. Please try again.");
                window.location = "inventory.php";
              </script>';
    }
}
?>
