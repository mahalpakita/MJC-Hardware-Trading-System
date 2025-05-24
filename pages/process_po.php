<?php
session_start();
include '../includes/connection.php'; // Ensure this file contains the database connection using $db

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user is logged in
    if (!isset($_SESSION['MEMBER_ID'])) {
        echo "<script>alert('Error: User not logged in.'); window.location.href='login.php';</script>";
        exit();
    }

    $supplier_id = $_POST['supplier_id'];
    $order_date = $_POST['order_date']; // Should be in 'YYYY-MM-DDTHH:MM' format from datetime-local input
    $order_date = date('Y-m-d H:i:s', strtotime($order_date)); // Convert to MySQL DATETIME format
    
    $expected_date = $_POST['expected_date'];
    $created_by = intval($_SESSION['MEMBER_ID']); // Secure conversion to integer

    // Insert Purchase Order
    $sql = "INSERT INTO purchase_orders (SUPPLIER_ID, ORDER_DATE, EXPECTED_DELIVERY_DATE, STATUS, CREATED_BY) 
            VALUES (?, ?, ?, 'Pending', ?)";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("issi", $supplier_id, $order_date, $expected_date, $created_by);

    if ($stmt->execute()) {
        $po_id = $stmt->insert_id; // Get last inserted PO ID
        $stmt->close();

        // Insert PO Items
        if (!empty($_POST['product_id'])) {
            $product_ids = $_POST['product_id'];
            $quantities = $_POST['quantity'];
            $unit_prices = $_POST['unit_price'];
        
            $stmt = $db->prepare("INSERT INTO purchase_order_items (PO_ID, PRODUCT_ID, QUANTITY_ORDERED, UNIT_PRICE) 
                                  VALUES (?, ?, ?, ?)");
        
            for ($i = 0; $i < count($product_ids); $i++) {
                $product_id = intval($product_ids[$i]);
                $quantity = intval($quantities[$i]);
                $unit_price = floatval($unit_prices[$i]);
        
                $stmt->bind_param("iiid", $po_id, $product_id, $quantity, $unit_price); // ✅ Bind inside the loop
                $stmt->execute();
            }
        
            $stmt->close();
        }
        

        echo "<script>alert('Purchase Order Created Successfully!'); window.location.href='create_po.php';</script>";
    } else {
        echo "Error: " . $db->error;
    }
}

$db->close();
?>
