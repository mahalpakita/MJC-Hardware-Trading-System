<?php
include '../includes/connection.php';

if (isset($_POST['code'])) {
    $productCode = mysqli_real_escape_string($db, $_POST['code']);

    // Query to get the product price based on product code
    $query = "SELECT PRICE FROM product WHERE PRODUCT_CODE LIKE '$productCode%' LIMIT 1";
    $result = mysqli_query($db, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        echo $row['PRICE']; // Return the price
    } else {
        echo ""; // Return empty if no match found
    }
}
mysqli_close($db);
?>
