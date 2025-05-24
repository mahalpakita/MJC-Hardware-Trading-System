<?php
include '../includes/connection.php';
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['MEMBER_ID'])) {
    header("Location: login.php");
    exit();
}

// Fetch user role
$stmt = mysqli_prepare($db, "SELECT TYPE_ID, BRANCH_ID FROM users WHERE ID = ?");
mysqli_stmt_bind_param($stmt, "i", $_SESSION['MEMBER_ID']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $userTypeID = $row['TYPE_ID'];
    $_SESSION['BRANCH_ID'] = $row['BRANCH_ID']; // Store branch ID in session
} else {
    die('Error: User not found.');
}

$userBranchID = $_SESSION['BRANCH_ID'];

// Include appropriate sidebar based on user type
switch ($userTypeID) {
    case 1:
        include '../includes/sidebar.php';
        break;
    case 2:
        include '../includes/sidebar_cashier.php';
        break;
    case 3:
        include '../includes/sidebar_manger.php';
        break;
    default:
        die('Unauthorized User Type');
}

// Validate transaction ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Error: Invalid Transaction ID.');
}

$transactionID = intval($_GET['id']);

// Fetch transaction details
$query = 'SELECT t.TRANS_ID, t.TRANS_NO, t.CUST_ID, t.CASH, t.PAYMENT, t.REF_NO, t.STATUS, c.FIRST_NAME, c.LAST_NAME 
          FROM transaction t 
          JOIN customer c ON t.CUST_ID = c.CUST_ID 
          WHERE t.TRANS_ID = ?';

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $transactionID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$row = mysqli_fetch_assoc($result)) {
    die('Error: Transaction not found.');
}

// Assign transaction details
$transId = $row['TRANS_ID'];
$transNo = $row['TRANS_NO'];
$custId = $row['CUST_ID'];
$amountPaid = $row['CASH'];
$paymentMethod = $row['PAYMENT'];
$refNo = $row['REF_NO'];
$status = $row['STATUS'];

// Fetch customers from the same branch
$customerQuery = "SELECT CUST_ID, CONCAT(FIRST_NAME, ' ', LAST_NAME) AS CUST_NAME 
                  FROM customer 
                  WHERE BRANCH_ID = ? 
                  ORDER BY FIRST_NAME ASC";

$stmtCustomers = mysqli_prepare($db, $customerQuery);
mysqli_stmt_bind_param($stmtCustomers, "i", $userBranchID);
mysqli_stmt_execute($stmtCustomers);
$customerResult = mysqli_stmt_get_result($stmtCustomers);
?>

<script>
    function toggleRefNoField() {
        var paymentMethod = document.getElementById("payment_method").value;
        var refNoField = document.getElementById("ref_no");

        if (paymentMethod === "Cash") {
            refNoField.value = "";
            refNoField.disabled = true;
        } else {
            refNoField.disabled = false;
        }
    }
</script>

<center>
<div class="card shadow mb-4 col-xs-12 col-md-8 border-bottom-primary">
    <div class="card-header py-3">
        <h4 class="m-2 font-weight-bold text-primary">Edit Transaction</h4>
        <hr>
        <div class="card-body">

            <form role="form" method="post" action="process_transaction_edit.php">
                <input type="hidden" name="trans_id" value="<?php echo $transId; ?>" />
                <input type="hidden" name="trans_no" value="<?php echo $transNo; ?>" />

                <div class="form-group row text-left">
                    <div class="col-sm-3" style="padding-top: 5px;">Transaction No:</div>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?php echo $transNo; ?>" readonly>
                    </div>
                </div>

                <div class="form-group row text-left">
                    <div class="col-sm-3" style="padding-top: 5px;">Customer:</div>
                    <div class="col-sm-9">
                        <select class="form-control" name="cust_id" required>
                            <option value="">-- Select Customer --</option>
                            <?php
                            while ($customerRow = mysqli_fetch_assoc($customerResult)) { 
                                echo '<option value="' . $customerRow['CUST_ID'] . '" ' . 
                                    (($customerRow['CUST_ID'] == $custId) ? 'selected' : '') . '>' . 
                                    htmlspecialchars($customerRow['CUST_NAME']) . 
                                    '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row text-left">
                    <div class="col-sm-3" style="padding-top: 5px;">Amount Paid:</div>
                    <div class="col-sm-9">
                        <input class="form-control" name="amount_paid" value="<?php echo $amountPaid; ?>" required>
                    </div>
                </div>

                <div class="form-group row text-left">
                    <div class="col-sm-3" style="padding-top: 5px;">Payment Method:</div>
                    <div class="col-sm-9">
                        <select class="form-control" name="payment_method" id="payment_method" onchange="toggleRefNoField()" required>
                            <option value="Cash" <?php echo ($paymentMethod == 'Cash') ? 'selected' : ''; ?>>Cash</option>
                            <option value="Online" <?php echo ($paymentMethod == 'Online') ? 'selected' : ''; ?>>Online</option>
                            <option value="Bank" <?php echo ($paymentMethod == 'Bank') ? 'selected' : ''; ?>>Bank</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row text-left">
                    <div class="col-sm-3" style="padding-top: 5px;">Reference No:</div>
                    <div class="col-sm-9">
                        <input class="form-control" name="ref_no" id="ref_no" value="<?php echo $refNo; ?>" 
                               <?php echo ($paymentMethod == 'Cash') ? 'disabled' : ''; ?> >
                    </div>
                </div>

                <div class="form-group row text-left">
                    <div class="col-sm-3" style="padding-top: 5px;">Status:</div>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?php echo $status; ?>" readonly>
                    </div>
                </div>

                <button type="submit" name="action" value="update" class="btn btn-warning btn-block">
                    <i class="fa fa-edit fa-fw"></i> Update
                </button>

                <button type="submit" name="action" value="void" class="btn btn-danger btn-block">
                    <i class="fa fa-times fa-fw"></i> Delete Transaction
                </button>

                <a href="transaction.php" class="btn btn-secondary btn-block">
                    <i class="fas fa-arrow-left fa-fw"></i> Back
                </a>
            </form>

        </div>
    </div>
</div>
</center>

<?php include '../includes/footer.php'; ?>
