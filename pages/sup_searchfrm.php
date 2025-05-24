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
?>
<?php 
    $query = 'SELECT SUPPLIER_ID, COMPANY_NAME, l.PROVINCE, l.CITY, PHONE_NUMBER FROM supplier e join location l on e.LOCATION_ID = l.LOCATION_ID WHERE e.SUPPLIER_ID ='.$_GET['id'];
    $result = mysqli_query($db, $query) or die(mysqli_error($db));
      while($row = mysqli_fetch_array($result))
      {   
        $zz= $row['SUPPLIER_ID'];
        $a= $row['COMPANY_NAME'];
        $b=$row['PROVINCE'];
        $c=$row['CITY'];
        $d=$row['PHONE_NUMBER'];
      }
      $id = $_GET['id'];
  ?>
  
  <div class="d-flex justify-content-center">
    <div class="card shadow-lg border-0 rounded-4 col-12 col-md-8">
        <div class="card-header bg-primary text-white text-center py-3">
            <h4 class="fw-bold"><i class="fas fa-building me-2"></i> Supplier's Detail</h4>
        </div>
        <div class="card-body px-4 py-4">
            <div class="row mb-3">
                <div class="col-sm-3 fw-bold text-primary">
                    <i class="fas fa-industry me-2"></i> Company Name
                </div>
                <div class="col-sm-9">: <?php echo $a; ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3 fw-bold text-primary">
                    <i class="fas fa-map-marked-alt me-2"></i> Province
                </div>
                <div class="col-sm-9">: <?php echo $b; ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3 fw-bold text-primary">
                    <i class="fas fa-city me-2"></i> City
                </div>
                <div class="col-sm-9">: <?php echo $c; ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3 fw-bold text-primary">
                    <i class="fas fa-phone me-2"></i> Phone Number
                </div>
                <div class="col-sm-9">: <?php echo $d; ?></div>
            </div>
        </div>

        <div class="card-footer bg-light text-center">
            <a href="supplier.php" class="btn btn-primary btn-lg w-100">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>
</div>

<?php
include'../includes/footer.php';
?>