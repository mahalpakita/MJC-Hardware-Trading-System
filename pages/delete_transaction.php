<?php
include '../includes/connection.php';

$data = json_decode(file_get_contents("php://input"), true);
$transactionId = $data['transactionId'];
$transDId = $data['transDId'];

if (!$transactionId || !$transDId) {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
    exit;
}

// Step 1: Retrieve sold items
$sqlGetItems = "SELECT PRODUCT_ID, QTY FROM transaction_details WHERE TRANS_D_ID = ?";
$stmt = $conn->prepare($sqlGetItems);
$stmt->bind_param("s", $transDId);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
$stmt->close();

// Step 2: Restore product stock
foreach ($products as $product) {
    $updateStock = "UPDATE products SET QTY_STOCK = QTY_STOCK + ? WHERE PRODUCT_ID = ?";
    $stmt = $conn->prepare($updateStock);
    $stmt->bind_param("ii", $product['QTY'], $product['PRODUCT_ID']);
    $stmt->execute();
    $stmt->close();
}

// Step 3: Delete transaction details
$sqlDeleteDetails = "DELETE FROM transaction_details WHERE TRANS_D_ID = ?";
$stmt = $conn->prepare($sqlDeleteDetails);
$stmt->bind_param("s", $transDId);
$stmt->execute();
$stmt->close();

// Step 4: Delete transaction record
$sqlDeleteTransaction = "DELETE FROM transaction WHERE TRANS_ID = ?";
$stmt = $conn->prepare($sqlDeleteTransaction);
$stmt->bind_param("i", $transactionId);
$stmt->execute();
$stmt->close();

echo json_encode(["success" => true]);
?>
