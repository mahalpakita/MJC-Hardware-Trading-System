<?php
include '../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $custId = $_POST['id'];
    $locationId = $_POST['location_id']; // Current LOCATION_ID
    $fname = mysqli_real_escape_string($db, $_POST['firstname']);
    $lname = mysqli_real_escape_string($db, $_POST['lastname']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $branchId = mysqli_real_escape_string($db, $_POST['branch']);
    $province = mysqli_real_escape_string($db, $_POST['province']);
    $city = mysqli_real_escape_string($db, $_POST['city']);

    // Check if the selected province and city already exist
    $locationQuery = "SELECT LOCATION_ID FROM location WHERE PROVINCE = '$province' AND CITY = '$city'";
    $locationResult = mysqli_query($db, $locationQuery);

    if (mysqli_num_rows($locationResult) > 0) {
        // Use existing location
        $locationRow = mysqli_fetch_assoc($locationResult);
        $newLocationId = $locationRow['LOCATION_ID'];
    } else {
        // Insert new location
        $insertLocationQuery = "INSERT INTO location (PROVINCE, CITY) VALUES ('$province', '$city')";
        if (mysqli_query($db, $insertLocationQuery)) {
            $newLocationId = mysqli_insert_id($db); // Get the new LOCATION_ID
        } else {
            die('Error inserting new location: ' . mysqli_error($db));
        }
    }

    // Update customer details
    $updateCustomerQuery = "UPDATE customer 
                            SET FIRST_NAME = '$fname', 
                                LAST_NAME = '$lname', 
                                PHONE_NUMBER = '$phone', 
                                BRANCH_ID = '$branchId', 
                                LOCATION_ID = '$newLocationId' 
                            WHERE CUST_ID = '$custId'";
    if (mysqli_query($db, $updateCustomerQuery)) {
        echo '<script type="text/javascript">
                alert("Customer updated successfully!");
                window.location = "customer.php";
              </script>';
    } else {
        die('Error updating customer: ' . mysqli_error($db));
    }
}
?>