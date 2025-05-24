<?php
include '../includes/connection.php';

// Debugging: Print POST data
error_log("POST data: " . print_r($_POST, true));

$pc = mysqli_real_escape_string($db, $_POST['prodcode']);  // Product Code
$name = mysqli_real_escape_string($db, $_POST['name']);    // Product Name
$desc = mysqli_real_escape_string($db, $_POST['description']); // Description
$qty = intval($_POST['quantity']);   // Quantity
$pr = floatval($_POST['price']);     // Price
$supp = isset($_POST['supplier']) ? intval($_POST['supplier']) : NULL;
$branch = intval($_POST['branch_id']); // Branch ID
$remarks = mysqli_real_escape_string($db, $_POST['remarks']); // Get remarks

// Date handling
$dats = !empty($_POST['datestock']) ? $_POST['datestock'] : date('Y-m-d');
$exp_date = !empty($_POST['expiration']) ? $_POST['expiration'] : NULL;

// Debug the date values
error_log("Raw Date Stock In value: " . $_POST['datestock']);
error_log("Processed Date Stock In value: " . $dats);

if (empty($supp)) {
    die("<script>alert('Error: Supplier is required.'); window.history.back();</script>");
}

if ($_GET['action'] == 'add') {
    // Direct SQL insertion with explicit date formatting
    $query = "INSERT INTO product 
        (PRODUCT_CODE, NAME, DESCRIPTION, QTY_STOCK, PRICE, SUPPLIER_ID, DATE_STOCK_IN, EXPIRATION_DATE, BRANCH_ID, REMARKS) 
        VALUES (
            '$pc',
            '$name',
            '$desc',
            $qty,
            $pr,
            $supp,
            DATE('$dats'),
            " . ($exp_date ? "DATE('$exp_date')" : "NULL") . ",
            $branch,
            '$remarks'
        )";

    error_log("SQL Query: " . $query);

    if (!mysqli_query($db, $query)) {
        error_log("MySQL Error: " . mysqli_error($db));
        die('Error in executing query: ' . mysqli_error($db));
    }

    $productId = mysqli_insert_id($db);

    // Insert into product_history
    $user = 'Admin'; // Replace with session user if available
    $historyQuery = "INSERT INTO product_history 
        (PRODUCT_ID, PRODUCT_CODE, NAME, DESCRIPTION, QTY_STOCK, PRICE, SUPPLIER_ID, BRANCH_ID, ACTION_TYPE, USER, ACTION_DATE)
        VALUES ($productId, '$pc', '$name', '$desc', $qty, $pr, $supp, $branch, 'Added', '$user', NOW())";

    if (!mysqli_query($db, $historyQuery)) {
        error_log("Error inserting history: " . mysqli_error($db));
    }
}
?>
<script type="text/javascript">
    window.location = "inventory.php";
</script>

<?php
include '../includes/footer.php';
?>
