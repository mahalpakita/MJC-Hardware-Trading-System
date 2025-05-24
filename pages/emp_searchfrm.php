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

$query = 'SELECT e.EMPLOYEE_ID, e.FIRST_NAME, e.LAST_NAME, e.GENDER, e.EMAIL, e.PHONE_NUMBER, j.JOB_TITLE, e.HIRED_DATE, 
                 l.PROVINCE, l.CITY, b.BRANCH_NAME 
          FROM employee e 
          JOIN location l ON e.LOCATION_ID = l.LOCATION_ID 
          JOIN job j ON j.JOB_ID = e.JOB_ID 
          JOIN branches b ON e.BRANCH_ID = b.BRANCH_ID 
          WHERE e.EMPLOYEE_ID =' . $_GET['id'];
$result = mysqli_query($db, $query) or die(mysqli_error($db));

while ($row = mysqli_fetch_array($result)) {   
    $zz = $row['EMPLOYEE_ID'];
    $i = $row['FIRST_NAME'];
    $ii = $row['LAST_NAME'];
    $iii = $row['GENDER'];
    $a = $row['EMAIL'];
    $b = $row['PHONE_NUMBER'];
    $c = $row['JOB_TITLE'];
    $d = $row['HIRED_DATE'];
    $f = $row['PROVINCE'];
    $g = $row['CITY'];
    $branch = $row['BRANCH_NAME'];
}
$id = $_GET['id'];
?><?php
// Fetch employee details
$query = 'SELECT e.EMPLOYEE_ID, e.FIRST_NAME, e.LAST_NAME, e.GENDER, e.EMAIL, e.PHONE_NUMBER, 
                 j.JOB_TITLE, e.HIRED_DATE, l.PROVINCE, l.CITY, b.BRANCH_NAME 
          FROM employee e 
          JOIN location l ON e.LOCATION_ID = l.LOCATION_ID 
          JOIN job j ON j.JOB_ID = e.JOB_ID 
          JOIN branches b ON e.BRANCH_ID = b.BRANCH_ID 
          WHERE e.EMPLOYEE_ID = ' . intval($_GET['id']);

$result = mysqli_query($db, $query) or die(mysqli_error($db));
$row = mysqli_fetch_array($result);

$full_name = $row['FIRST_NAME'] . ' ' . $row['LAST_NAME'];
$gender = $row['GENDER'];
$email = $row['EMAIL'];
$contact = $row['PHONE_NUMBER'];
$role = $row['JOB_TITLE'];
$hired_date = $row['HIRED_DATE'];
$address = $row['CITY'] . ', ' . $row['PROVINCE'];
$branch = $row['BRANCH_NAME'];
?>

<div class="container mt-5">
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white text-center py-4">
            <h3 class="fw-bold mb-0">Employee Details</h3>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <h6 class="text-muted"><i class="fas fa-user"></i> Full Name</h6>
                    <p class="fw-semibold"><?php echo $full_name; ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted"><i class="fas fa-venus-mars"></i> Gender</h6>
                    <p class="fw-semibold"><?php echo $gender; ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted"><i class="fas fa-envelope"></i> Email</h6>
                    <p class="fw-semibold"><?php echo $email; ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted"><i class="fas fa-phone"></i> Contact</h6>
                    <p class="fw-semibold"><?php echo $contact; ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted"><i class="fas fa-briefcase"></i> Role</h6>
                    <p class="fw-semibold"><?php echo $role; ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted"><i class="fas fa-calendar-alt"></i> Hired Date</h6>
                    <p class="fw-semibold"><?php echo $hired_date; ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted"><i class="fas fa-map-marker-alt"></i> Address</h6>
                    <p class="fw-semibold"><?php echo $address; ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted"><i class="fas fa-store"></i> Branch</h6>
                    <p class="fw-semibold"><?php echo $branch; ?></p>
                </div>
            </div>
        </div>
        <div class="card-footer text-center py-3">
    <a 
        <?php echo ($userTypeID == 1) ? 'href="employee.php"' : 'href="employee_manager.php"'; ?> 
        class="btn btn-lg btn-primary px-4">
        <i class="fas fa-arrow-left me-2"></i>Back to Employees
    </a>
</div>

    </div>
</div>

<?php
include '../includes/footer.php';
?>
