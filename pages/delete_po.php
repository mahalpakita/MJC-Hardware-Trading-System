<?php 
require_once('redirect.php');
allowOnlyAdmin();
?>


<?php
include '../includes/connection.php';

if (isset($_GET['po_id'])) {
    $po_id = intval($_GET['po_id']);
    
    // Delete PO items first to prevent foreign key issues
    mysqli_query($db, "DELETE FROM purchase_order_items WHERE PO_ID = $po_id");
    
    // Delete the PO
    $sql = "DELETE FROM purchase_orders WHERE PO_ID = $po_id";
    
    if (mysqli_query($db, $sql)) {
        echo "<script>alert('Purchase Order Deleted Successfully!'); window.location.href='manage_po.php';</script>";
    } else {
        echo "Error: " . mysqli_error($db);
    }
}
?>
