<?php 
require_once('redirect.php');
allowOnlyManager();
?>


<?php 

include '../includes/connection.php';

if (!isset($_SESSION['MEMBER_ID'])) {
  header("Location: login.php"); // Redirect to the login page
  exit(); // Stop script execution
}

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

<div class="row show-grid">
  <!-- Customer ROW -->
  <div class="col-md-3">
    <!-- Customer record -->
    <div class="col-md-12 mb-3">
      <a href="customer.php" style="text-decoration: none;">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-0">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Customers</div>
                <div class="h6 mb-0 font-weight-bold text-gray-800">
                <?php 
                                // Get the logged-in user's branch
                                $userID = $_SESSION['MEMBER_ID'];
                                $branchQuery = "SELECT BRANCH_ID FROM users WHERE ID = '$userID'";
                                $branchResult = mysqli_query($db, $branchQuery) or die(mysqli_error($db));
                                $branchRow = mysqli_fetch_assoc($branchResult);
                                $branchID = $branchRow['BRANCH_ID'];

                                // Count products only for this branch
                                $query = "SELECT COUNT(*) FROM customer WHERE BRANCH_ID = '$branchID'";
                                $result = mysqli_query($db, $query) or die(mysqli_error($db));
                                $row = mysqli_fetch_array($result);
                                echo "$row[0]"; 
                                ?> Record(s)
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-child fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </a>
      
    </div>

    <!-- Customer record -->
    <div class="col-md-12 mb-3">
      <a href="transaction.php" style="text-decoration: none;">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-0">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Transactions</div>
                <div class="h6 mb-0 font-weight-bold text-gray-800">
                <?php 
                                // Get the logged-in user's branch
                                $userID = $_SESSION['MEMBER_ID'];
                                $branchQuery = "SELECT BRANCH_ID FROM users WHERE ID = '$userID'";
                                $branchResult = mysqli_query($db, $branchQuery) or die(mysqli_error($db));
                                $branchRow = mysqli_fetch_assoc($branchResult);
                                $branchID = $branchRow['BRANCH_ID'];

                                // Count products only for this branch
                                $query = "SELECT COUNT(*) FROM Transaction ";
                                $result = mysqli_query($db, $query) or die(mysqli_error($db));
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "$row[0]";
                                }
                                ?> Record(s)
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-retweet fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </a>
      
    </div>
  </div>

  <!-- Employee ROW -->
  <div class="col-md-3">
    <!-- Employee record -->
    <div class="col-md-12 mb-3">
      <a href="employee_manager.php" style="text-decoration: none;">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-0">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Employees</div>
                <div class="h6 mb-0 font-weight-bold text-gray-800">
                <?php 
                                // Get the logged-in user's branch
                                $userID = $_SESSION['MEMBER_ID'];
                                $branchQuery = "SELECT BRANCH_ID FROM users WHERE ID = '$userID'";
                                $branchResult = mysqli_query($db, $branchQuery) or die(mysqli_error($db));
                                $branchRow = mysqli_fetch_assoc($branchResult);
                                $branchID = $branchRow['BRANCH_ID'];

                                // Count products only for this branch
                                $query = "SELECT COUNT(*) FROM employee WHERE BRANCH_ID = '$branchID'";
                                $result = mysqli_query($db, $query) or die(mysqli_error($db));
                                $row = mysqli_fetch_array($result);
                                echo "$row[0]"; 
                                ?> Record(s)
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-user fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>


<!-- PRODUCTS ROW -->
<div class="col-md-3">
    <!-- Product record -->
    <div class="col-md-12 mb-3">
        <a href="Inventory_cashier.php" style="text-decoration: none;">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-0">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Inventory</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                // Get the logged-in user's branch
                                $userID = $_SESSION['MEMBER_ID'];
                                $branchQuery = "SELECT BRANCH_ID FROM users WHERE ID = '$userID'";
                                $branchResult = mysqli_query($db, $branchQuery) or die(mysqli_error($db));
                                $branchRow = mysqli_fetch_assoc($branchResult);
                                $branchID = $branchRow['BRANCH_ID'];

                                // Count products only for this branch
                                $query = "SELECT COUNT(*) FROM product WHERE BRANCH_ID = '$branchID'";
                                $result = mysqli_query($db, $query) or die(mysqli_error($db));
                                $row = mysqli_fetch_array($result);
                                echo "$row[0]"; 
                                ?> Record(s)
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="col-md-3">
<div class="col-md-12 mb-3">
      <a href="Create_po.php" style="text-decoration: none;">
        <div class="card border-left-danger shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-0">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">P.O.</div>
                <div class="h6 mb-0 font-weight-bold text-gray-800">
                  <?php 
                  $query = "SELECT COUNT(*) FROM purchase_orders ";
                  $result = mysqli_query($db, $query) or die(mysqli_error($db));
                  while ($row = mysqli_fetch_array($result)) {
                      echo "$row[0]";
                  }
                  ?> Record(s)
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-truck fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>
  </div>


<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>    
<br>     
<br>
<br>
<br>

<?php
include '../includes/footer.php';
?>