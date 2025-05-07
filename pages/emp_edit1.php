<?php 
require_once('redirect.php');
allowOnlyCashier();
?>

<?php
include('../includes/connection.php');

$id = $_POST['id'];
$fname = $_POST['firstname'];
$lname = $_POST['lastname'];
$gen = $_POST['gender'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$hdate = $_POST['hireddate'];
$prov = $_POST['provincee'];
$cit = $_POST['cityy'];
$branch = $_POST['branch'];  // Capture the selected branch

// Update employee details, including the branch
$query = 'UPDATE employee e
          JOIN location l ON l.LOCATION_ID = e.LOCATION_ID
          SET FIRST_NAME = ?, LAST_NAME = ?, GENDER = ?, EMAIL = ?, PHONE_NUMBER = ?, HIRED_DATE = ?, l.PROVINCE = ?, l.CITY = ?, e.BRANCH_ID = ?
          WHERE EMPLOYEE_ID = ?';

$stmt = $db->prepare($query);
$stmt->bind_param('ssssssssii', $fname, $lname, $gen, $email, $phone, $hdate, $prov, $cit, $branch, $id);

if ($stmt->execute()) {
    echo '<script type="text/javascript">
            alert("Employee updated successfully.");
            window.location = "employee.php";
          </script>';
} else {
    echo '<script type="text/javascript">
            alert("Failed to update employee.");
            window.location = "employee.php";
          </script>';
}

$stmt->close();
?>
