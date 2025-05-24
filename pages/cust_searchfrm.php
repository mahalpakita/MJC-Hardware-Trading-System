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


<?php 

// Query to get customer details including branch and location
$query = 'SELECT c.CUST_ID, c.FIRST_NAME, c.LAST_NAME, c.PHONE_NUMBER, 
                 b.BRANCH_NAME, l.PROVINCE, l.CITY 
          FROM customer c 
          JOIN branches b ON c.BRANCH_ID = b.BRANCH_ID 
          JOIN location l ON c.LOCATION_ID = l.LOCATION_ID 
          WHERE c.CUST_ID = '.$_GET['id'];
$result = mysqli_query($db, $query) or die(mysqli_error($db));

if ($row = mysqli_fetch_array($result)) {   
    $zz = $row['CUST_ID'];
    $i = $row['FIRST_NAME'];
    $a = $row['LAST_NAME'];
    $b = $row['PHONE_NUMBER'];
    $branch = $row['BRANCH_NAME'];
    $province = $row['PROVINCE'];
    $city = $row['CITY'];

   
} else {
    echo "No data found for customer ID: ".$_GET['id'];
}

?>
<div class="d-flex justify-content-center">
    <div class="card shadow-lg border-0 rounded-4 col-12 col-md-8">
        <div class="card-header bg-primary text-white text-center py-3">
            <h4 class="fw-bold"><i class="fas fa-user-circle me-2"></i> Customer's Detail</h4>
        </div>
        <div class="card-body px-4 py-4">
            <div class="row mb-3">
                <div class="col-sm-3 fw-bold text-primary">
                    <i class="fas fa-user me-2"></i> Full Name
                </div>
                <div class="col-sm-9">: <?php echo $i . " " . $a; ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3 fw-bold text-primary">
                    <i class="fas fa-phone"></i> Contact #
                </div>
                <div class="col-sm-9">: <?php echo $b; ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3 fw-bold text-primary">
                    <i class="fas fa-store me-2"></i> Branch
                </div>
                <div class="col-sm-9">: <?php echo $branch; ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3 fw-bold text-primary">
                    <i class="fas fa-map-marker-alt me-2"></i> Location
                </div>
                <div class="col-sm-9">: <?php echo $city . ", " . $province; ?></div>
            </div>
        </div>

        <div class="card-footer bg-light text-center">
            <a href="customer.php" class="btn btn-primary btn-lg w-100">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
        </div>
    </div>
</div>



<?php
include '../includes/footer.php';
?>
