<?php
include '../includes/connection.php';

// Debugging: Print POST data to check if remarks and supplier are included
print_r($_POST);

$pc = mysqli_real_escape_string($db, $_POST['prodcode']);  // Product Code
$name = mysqli_real_escape_string($db, $_POST['name']);    // Product Name
$desc = mysqli_real_escape_string($db, $_POST['description']); // Description
$qty = intval($_POST['quantity']);   // Quantity
$pr = floatval($_POST['price']);     // Price
$supp = isset($_POST['supplier']) ? intval($_POST['supplier']) : NULL;
$dats = $_POST['datestock'];         // Date Stock In
$branch = intval($_POST['branch_id']); // Branch ID
$exp_date = !empty($_POST['expiration']) ? date('Y-m-d', strtotime($_POST['expiration'])) : NULL;
$remarks = mysqli_real_escape_string($db, $_POST['remarks']); // Get remarks

if (empty($supp)) {
    die("<script>alert('Error: Supplier is required.'); window.history.back();</script>");
}

if ($_GET['action'] == 'add') {
    // Insert into `product` table using prepared statements
    $query = "INSERT INTO product 
        (PRODUCT_CODE, NAME, DESCRIPTION, QTY_STOCK, PRICE, SUPPLIER_ID, DATE_STOCK_IN, EXPIRATION_DATE, BRANCH_ID, REMARKS) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "sssdisiiss", $pc, $name, $desc, $qty, $pr, $supp, $dats, $exp_date, $branch, $remarks);
    $result = mysqli_stmt_execute($stmt);
    $productId = mysqli_insert_id($db); // Get the last inserted product ID
    mysqli_stmt_close($stmt);

    if (!$result) {
        die('Error in adding product to Database: ' . mysqli_error($db));
    }

    // Insert into `product_history` table
    $user = 'Admin'; // Change this to the logged-in user

    $historyQuery = "INSERT INTO product_history 
        (PRODUCT_ID, PRODUCT_CODE, NAME, DESCRIPTION, QTY_STOCK, PRICE, SUPPLIER_ID, BRANCH_ID, ACTION_TYPE, USER, ACTION_DATE)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Added', ?, NOW())";

    $historyStmt = mysqli_prepare($db, $historyQuery);
    mysqli_stmt_bind_param($historyStmt, "isssdisis", $productId, $pc, $name, $desc, $qty, $pr, $supp, $branch, $user);
    mysqli_stmt_execute($historyStmt);
    mysqli_stmt_close($historyStmt);
}

?>
<script type="text/javascript">
    window.location = "inventory.php";
</script>

<?php
include '../includes/footer.php';
?>
