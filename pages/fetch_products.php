<?php
include '../includes/connection.php';

if (isset($_POST['supplier_id'])) {
    $supplierID = intval($_POST['supplier_id']);

    // Fetch products that belong to the selected supplier
    $query = "SELECT PRODUCT_ID, NAME, PRICE, DESCRIPTION FROM product WHERE SUPPLIER_ID = $supplierID";
    $result = mysqli_query($db, $query) or die("Error: " . mysqli_error($db));

    echo '<option value="">Select Product</option>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='".$row['PRODUCT_ID']."' data-price='".$row['PRICE']."' data-description='".$row['DESCRIPTION']."'>".$row['NAME']." - ".$row['DESCRIPTION']." (₱".$row['PRICE'].")</option>";
    }
}
?>
