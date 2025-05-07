<?php
require_once('redirect.php');
allowOnlyCashier();
include '../includes/connection.php';
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validate required fields
$requiredFields = ['transNo', 'customer', 'subtotal', 'cash'];
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field])) {
        die("<script>alert('Error: Missing required fields!'); window.history.back();</script>");
    }
}

// Retrieve and sanitize form data
$date = $_POST['date'] ?? date('Y-m-d');
$customer = trim($_POST['customer']);
$subtotal = str_replace(',', '', $_POST['subtotal']);
$lessvat = str_replace(',', '', $_POST['lessvat'] ?? 0);
$netvat = str_replace(',', '', $_POST['netvat'] ?? 0);
$addvat = str_replace(',', '', $_POST['addvat'] ?? 0);
$cash = str_replace(',', '', $_POST['cash']);
$discount = str_replace(',', '', $_POST['discount'] ?? 0);
$emp = trim($_POST['employee'] ?? 'Unknown');
$rol = trim($_POST['role'] ?? 'Unknown');
$transNo = trim($_POST['transNo']);

// Validate Transaction Number
if (empty($transNo)) {
    die("<script>alert('Error: Transaction Number is required!'); window.history.back();</script>");
}

// Process Discount
$discount = isset($_POST['discount']) ? trim($_POST['discount']) : '0';
if (strpos($discount, '%') !== false) {
    $discount = bcdiv(str_replace('%', '', $discount) * $subtotal / 100, '1', 2);
} else {
    $discount = bcdiv($discount, '1', 2);
}

// Compute Grand Total after Discount
$total = bcsub($subtotal, $discount, 2);
if ($total < 0) {
    $total = '0.00';
}

// Validate Payment Method & Reference No.
$payment = $_POST['payment'] ?? 'cash';
$referenceNo = ($payment === 'online' && !empty($_POST['referenceNo'])) ? $_POST['referenceNo'] : 'N/A';

// Generate Unique Transaction ID
$today = date("mdGis");

// Check if 'product_id' is provided
if (!isset($_POST['product_id']) || !is_array($_POST['product_id'])) {
    die("<script>alert('Error: No products selected!'); window.history.back();</script>");
}
$countID = count($_POST['product_id']);

// Check for Duplicate Transaction Number
$checkQuery = "SELECT TRANS_NO FROM transaction WHERE TRANS_NO = ?";
$stmt = mysqli_prepare($db, $checkQuery);
mysqli_stmt_bind_param($stmt, "s", $transNo);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if (mysqli_stmt_num_rows($stmt) > 0) {
    echo "<script>alert('Duplicate transaction number found! Please try again.'); window.history.back();</script>";
    exit();
}
mysqli_stmt_close($stmt);

// Insert Transaction Details
if ($_GET['action'] === 'add') {
    for ($i = 0; $i < $countID; $i++) {
        $productId = intval($_POST['product_id'][$i]);
        $quantity = bcdiv($_POST['quantity'][$i], '1', 2);
        $price = bcdiv($_POST['price'][$i], '1', 2);

        $productQuery = "SELECT NAME, DESCRIPTION FROM product WHERE PRODUCT_ID = ?";
        $stmt = mysqli_prepare($db, $productQuery);
        mysqli_stmt_bind_param($stmt, "i", $productId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $productData = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!$productData) {
            die("Error: Product ID '$productId' not found in the database.");
        }

        $productName = $productData['NAME'];
        $description = $productData['DESCRIPTION'];

        $insertQuery = "INSERT INTO transaction_details 
                        (TRANS_D_ID, PRODUCTS, DESCRIPTION, QTY, PRICE, EMPLOYEE, ROLE, PAYMENT, TRANS_NO, REF_NO, PRODUCT_ID) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($db, $insertQuery);
        mysqli_stmt_bind_param($stmt, "sssdssssssi", 
            $today, $productName, $description, $quantity, $price, 
            $emp, $rol, $payment, $transNo, $referenceNo, $productId
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Insert Transaction with Discount
$query111 = "INSERT INTO transaction 
             (TRANS_NO, CUST_ID, NUMOFITEMS, SUBTOTAL, LESSVAT, NETVAT, ADDVAT, GRANDTOTAL, CASH, DATE, TRANS_D_ID, PAYMENT, REF_NO, DISCOUNT) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($db, $query111);
mysqli_stmt_bind_param($stmt, "ssssssssssssss", 
    $transNo, $customer, $countID, $subtotal, $lessvat, 
    $netvat, $addvat, $total, $cash, $date, 
    $today, $payment, $referenceNo, $discount
);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// Delete Items in Cart After Successful Transaction
if (!empty($_SESSION['cart_session_id']) && !empty($_SESSION['BRANCH_ID'])) {
    $deleteQuery = "DELETE FROM pos_cart WHERE SESSION_ID = ? AND BRANCH_ID = ?";
    $stmt = mysqli_prepare($db, $deleteQuery);
    mysqli_stmt_bind_param($stmt, "si", $_SESSION['cart_session_id'], $_SESSION['BRANCH_ID']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Clear Session After Transaction
unset($_SESSION['pointofsale']);
unset($_SESSION['cart_session_id']);
unset($_SESSION['BRANCH_ID']);
session_regenerate_id(true);

?>

<script type="text/javascript">
    alert("Transaction Successful!");
    window.location = "pos.php";
</script>