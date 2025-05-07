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
// Check user type
$query = 'SELECT ID, t.TYPE
          FROM users u
          JOIN type t ON t.TYPE_ID = u.TYPE_ID
          WHERE ID = ' . $_SESSION['MEMBER_ID'] . '';
$result = mysqli_query($db, $query) or die(mysqli_error($db));

while ($row = mysqli_fetch_assoc($result)) {
    $Aa = $row['TYPE'];
    if ($Aa == 'User') {
        echo '<script type="text/javascript">
                  alert("Restricted Page! You will be redirected to POS");
                  window.location = "pos.php";
              </script>';
        exit;
    }
}
?>

<!-- Page Content -->
<div class="col-lg-12">
    <?php
    $fname = $_POST['firstname'];
    $lname = $_POST['lastname'];
    $pn = $_POST['phonenumber'];
    $branch = $_POST['branch'];
    $province = $_POST['province'];
    $city = $_POST['city'];

    switch ($_GET['action']) {
        case 'add':
            $query = "INSERT INTO customer
                      (CUST_ID, FIRST_NAME, LAST_NAME, PHONE_NUMBER, BRANCH_ID, PROVINCE, CITY)
                      VALUES (NULL, '{$fname}', '{$lname}', '{$pn}', '{$branch}', '{$province}', '{$city}')";
            mysqli_query($db, $query) or die('Error in updating Database');
            break;
    }
    ?>
    <script type="text/javascript">
        window.location = "pos.php";
    </script>
</div>

<?php
include '../includes/footer.php';
?>
