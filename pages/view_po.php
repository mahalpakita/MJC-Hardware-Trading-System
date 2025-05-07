<?php
include '../includes/connection.php';
session_start();

if (!isset($_SESSION['MEMBER_ID'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['po_id'])) {
    echo "Invalid Request.";
    exit();
}

$po_id = intval($_GET['po_id']); // Ensure it's a valid number

// Fetch PO details
$sql_po = "SELECT po.PO_ID, s.COMPANY_NAME, po.ORDER_DATE, po.EXPECTED_DELIVERY_DATE, po.STATUS, 
                  CONCAT(e.FIRST_NAME, ' ', e.LAST_NAME) AS CREATED_BY_NAME
           FROM purchase_orders po
           JOIN supplier s ON po.SUPPLIER_ID = s.SUPPLIER_ID
           JOIN employee e ON po.CREATED_BY = e.EMPLOYEE_ID
           WHERE po.PO_ID = $po_id";

$result_po = mysqli_query($db, $sql_po);
$po = mysqli_fetch_assoc($result_po);

// Check if PO exists
if (!$po) {
    echo "Purchase Order not found.";
    exit();
}

// Fetch PO items
$sql_items = "SELECT p.NAME, poi.QUANTITY_ORDERED, poi.UNIT_PRICE 
              FROM purchase_order_items poi
              JOIN product p ON poi.PRODUCT_ID = p.PRODUCT_ID
              WHERE poi.PO_ID = $po_id";

$result_items = mysqli_query($db, $sql_items);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order - <?php echo $po['PO_ID']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .po-container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .footer-signature {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            text-align: center;
            border-top: 1px solid black;
            padding-top: 5px;
        }
        .print-btn {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="po-container">
    <div class="company-name">MJC HARDWARE TRADING</div>
    
    <h3 class="text-center">PURCHASE ORDER</h3>

    <table class="table table-bordered">
        <tr>
            <th>PO ID</th>
            <td><?php echo $po['PO_ID']; ?></td>
        </tr>
        <tr>
            <th>Supplier</th>
            <td><?php echo $po['COMPANY_NAME']; ?></td>
        </tr>
        <tr>
            <th>Order Date</th>
            <td><?php echo $po['ORDER_DATE']; ?></td>
        </tr>
        <tr>
            <th>Expected Delivery Date</th>
            <td><?php echo $po['EXPECTED_DELIVERY_DATE']; ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?php echo $po['STATUS']; ?></td>
        </tr>
    </table>

    <h4>Ordered Items</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_cost = 0;
            while ($row = mysqli_fetch_assoc($result_items)) { 
                $total_price = $row['QUANTITY_ORDERED'] * $row['UNIT_PRICE'];
                $total_cost += $total_price;
            ?>
            <tr>
                <td><?php echo $row['NAME']; ?></td>
                <td><?php echo $row['QUANTITY_ORDERED']; ?></td>
                <td><?php echo number_format($row['UNIT_PRICE'], 2); ?></td>
                <td><?php echo number_format($total_price, 2); ?></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Total Cost:</th>
                <th><?php echo number_format($total_cost, 2); ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="footer-signature">
        <div class="signature-box">
            Created By: <br>
            <?php echo $po['CREATED_BY_NAME']; ?>
        </div>
        <div class="signature-box">
            Checked By: <br>
        </div>
    </div>

    <div class="print-btn">
    <button onclick="window.location.href='generate_po_pdf.php?po_id=<?php echo $po_id; ?>'" class="btn btn-primary">
    Print
</button>

        <a href="manage_po.php" class="btn btn-secondary">Back</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
