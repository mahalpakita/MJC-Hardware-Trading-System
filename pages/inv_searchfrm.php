<?php
include '../includes/connection.php';
session_start();

if (!isset($_SESSION['MEMBER_ID'])) {
    die('Access Denied: No session found.');
}

// Fetch user role
$querya = "SELECT u.TYPE_ID FROM users u WHERE u.ID = " . intval($_SESSION['MEMBER_ID']);
$resulta = mysqli_query($db, $querya) or die(mysqli_error($db));

if ($row = mysqli_fetch_assoc($resulta)) {
    $userTypeID = $row['TYPE_ID'];

    // Compare TYPE_ID (1 = Admin, 2 = Cashier, 3 = Manager)
    if ($userTypeID == 1) {
        include '../includes/sidebar.php';
    } elseif ($userTypeID == 2) {    
        include '../includes/sidebar_cashier.php';
    } elseif ($userTypeID == 3) {
        include '../includes/sidebar_manger.php'; // Fixed filename
    } else {
        die('Unauthorized User Type');
    }
} else {
    die('User not found in database.');
}
?>
<?php
// Database query to fetch product details
$query = 'SELECT p.PRODUCT_ID, p.PRODUCT_CODE, p.NAME, p.DESCRIPTION, p.QTY_STOCK, p.PRICE, p.REMARKS, 
                 s.COMPANY_NAME AS SUPPLIER, p.DATE_STOCK_IN, p.EXPIRATION_DATE, 
                 b.BRANCH_NAME, p.STATUS 
          FROM product p 
          JOIN supplier s ON p.SUPPLIER_ID = s.SUPPLIER_ID 
          JOIN branches b ON p.BRANCH_ID = b.BRANCH_ID 
          WHERE p.PRODUCT_ID = ' . intval($_GET['id']);

$result = mysqli_query($db, $query) or die(mysqli_error($db));

// Fetch the result
while ($row = mysqli_fetch_array($result)) {   
    $product_id = $row['PRODUCT_ID'];
    $product_code = $row['PRODUCT_CODE'];
    $product_name = $row['NAME'];
    $description = $row['DESCRIPTION'];
    $quantity = $row['QTY_STOCK'];
    $price = number_format($row['PRICE'], 2);
    $supplier = $row['SUPPLIER'];
    $date_stock_in = $row['DATE_STOCK_IN'];
    $expiration_date = !empty($row['EXPIRATION_DATE']) ? $row['EXPIRATION_DATE'] : 'N/A';
    $status = $row['STATUS'];
    $branch = $row['BRANCH_NAME'];
    $remarks = $row['REMARKS'];
}
$id = $_GET['id'];
?><?php
// Fetch product details
$query = 'SELECT p.PRODUCT_ID, p.PRODUCT_CODE, p.NAME, p.DESCRIPTION, p.QTY_STOCK, p.PRICE, p.REMARKS, 
                 s.COMPANY_NAME AS SUPPLIER, p.DATE_STOCK_IN, p.EXPIRATION_DATE, 
                 b.BRANCH_NAME, p.STATUS 
          FROM product p 
          JOIN supplier s ON p.SUPPLIER_ID = s.SUPPLIER_ID 
          JOIN branches b ON p.BRANCH_ID = b.BRANCH_ID 
          WHERE p.PRODUCT_ID = ' . intval($_GET['id']);

$result = mysqli_query($db, $query) or die(mysqli_error($db));
$row = mysqli_fetch_array($result);

$product_code = $row['PRODUCT_CODE'];
$product_name = $row['NAME'];
$description = $row['DESCRIPTION'];
$quantity = $row['QTY_STOCK'];
$price = number_format($row['PRICE'], 2);
$supplier = $row['SUPPLIER'];
$date_stock_in = $row['DATE_STOCK_IN'];
$expiration_date = !empty($row['EXPIRATION_DATE']) ? $row['EXPIRATION_DATE'] : 'N/A';
$status = $row['STATUS'];
$branch = $row['BRANCH_NAME'];
$remarks = $row['REMARKS'];

// Status badge color
$status_badge = match ($status) {
    'Out of Stock' => 'danger',
    'Low Stock' => 'warning',
    'About to Expire' => 'orange',
    default => 'success'
};
?>

<div class="container mt-5">
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white text-center py-4">
            <h3 class="fw-bold mb-0"><i class="fas fa-box-open me-2"></i> Product Details</h3>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <h6 class="text-muted"><i class="fas fa-barcode me-2"></i> Product Code</h6>
                    <p class="fw-semibold"><?php echo $product_code; ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted"><i class="fas fa-tag me-2"></i> Name</h6>
                    <p class="fw-semibold"><?php echo $product_name; ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted"><i class="fas fa-align-left me-2"></i> Description</h6>
                    <p class="fw-semibold"><?php echo $description; ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted"><i class="fas fa-boxes me-2"></i> Quantity</h6>
                    <p class="fw-semibold"><?php echo $quantity; ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted"><i class="fas fa-coins me-2"></i> Price</h6>
                    <p class="fw-semibold text-success">₱<?php echo $price; ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted"><i class="fas fa-truck me-2"></i> Supplier</h6>
                    <p class="fw-semibold"><?php echo $supplier; ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted"><i class="fas fa-calendar-plus me-2"></i> Date Stocked In</h6>
                    <p class="fw-semibold"><?php echo $date_stock_in; ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted"><i class="fas fa-calendar-alt me-2"></i> Expiration Date</h6>
                    <p class="fw-semibold"><?php echo $expiration_date; ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted"><i class="fas fa-info-circle me-2"></i> Status</h6>
                    <span class="badge bg-<?php echo $status_badge; ?> p-2"><?php echo $status; ?></span>
                </div>
                
                <div class="col-md-6">
                    <h6 class="text-muted"><i class="fas fa-store me-2"></i> Branch</h6>
                    <p class="fw-semibold"><?php echo $branch; ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted"><i class="fas fa-info-circle me-2"></i>Remarks</h6>
                    <span class="badge bg-<?php echo $remarks; ?> p-2"><?php echo $remarks; ?></span>
                </div>
            </div>
        </div>
        <div class="card-footer text-center py-3">
    <a 
        <?php echo ($userTypeID == 1) ? 'href="inventory.php"' : 'href="inventory_cashier.php"'; ?> 
        class="btn btn-lg btn-primary px-4">
        <i class="fas fa-arrow-left me-2"></i>Back to inventory
    </a>
</div>
        
    </div>
</div>




<?php
include '../includes/footer.php';
?>
