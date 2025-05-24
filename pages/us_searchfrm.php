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
$query2 = 'SELECT ID, e.FIRST_NAME, e.LAST_NAME, e.GENDER, USERNAME, PASSWORD, e.EMAIL, PHONE_NUMBER, j.JOB_TITLE, e.HIRED_DATE, t.TYPE, l.PROVINCE, l.CITY, u.STATUS
FROM users u
JOIN employee e ON u.EMPLOYEE_ID = e.EMPLOYEE_ID
JOIN job j ON e.JOB_ID = j.JOB_ID
JOIN location l ON e.LOCATION_ID = l.LOCATION_ID
JOIN type t ON u.TYPE_ID = t.TYPE_ID
WHERE ID =' . $_GET['id'];
$result2 = mysqli_query($db, $query2) or die(mysqli_error($db));
  
while ($row = mysqli_fetch_array($result2)) {   
  $zz = $row['ID'];
  $a = $row['FIRST_NAME'];
  $b = $row['LAST_NAME'];
  $c = $row['GENDER'];
  $d = $row['USERNAME'];
  $e = $row['PASSWORD'];
  $f = $row['EMAIL'];
  $g = $row['PHONE_NUMBER'];
  $h = $row['JOB_TITLE'];
  $i = $row['HIRED_DATE'];
  $j = $row['PROVINCE'];
  $k = $row['CITY'];
  $l = $row['TYPE'];
  $m = $row['STATUS']; // Fetch status
}
$id = $_GET['id'];

?>
          <div class="d-flex justify-content-center">
    <div class="card shadow-lg border-0 rounded-4 col-12 col-md-8">
        <div class="card-header bg-primary text-white text-center py-3">
            <h4 class="fw-bold"><i class="fas fa-user me-2"></i> <?php echo $a; ?>'s Detail</h4>
        </div>
        <div class="card-body px-4 py-4">
            <div class="row mb-3">
                <div class="col-sm-3 fw-bold text-primary">
                    <i class="fas fa-user me-2"></i> Full Name
                </div>
                <div class="col-sm-9">: <?php echo $a . " " . $b; ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3 fw-bold text-primary">
                    <i class="fas fa-venus-mars me-2"></i> Gender
                </div>
                <div class="col-sm-9">: <?php echo $c; ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3 fw-bold text-primary">
                    <i class="fas fa-user-circle me-2"></i> Username
                </div>
                <div class="col-sm-9">: <?php echo $d; ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3 fw-bold text-primary">
                    <i class="fas fa-envelope me-2"></i> Email
                </div>
                <div class="col-sm-9">: <?php echo $f; ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3 fw-bold text-primary">
                    <i class="fas fa-phone me-2"></i> Contact #
                </div>
                <div class="col-sm-9">: <?php echo $g; ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3 fw-bold text-primary">
                    <i class="fas fa-user-tag me-2"></i> Role
                </div>
                <div class="col-sm-9">: <?php echo $h; ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3 fw-bold text-primary">
                    <i class="fas fa-calendar-alt me-2"></i> Hired Date
                </div>
                <div class="col-sm-9">: <?php echo $i; ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3 fw-bold text-primary">
                    <i class="fas fa-map-marker-alt me-2"></i> Province
                </div>
                <div class="col-sm-9">: <?php echo $j; ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3 fw-bold text-primary">
                    <i class="fas fa-city me-2"></i> City / Municipality
                </div>
                <div class="col-sm-9">: <?php echo $k; ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3 fw-bold text-primary">
                    <i class="fas fa-key me-2"></i> Account Type
                </div>
                <div class="col-sm-9">: <?php echo $l; ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3 fw-bold text-primary">
                    <i class="fas fa-check-circle me-2"></i> Status
                </div>
                <div class="col-sm-9">: <?php echo $m; ?></div>
            </div>
        </div>

       
       

        <div class="card-footer bg-light text-center">
            <a href="user.php" class="btn btn-primary btn-lg w-100">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>
</div>


<?php
include '../includes/footer.php';
?>