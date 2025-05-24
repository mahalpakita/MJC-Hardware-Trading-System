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


// Get Product Details
$query = 'SELECT p.PRODUCT_ID, p.PRODUCT_CODE, p.NAME, p.DESCRIPTION, p.QTY_STOCK, p.PRICE, 
                 p.DATE_STOCK_IN, p.EXPIRATION_DATE, p.REMARKS, b.BRANCH_NAME, s.COMPANY_NAME
          FROM product p 
          JOIN supplier s ON p.SUPPLIER_ID = s.SUPPLIER_ID 
          LEFT JOIN branches b ON p.BRANCH_ID = b.BRANCH_ID 
          WHERE p.PRODUCT_ID =' . intval($_GET['id']);


$result = mysqli_query($db, $query) or die(mysqli_error($db));

while ($row = mysqli_fetch_array($result)) {
    $zz = $row['PRODUCT_ID'];
    $zzz = $row['PRODUCT_CODE'];
    $A = $row['NAME'];
    $B = $row['QTY_STOCK'];
    $C = $row['PRICE'];
    $D = $row['COMPANY_NAME'];
    $F = $row['DESCRIPTION']; // Product Description
    $G = $row['BRANCH_NAME']; // Branch Name
    $H = isset($row['EXPIRATION_DATE']) ? $row['EXPIRATION_DATE'] : ''; // Handle NULL values
    $I = isset($row['REMARKS']) ? $row['REMARKS'] : ''; // Handle NULL remarks
    $J = isset($row['DATE_STOCK_IN']) ? date('Y-m-d', strtotime($row['DATE_STOCK_IN'])) : ''; // Date Stock In
}

$id = $_GET['id'];
?>

<center>
<div class="card shadow mb-4 col-xs-12 col-md-8 border-bottom-primary">
    <div class="card-header py-3">
        <h4 class="m-2 font-weight-bold text-primary">Edit Inventory for: <?php echo $A ?></h4>
        <hr>
        <div class="card-body">
            <form role="form" method="post" action="inv_edit1.php">
                <input type="hidden" name="idd" value="<?php echo $zz; ?>" />

                <div class="form-group row text-left">
                    <div class="col-sm-3" style="padding-top: 5px;">Product Code:</div>
                    <div class="col-sm-9">
                        <input class="form-control" id="product_code" name="product_code" value="<?php echo $zzz; ?>" >
                    </div>
                </div>

                <div class="form-group row text-left">
                    <div class="col-sm-3" style="padding-top: 5px;">Product Name:</div>
                    <div class="col-sm-9">
                        <input class="form-control" placeholder="name" name="name" value="<?php echo $A; ?>" >
                    </div>
                </div>

                <div class="form-group row text-left">
                    <div class="col-sm-3" style="padding-top: 5px;">Quantity:</div>
                    <div class="col-sm-9">
                        <input class="form-control" placeholder="Quantity" name="qty" value="<?php echo $B; ?>" required>
                    </div>
                </div>

                <div class="form-group row text-left">
                    <div class="col-sm-3" style="padding-top: 5px;">Price:</div>
                    <div class="col-sm-9">
                        <input class="form-control" id="price" placeholder="Price" name="price" value="<?php echo $C; ?>" required>
                    </div>
                </div>

                <div class="form-group row text-left">
                    <div class="col-sm-3" style="padding-top: 5px;">Description:</div>
                    <div class="col-sm-9">
                        <textarea class="form-control" placeholder="Product Description" name="description" required><?php echo htmlspecialchars($F); ?></textarea>
                    </div>
                </div>
                <div class="form-group row text-left">
                    <div class="col-sm-3" style="padding-top: 5px;">Remarks:</div>
                    <div class="col-sm-9">
                        <textarea class="form-control" placeholder="Enter remarks" name="remarks"><?php echo htmlspecialchars($I); ?></textarea>
                    </div>
                </div>


                <div class="form-group row text-left">
                    <div class="col-sm-3" style="padding-top: 5px;">Branch:</div>
                    <div class="col-sm-9">
                        <input class="form-control" value="<?php echo htmlspecialchars($G); ?>" readonly>
                    </div>
                </div>

                <div class="form-group row text-left">
                    <div class="col-sm-3" style="padding-top: 5px;">Supplier:</div>
                    <div class="col-sm-9">
                        <input class="form-control" value="<?php echo $D; ?>" readonly>
                    </div>
                </div>

                <div class="form-group row text-left">
                    <div class="col-sm-3" style="padding-top: 5px;">Date Stock In:</div>
                    <div class="col-sm-9">
                        <input type="date" class="form-control" value="<?php echo $J; ?>" readonly>
                    </div>
                </div>

                <div class="form-group row text-left">
                    <div class="col-sm-3" style="padding-top: 5px;">Expiration Date (Optional):</div>
                    <div class="col-sm-9">
                        <input type="date" class="form-control" name="expiration_date" value="<?php echo $H; ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-warning btn-block"><i class="fa fa-edit fa-fw"></i> Update</button>
                    </div>
                    <div class="col-md-6">
                        <a type="button" class="btn btn-danger btn-block" href="inv_del.php?action=delete&id=<?php echo $zz; ?>" onclick="return confirm('Are you sure you want to delete this product?')">
                            <i class="fas fa-fw fa-trash"></i> Delete
                        </a>
                    </div>
                </div>

                <div class="mt-3">
                    <a type="button" class="btn btn-primary bg-gradient-primary btn-block" href="inventory.php">
                        <i class="fas fa-fw fa-flip-horizontal fa-share"></i> Back
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>
</center>

<?php
include '../includes/footer.php';
?>
