<?php 
require_once('redirect.php');
allowOnlyAdmin();
?>

<?php
include '../includes/connection.php';


if (!isset($_SESSION['MEMBER_ID'])) {
    header("Location: login.php"); // Redirect to the login page
    exit(); // Stop script execution
}

// Get user ID
$userID = $_SESSION['MEMBER_ID'];

// Get Product ID
$productID = intval($_POST['idd']);

// Fetch the current product details
$productQuery = "SELECT NAME, PRODUCT_CODE, QTY_STOCK, PRICE, DESCRIPTION, EXPIRATION_DATE, REMARKS 
                 FROM product WHERE PRODUCT_ID = $productID";
$productResult = mysqli_query($db, $productQuery);
$productRow = mysqli_fetch_assoc($productResult);

$oldName = $productRow['NAME'];
$oldCode = $productRow['PRODUCT_CODE'];
$oldQty = $productRow['QTY_STOCK'];
$oldPrice = $productRow['PRICE'];
$oldDescription = $productRow['DESCRIPTION'];
$oldExpirationDate = $productRow['EXPIRATION_DATE'];
$oldRemarks = $productRow['REMARKS']; // Fetch old remarks

// Get new values from form
$newName = mysqli_real_escape_string($db, $_POST['name']);
$newCode = mysqli_real_escape_string($db, $_POST['product_code']);
$newQty = intval($_POST['qty']);
$newPrice = floatval($_POST['price']);
$newDescription = mysqli_real_escape_string($db, $_POST['description']);
$newExpirationDate = !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : NULL;
$newRemarks = mysqli_real_escape_string($db, $_POST['remarks']); // Get new remarks

// Compare old and new values, and log changes if they are different
$changes = [];

if ($newName != $oldName) {
    $changes[] = "('{$productID}', '{$userID}', 'Product Name', '{$oldName}', '{$newName}')";
}
if ($newCode != $oldCode) {
    $changes[] = "('{$productID}', '{$userID}', 'Product Code', '{$oldCode}', '{$newCode}')";
}
if ($newQty != $oldQty) {
    $changes[] = "('{$productID}', '{$userID}', 'Quantity', '{$oldQty}', '{$newQty}')";
}
if ($newPrice != $oldPrice) {
    $changes[] = "('{$productID}', '{$userID}', 'Price', '{$oldPrice}', '{$newPrice}')";
}
if ($newDescription != $oldDescription) {
    $changes[] = "('{$productID}', '{$userID}', 'Description', '{$oldDescription}', '{$newDescription}')";
}
if ($newExpirationDate != $oldExpirationDate) {
    $changes[] = "('{$productID}', '{$userID}', 'Expiration Date', '{$oldExpirationDate}', '{$newExpirationDate}')";
}
if ($newRemarks != $oldRemarks) {
    $changes[] = "('{$productID}', '{$userID}', 'Remarks', '{$oldRemarks}', '{$newRemarks}')"; // Log changes to remarks
}

// Insert changes into product_edit_history if there are any
if (!empty($changes)) {
    $sqlLog = "INSERT INTO product_edit_history (PRODUCT_ID, USER_ID, FIELD_CHANGED, OLD_VALUE, NEW_VALUE) VALUES " . implode(',', $changes);
    mysqli_query($db, $sqlLog) or die(mysqli_error($db));
}

// Update product details (Handle NULL expiration date properly)
$updateQuery = "UPDATE product SET 
                NAME = '$newName',
                PRODUCT_CODE = '$newCode',
                QTY_STOCK = '$newQty', 
                PRICE = '$newPrice', 
                DESCRIPTION = '$newDescription', 
                EXPIRATION_DATE = " . ($newExpirationDate ? "'$newExpirationDate'" : "NULL") . ", 
                REMARKS = '$newRemarks' 
                WHERE PRODUCT_ID = '$productID'";

if (mysqli_query($db, $updateQuery)) {
    echo "<script>alert('Product updated successfully!'); window.location = 'inventory.php';</script>";
} else {
    echo "Error updating product: " . mysqli_error($db);
}

mysqli_close($db);
?>
