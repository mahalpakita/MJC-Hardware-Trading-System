<?php 
require_once('redirect.php');
allowOnlyAdmin();
?>


<?php
include'../includes/connection.php';
?>

<?php
$fname = $_POST['firstname'];
$lname = $_POST['lastname'];
$gen = $_POST['gender'];
$email = $_POST['email'];
$phone = $_POST['phonenumber'];
$jobb = $_POST['jobs'];
$hdate = $_POST['hireddate'];
$prov = $_POST['provincee'];
$cit = $_POST['cityy'];
$branch = $_POST['branch'];  // Added for branch selection

// Insert location data
mysqli_query($db,"INSERT INTO location
                  (LOCATION_ID, PROVINCE, CITY)
                  VALUES (Null,'$prov','$cit')");

// Insert employee data including branch
mysqli_query($db,"INSERT INTO employee
                  (EMPLOYEE_ID, FIRST_NAME, LAST_NAME, GENDER, EMAIL, PHONE_NUMBER, JOB_ID, HIRED_DATE, LOCATION_ID, BRANCH_ID)
                  VALUES (Null,'{$fname}','{$lname}','{$gen}','{$email}','{$phone}','{$jobb}','{$hdate}',(SELECT MAX(LOCATION_ID) FROM location), '{$branch}')");

// Redirect to employee page
header('location:employee.php');
?>
