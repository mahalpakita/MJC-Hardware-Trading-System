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
?>
<a href="javascript:history.back();" class="btn btn-lg btn-primary px-4"> <i class="fas fa-arrow-left me-2"></i> Back </a>
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
            
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="form-group row text-left mb-0">
                <div class="col-sm-9">
                  <h5 class="font-weight-bold">
                    Sales and Inventory
                  </h5>
                </div>
                <div class="col-sm-3 py-1">
                  <h6>
                    Date: <?php echo $date; ?>
                  </h6>
                </div>
              </div>
<hr>
              <div class="form-group row text-left mb-0 py-2">
                <div class="col-sm-4 py-1">
                  <h6 class="font-weight-bold">
                    <?php echo $fname; ?> <?php echo $lname; ?>
                  </h6>
                  <h6>
                    Phone: <?php echo $pn; ?>
                  </h6>
                </div>
                <div class="col-sm-4 py-1"></div>
<div class="col-sm-4 py-1">
    <h6>
        Transaction #<?php echo $tid; ?>
    </h6>
    <?php if (!in_array($payment_clean, ['cash', 'bank'], true) && !empty($ref_no)) : ?>
            <h6>Reference #<?= htmlspecialchars($ref_no) ?></h6>
        <?php endif; ?>
    <h6 class="font-weight-bold">
        Cashier: <?php echo $role; ?>
    </h6>
    <h6">
        Branch: 
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
    </h6>
</div>

              </div>
              <div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Products</th>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
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
                        echo '<td>'. htmlspecialchars($row['DESCRIPTION']) .'</td>'; // Added Description
                        echo '<td>'. $row['QTY'] .'</td>';
                        echo '<td>'. number_format($row['PRICE'], 2) .'</td>';
                        echo '<td>'. number_format($Sub, 2) .'</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="form-group row text-left mb-0 py-2">
    <div class="col-sm-4 py-1"></div>
    <div class="col-sm-3 py-1"></div>
    <div class="col-sm-4 py-1">
    <h4>
    Payment Method: 
    <?php 
        $query_payment = "SELECT payment 
                          FROM transaction_details 
                          WHERE TRANS_NO = $tid LIMIT 1";
        $result_payment = mysqli_query($db, $query_payment) or die(mysqli_error($db));
        $row_payment = mysqli_fetch_assoc($result_payment);
        echo $row_payment['payment'];
    ?>
</h4>

        <h4>
            <?php 
                $cash = floatval(str_replace(',', '', $cash));
                $grand = floatval(str_replace(',', '', $grand));
                $change = $cash - $grand;
            ?>
            Cash Amount: ₱ <?php echo number_format($cash, 2); ?>
        </h4>
        <h4>
            Change: ₱ <?php echo number_format($change, 2); ?>
        </h4>
        <table width="100%">
            <?php 
                $sub = floatval(str_replace(',', '', $sub)); 
                $less = floatval(str_replace(',', '', $less)); 
                $net = floatval(str_replace(',', '', $net)); 
                $add = floatval(str_replace(',', '', $add)); 
                $disco = floatval(str_replace(',', '', $disco   )); 
            ?>
            <tr>
                <td class="font-weight-bold">Subtotal</td>
                <td class="text-right">₱ <?php echo number_format($sub, 2); ?></td>
            </tr>
            <tr>
                <td class="font-weight-bold">Less VAT</td>
                <td class="text-right">₱ <?php echo number_format($less, 2); ?></td>
            </tr>
            <tr>
                <td class="font-weight-bold">Net of VAT</td>
                <td class="text-right">₱ <?php echo number_format($net, 2); ?></td>
            </tr>
            <tr>
                <td class="font-weight-bold">Discount</td>
                    <td class="text-right">₱ <?php echo number_format($disco, 2); ?></td>
                </tr>
                <tr>
            <tr>
                <td class="font-weight-bold">Add VAT</td>
                    <td class="text-right">₱ <?php echo number_format($add, 2); ?></td>
                </tr>
              
                    <td class="font-weight-bold">Total</td>
                    <td class="font-weight-bold text-right text-primary">₱ <?php echo number_format($grand, 2); ?></td>
                </tr>
            </table>
        </div>
        <a href="receipt_pdf.php?id=<?php echo $_GET['id']; ?>" target="_blank" class="btn btn-primary">
    🖨️ Print Receipt
</a>




        <div class="col-sm-1 py-1"></div>
        
    </div>


<?php
include '../includes/footer.php';
?>
