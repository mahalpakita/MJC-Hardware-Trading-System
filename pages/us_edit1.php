<?php 
require_once('redirect.php');
allowOnlyAdmin();
?>
<?php
include('../includes/connection.php');

$zz = $_POST['id'];
$a = $_POST['firstname'];
$b = $_POST['lastname'];
$c = $_POST['gender'];
$d = $_POST['username'];
$f = $_POST['email'];
$g = $_POST['phone'];
$i = $_POST['hireddate'];
$j = $_POST['province'];
$k = $_POST['city'];
$status = isset($_POST['status']) ? $_POST['status'] : 'Active'; // Ensure status is set

// Debugging: Check received POST data
// var_dump($_POST); exit;

// Prepare statement to prevent SQL injection
$query = "UPDATE users u 
          JOIN employee e ON e.EMPLOYEE_ID = u.EMPLOYEE_ID
          JOIN location l ON l.LOCATION_ID = e.LOCATION_ID
          SET e.FIRST_NAME = ?, 
              e.LAST_NAME = ?, 
              e.GENDER = ?, 
              u.USERNAME = ?, 
              e.EMAIL = ?,
              l.PROVINCE = ?, 
              l.CITY = ?, 
              e.PHONE_NUMBER = ?, 		
              e.HIRED_DATE = ?,
              u.STATUS = ? 
          WHERE u.ID = ?";

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "ssssssssssi", $a, $b, $c, $d, $f, $j, $k, $g, $i, $status, $zz);
$result = mysqli_stmt_execute($stmt);

if ($result) {
    echo "<script>
            alert(\"You've updated your account successfully.\");
            window.location = 'user.php';
          </script>";
} else {
    echo "<script>
            alert('Update failed: " . mysqli_error($db) . "');
            window.history.back();
          </script>";
}
?>
