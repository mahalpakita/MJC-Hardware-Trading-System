<?php
include '../includes/connection.php';
session_start();

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

// Required CSS
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg rounded-lg border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3 rounded-top">
                    <div class="d-flex align-items-center">
                        <h4 class="m-0 font-weight-bold">
                            <i class="fas fa-receipt me-2"></i>Transaction Details
                        </h4>
                    </div>
                    <a href="javascript:history.back();" class="btn btn-light text-primary shadow-sm rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
                <div class="card-body">
                    <?php
                    $query = 'SELECT *, FIRST_NAME, LAST_NAME, PHONE_NUMBER, EMPLOYEE, ROLE, DISCOUNT
                            FROM transaction T
                            JOIN customer C ON T.`CUST_ID`=C.`CUST_ID`
                            JOIN transaction_details tt ON tt.`TRANS_NO`=T.`TRANS_NO`
                            WHERE TRANS_ID ='.$_GET['id'];
                    $result = mysqli_query($db, $query) or die (mysqli_error($db));
                    while ($row = mysqli_fetch_assoc($result)) {
                        $fname = $row['FIRST_NAME'];
                        $lname = $row['LAST_NAME'];
                        $pn = $row['PHONE_NUMBER'];
                        $date = $row['DATE'];
                        $tid = $row['TRANS_NO'];
                        $cash = $row['CASH'];
                        $disco = $row['DISCOUNT'];
                        $sub = $row['SUBTOTAL'];
                        $less = $row['LESSVAT'];
                        $net = $row['NETVAT'];
                        $add = $row['ADDVAT'];
                        $grand = $row['GRANDTOTAL'];
                        $role = $row['EMPLOYEE'];
                        $roles = $row['ROLE'];
                        $payment_clean = strtolower(trim($row['PAYMENT']));
                        $ref_no = $row['REF_NO'] ?? '';
                    }
                    ?>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold mb-3">
                                        <i class="fas fa-user me-2 text-primary"></i>Customer Information
                                    </h5>
                                    <p class="mb-1"><strong>Name:</strong> <?php echo $fname . ' ' . $lname; ?></p>
                                    <p class="mb-1"><strong>Phone:</strong> <?php echo $pn; ?></p>
                                    <p class="mb-0"><strong>Date:</strong> <?php echo date('F d, Y h:i A', strtotime($date)); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold mb-3">
                                        <i class="fas fa-info-circle me-2 text-primary"></i>Transaction Information
                                    </h5>
                                    <p class="mb-1"><strong>Transaction #:</strong> <?php echo $tid; ?></p>
                                    <?php if (!in_array($payment_clean, ['cash', 'bank'], true) && !empty($ref_no)) : ?>
                                        <p class="mb-1"><strong>Reference #:</strong> <?= htmlspecialchars($ref_no) ?></p>
                                    <?php endif; ?>
                                    <p class="mb-1"><strong>Cashier:</strong> <?php echo $role; ?></p>
                                    <p class="mb-0"><strong>Branch:</strong> 
                                        <?php 
                                        $query_branch = "SELECT b.BRANCH_NAME 
                                                        FROM customer c 
                                                        JOIN branches b ON c.BRANCH_ID = b.BRANCH_ID 
                                                        WHERE c.CUST_ID = (SELECT CUST_ID FROM transaction WHERE TRANS_ID = ".$_GET['id']." LIMIT 1) 
                                                        LIMIT 1";
                                        $result_branch = mysqli_query($db, $query_branch) or die(mysqli_error($db));
                                        $row_branch = mysqli_fetch_assoc($result_branch);
                                        echo !empty($row_branch['BRANCH_NAME']) ? $row_branch['BRANCH_NAME'] : 'N/A';
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">
                                <i class="fas fa-shopping-cart me-2 text-primary"></i>Order Details
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="fw-bold">Products</th>
                                            <th class="fw-bold">Description</th>
                                            <th class="fw-bold text-center">Qty</th>
                                            <th class="fw-bold text-end">Price</th>
                                            <th class="fw-bold text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php  
                                        $query = 'SELECT PRODUCTS, DESCRIPTION, QTY, PRICE 
                                                FROM transaction_details 
                                                WHERE TRANS_NO = '.$tid;
                                        $result = mysqli_query($db, $query) or die(mysqli_error($db));

                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $Sub = $row['QTY'] * $row['PRICE'];
                                            echo '<tr>';
                                            echo '<td>'. htmlspecialchars($row['PRODUCTS']) .'</td>';
                                            echo '<td>'. htmlspecialchars($row['DESCRIPTION']) .'</td>';
                                            echo '<td class="text-center"><span class="badge bg-primary p-2">'. $row['QTY'] .'</span></td>';
                                            echo '<td class="text-end">₱ '. number_format($row['PRICE'], 2) .'</td>';
                                            echo '<td class="text-end">₱ '. number_format($Sub, 2) .'</td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold mb-3">
                                        <i class="fas fa-credit-card me-2 text-primary"></i>Payment Information
                                    </h5>
                                    <?php 
                                    $query_payment = "SELECT payment 
                                                    FROM transaction_details 
                                                    WHERE TRANS_NO = $tid LIMIT 1";
                                    $result_payment = mysqli_query($db, $query_payment) or die(mysqli_error($db));
                                    $row_payment = mysqli_fetch_assoc($result_payment);
                                    ?>
                                    <p class="mb-2"><strong>Payment Method:</strong> <?php echo $row_payment['payment']; ?></p>
                                    <p class="mb-2"><strong>Cash Amount:</strong> ₱ <?php echo number_format($cash, 2); ?></p>
                                    <?php 
                                    $cash = floatval(str_replace(',', '', $cash));
                                    $grand = floatval(str_replace(',', '', $grand));
                                    $change = $cash - $grand;
                                    ?>
                                    <p class="mb-0"><strong>Change:</strong> ₱ <?php echo number_format($change, 2); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold mb-3">
                                        <i class="fas fa-calculator me-2 text-primary"></i>Total Summary
                                    </h5>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <tr>
                                                <td class="fw-bold">Subtotal</td>
                                                <td class="text-end">₱ <?php echo number_format($sub, 2); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Less VAT</td>
                                                <td class="text-end">₱ <?php echo number_format($less, 2); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Net of VAT</td>
                                                <td class="text-end">₱ <?php echo number_format($net, 2); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Discount</td>
                                                <td class="text-end">₱ <?php echo number_format($disco, 2); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Add VAT</td>
                                                <td class="text-end">₱ <?php echo number_format($add, 2); ?></td>
                                            </tr>
                                            <tr class="border-top">
                                                <td class="fw-bold">Total</td>
                                                <td class="text-end fw-bold text-primary">₱ <?php echo number_format($grand, 2); ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="receipt_pdf.php?id=<?php echo $_GET['id']; ?>" target="_blank" class="btn btn-primary rounded-pill px-4">
                            <i class="fas fa-print me-2"></i>Print Receipt
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<!-- Required Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
