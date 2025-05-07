<?php 
require_once('redirect.php');
allowOnlyAdmin();
?>

<?php
include '../includes/connection.php';


// Ensure user is logged in
if (!isset($_SESSION['MEMBER_ID'])) {
    header("Location: login.php"); // Redirect to the login page
    exit(); // Stop script execution
}

// Fetch user role
$querya = "SELECT u.TYPE_ID FROM users u WHERE u.ID = " . intval($_SESSION['MEMBER_ID']);
$resulta = mysqli_query($db, $querya) or die(mysqli_error($db));

if ($row = mysqli_fetch_assoc($resulta)) {
    $userTypeID = $row['TYPE_ID'];

    // Sidebar inclusion based on user role (1 = Admin, 2 = Cashier, 3 = Manager)
    if ($userTypeID == 1) {
        include '../includes/sidebar.php';
    } elseif ($userTypeID == 2) {
        include '../includes/sidebar_cashier.php';
    } elseif ($userTypeID == 3) {
        include '../includes/sidebar_manager.php';
    } else {
        die('Unauthorized User Type');
    }
}

// Restrict non-admin access
$query = 'SELECT ID, t.TYPE FROM users u JOIN type t ON t.TYPE_ID = u.TYPE_ID WHERE ID = ' . $_SESSION['MEMBER_ID'];
$result = mysqli_query($db, $query) or die(mysqli_error($db));

while ($row = mysqli_fetch_assoc($result)) {
    if ($row['TYPE'] == 'User') {
        echo '<script type="text/javascript">
                alert("Restricted Page! You will be redirected to POS");
                window.location = "pos.php";
              </script>';
        exit();
    }
}

// Ensure valid supplier ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid Supplier ID.');
}

$id = intval($_GET['id']);

// Fetch supplier details
$query = "SELECT e.SUPPLIER_ID, e.COMPANY_NAME, e.PHONE_NUMBER, e.LOCATION_ID, l.PROVINCE, l.CITY 
          FROM supplier e 
          LEFT JOIN location l ON e.LOCATION_ID = l.LOCATION_ID 
          WHERE e.SUPPLIER_ID = $id";
$result = mysqli_query($db, $query) or die(mysqli_error($db));

if ($row = mysqli_fetch_assoc($result)) {
    $zz = $row['SUPPLIER_ID'];
    $a = $row['COMPANY_NAME'];
    $b = $row['PROVINCE'];
    $c = $row['CITY'];
    $d = $row['PHONE_NUMBER'];
    $location_id = $row['LOCATION_ID'];
} else {
    die('Supplier not found.');
}

// Fetch all locations for the dropdown
$location_query = "SELECT LOCATION_ID, PROVINCE, CITY FROM location";
$location_result = mysqli_query($db, $location_query) or die(mysqli_error($db));
?>

<!-- Include the City.js library -->
<script src="../js/city.js"></script>

<!-- Script to initialize province and city dropdowns -->
<script>
  window.onload = function() {
    var $ = new City();

    // Initialize province and city dropdowns
    $.showProvinces("#province");
    $.showCities("#city");

    // Set selected province and city
    var selectedProvince = "<?php echo $b; ?>";
    var selectedCity = "<?php echo $c; ?>";

    if (selectedProvince && selectedCity) {
      setTimeout(function() {
        document.getElementById('province').value = selectedProvince;
        document.getElementById('city').value = selectedCity;
      }, 500); // Delay ensures dropdowns are populated
    }
  };
</script>
<center>
    <div class="card shadow mb-4 col-xs-12 col-md-8 border-bottom-primary">
        <div class="card-header py-3">
            <h4 class="m-2 font-weight-bold text-primary">Edit Supplier</h4>
            <hr>
            <div class="card-body">
                <form role="form" method="post" action="sup_edit1.php">
                    <input type="hidden" name="id" value="<?php echo $zz; ?>" />

                    <div class="form-group row text-left">
                        <div class="col-sm-3" style="padding-top: 5px;">Company Name:</div>
                        <div class="col-sm-9">
                            <input class="form-control" placeholder="Company Name" name="name" value="<?php echo htmlspecialchars($a); ?>" required>
                        </div>
                    </div>

                    <div class="form-group row text-left">
                        <div class="col-sm-3" style="padding-top: 5px;">Phone Number:</div>
                        <div class="col-sm-9">
                            <input class="form-control" placeholder="Phone Number" name="phone" value="<?php echo htmlspecialchars($d); ?>" required>
                        </div>
                    </div>

                    <div class="form-group row text-left">
                        <div class="col-sm-3" style="padding-top: 5px;">Province:</div>
                        <div class="col-sm-9">
                            <select class="form-control" id="province" name="province" required>
                                <option value="" disabled selected hidden>Seelect Province</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row text-left">
                        <div class="col-sm-3" style="padding-top: 5px;">City / Municipality:</div>
                        <div class="col-sm-9">
                            <select class="form-control" id="city" name="city" required>
                                <option value="" disabled selected hidden>Select City</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-warning btn-block"><i class="fa fa-edit fa-fw"></i> Update</button>
                        </div>
                        <div class="col-md-6">
                            <a type="button" class="btn btn-danger btn-block" href="sup_del.php?action=delete&id=<?php echo $zz; ?>" onclick="return confirm('Are you sure you want to delete this supplier?')">
                                <i class="fas fa-trash fa-fw"></i> Delete
                            </a>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a type="button" class="btn btn-primary bg-gradient-primary btn-block" href="supplier.php">
                            <i class="fas fa-flip-horizontal fa-fw fa-share"></i> Back
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</center>

<?php
include '../includes/footer.php';
?>
