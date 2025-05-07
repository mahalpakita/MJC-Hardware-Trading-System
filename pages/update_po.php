<?php
include '../includes/connection.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $po_id = intval($_POST['po_id']);
    $expected_date = $_POST['expected_date'];
    $status = $_POST['status'];

    // Start a transaction to ensure data consistency
    mysqli_begin_transaction($db);

    try {
        // Update the purchase order status
        $sql_update_po = "UPDATE purchase_orders SET EXPECTED_DELIVERY_DATE = ?, STATUS = ? WHERE PO_ID = ?";
        $stmt = mysqli_prepare($db, $sql_update_po);
        mysqli_stmt_bind_param($stmt, "ssi", $expected_date, $status, $po_id);
        mysqli_stmt_execute($stmt);
        
        // If status is 'Completed', update product stock
        if ($status === "Completed") {
            $sql_get_items = "SELECT PRODUCT_ID, QUANTITY_ORDERED FROM purchase_order_items WHERE PO_ID = ?";
            $stmt_items = mysqli_prepare($db, $sql_get_items);
            mysqli_stmt_bind_param($stmt_items, "i", $po_id);
            mysqli_stmt_execute($stmt_items);
            $result_items = mysqli_stmt_get_result($stmt_items);

            while ($row = mysqli_fetch_assoc($result_items)) {
                $product_id = $row['PRODUCT_ID'];
                $quantity_ordered = $row['QUANTITY_ORDERED'];

                // Update the product quantity
                $sql_update_stock = "UPDATE product SET QTY_STOCK = QTY_STOCK + ? WHERE PRODUCT_ID = ?";
                $stmt_update_stock = mysqli_prepare($db, $sql_update_stock);
                mysqli_stmt_bind_param($stmt_update_stock, "ii", $quantity_ordered, $product_id);
                mysqli_stmt_execute($stmt_update_stock);
            }
        }

        // Commit the transaction
        mysqli_commit($db);

        echo "<script>alert('Purchase Order Updated Successfully!'); window.location.href='manage_po.php';</script>";
    } catch (Exception $e) {
        // Rollback in case of an error
        mysqli_rollback($db);
        echo "Error: " . $e->getMessage();
    }
}
?>
    