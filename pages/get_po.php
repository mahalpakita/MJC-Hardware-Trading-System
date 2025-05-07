<?php
include '../includes/connection.php';

if (isset($_GET['po_id'])) {
    $po_id = intval($_GET['po_id']);
    $query = "SELECT * FROM purchase_orders WHERE PO_ID = $po_id";
    $result = mysqli_query($db, $query);
    $data = mysqli_fetch_assoc($result);
    echo json_encode($data);
}
?>
