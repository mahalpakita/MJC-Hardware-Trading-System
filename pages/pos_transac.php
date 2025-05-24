<?php
include '../includes/connection.php';

// Debugging: Print POST data to check if remarks and supplier are included
error_log("POST data: " . print_r($_POST, true));

$pc = mysqli_real_escape_string($db, $_POST['prodcode']);  // Product Code
$name = mysqli_real_escape_string($db, $_POST['name']);    // Product Name
$desc = mysqli_real_escape_string($db, $_POST['description']); // Description
$qty = intval($_POST['quantity']);   // Quantity
$pr = floatval($_POST['price']);     // Price
$supp = isset($_POST['supplier']) ? intval($_POST['supplier']) : NULL;

// Date handling with validation
$dats = !empty($_POST['datestock']) ? $_POST['datestock'] : date('Y-m-d');
$exp_date = !empty($_POST['expiration']) ? $_POST['expiration'] : NULL;

// Function to validate date format
function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

// Validate dates
if (!validateDate($dats)) {
    die("<script>alert('Error: Invalid Date Stock In format.'); window.history.back();</script>");
}

if ($exp_date !== NULL && !validateDate($exp_date)) {
    die("<script>alert('Error: Invalid Expiration Date format.'); window.history.back();</script>");
}

// Debug the date values
error_log("Raw Date Stock In value: " . $_POST['datestock']);
error_log("Processed Date Stock In value: " . $dats);
error_log("Raw Expiration Date value: " . $_POST['expiration']);
error_log("Processed Expiration Date value: " . $exp_date);

$branch = intval($_POST['branch_id']); // Branch ID
$remarks = mysqli_real_escape_string($db, $_POST['remarks']); // Get remarks

if (empty($supp)) {
    die("<script>alert('Error: Supplier is required.'); window.history.back();</script>");
}

if ($_GET['action'] == 'add') {
    // Format dates properly
    $dats = date('Y-m-d', strtotime($dats));
    $exp_date = $exp_date ? date('Y-m-d', strtotime($exp_date)) : NULL;

    // Ensure date is in correct format
    $dats = DateTime::createFromFormat('Y-m-d', $dats);
    if (!$dats) {
        die("<script>alert('Error: Invalid Date Stock In format.'); window.history.back();</script>");
    }
    $dats = $dats->format('Y-m-d');

    // Insert into `product` table using prepared statement
    $query = "INSERT INTO product 
        (PRODUCT_CODE, NAME, DESCRIPTION, QTY_STOCK, PRICE, SUPPLIER_ID, DATE_STOCK_IN, EXPIRATION_DATE, BRANCH_ID, REMARKS) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($db, $query);
    if (!$stmt) {
        die('Error in preparing statement: ' . mysqli_error($db));
    }

    // Debug the values
    error_log("Values being bound: " . print_r([
        'pc' => $pc,
        'name' => $name,
        'desc' => $desc,
        'qty' => $qty,
        'pr' => $pr,
        'supp' => $supp,
        'dats' => $dats,
        'exp_date' => $exp_date,
        'branch' => $branch,
        'remarks' => $remarks
    ], true));

    // Bind parameters with correct types
    mysqli_stmt_bind_param($stmt, "sssdisisss", 
        $pc,      // PRODUCT_CODE (s)
        $name,    // NAME (s)
        $desc,    // DESCRIPTION (s)
        $qty,     // QTY_STOCK (d)
        $pr,      // PRICE (i)
        $supp,    // SUPPLIER_ID (s)
        $dats,    // DATE_STOCK_IN (s)
        $exp_date,// EXPIRATION_DATE (s)
        $branch,  // BRANCH_ID (s)
        $remarks  // REMARKS (s)
    );
    
    if (!mysqli_stmt_execute($stmt)) {
        error_log("MySQL Error: " . mysqli_stmt_error($stmt));
        error_log("MySQL Error Code: " . mysqli_stmt_errno($stmt));
        die('Error in executing statement: ' . mysqli_stmt_error($stmt));
    }

    // Debug the actual SQL query and values
    error_log("SQL Query: " . $query);
    error_log("Date Stock In value being inserted: " . $dats);
    error_log("MySQL Error: " . mysqli_error($db));

    $productId = mysqli_insert_id($db);
    mysqli_stmt_close($stmt);

    // Insert into product_history
    $user = 'Admin'; // Replace with session user if available

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
