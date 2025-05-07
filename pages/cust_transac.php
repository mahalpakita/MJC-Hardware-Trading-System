<?php
include '../includes/connection.php';
session_start();

if (!isset($_SESSION['MEMBER_ID'])) {
    header("Location: login.php"); // Redirect to the login page
    exit(); // Stop script execution
  }
// Fetch user role and branch ID
$queryUser = "SELECT TYPE_ID, BRANCH_ID FROM users WHERE ID = " . intval($_SESSION['MEMBER_ID']);
$resultUser = mysqli_query($db, $queryUser) or die(mysqli_error($db));

if ($rowUser = mysqli_fetch_assoc($resultUser)) {
    $userTypeID = $rowUser['TYPE_ID']; // 1 = Admin, 2 = Manager, 3 = Cashier
    $userBranchID = $rowUser['BRANCH_ID']; // Manager's branch
} else {
    die('User not found in database.');
}

// Get form data
$fname = $_POST['firstname'];
$lname = $_POST['lastname'];
$pn = $_POST['phonenumber'];
$prov = $_POST['province']; 
$city = $_POST['city'];     

// Assign branch based on role
if ($userTypeID == 1) { 
    // Admin can select any branch
    $branch_id = $_POST['branch'];
} else { 
    // Manager or other roles must use their assigned branch
    $branch_id = $userBranchID;
}

// Perform the action
if ($_GET['action'] == 'add') {  
    // Insert location first
    $query1 = "INSERT INTO location (LOCATION_ID, PROVINCE, CITY) VALUES (NULL, '$prov', '$city')";
    mysqli_query($db, $query1) or die('Error in updating Database (Location)');

    // Insert customer with the new location ID
    $query2 = "INSERT INTO customer 
               (CUST_ID, FIRST_NAME, LAST_NAME, PHONE_NUMBER, LOCATION_ID, BRANCH_ID) 
               VALUES (NULL, '{$fname}', '{$lname}', '{$pn}', 
                       (SELECT MAX(LOCATION_ID) FROM location), '{$branch_id}')";
    mysqli_query($db, $query2) or die('Error in updating Database (Customer)');
}

?>
<script type="text/javascript">
    window.location = "customer.php";
</script>
