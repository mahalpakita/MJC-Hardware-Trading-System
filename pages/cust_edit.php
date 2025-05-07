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
// Fetch customer details
$query = 'SELECT c.CUST_ID, c.FIRST_NAME, c.LAST_NAME, c.PHONE_NUMBER, l.LOCATION_ID, l.PROVINCE, l.CITY, c.BRANCH_ID 
          FROM customer c 
          JOIN location l ON c.LOCATION_ID = l.LOCATION_ID 
          WHERE c.CUST_ID = ' . $_GET['id'];
$result = mysqli_query($db, $query) or die(mysqli_error($db));
while ($row = mysqli_fetch_array($result)) {
    $zz = $row['CUST_ID'];
    $fname = $row['FIRST_NAME'];
    $lname = $row['LAST_NAME'];
    $phone = $row['PHONE_NUMBER'];
    $locationId = $row['LOCATION_ID']; // Fetch LOCATION_ID
    $prov = $row['PROVINCE'];
    $cit = $row['CITY'];
    $branchId = $row['BRANCH_ID'];
}

// Fetch all branches for the dropdown
$branchQuery = 'SELECT BRANCH_ID, BRANCH_NAME FROM branches';
$branchResult = mysqli_query($db, $branchQuery) or die(mysqli_error($db));
?>

<center>
    <div class="card shadow mb-4 col-xs-12 col-md-8 border-bottom-primary">
        <div class="card-header py-3">
            <h4 class="m-2 font-weight-bold text-primary">Edit Customer</h4>
            <hr>
            <div class="card-body">

                <form role="form" method="post" action="cust_edit1.php">
                    <input type="hidden" name="id" value="<?php echo $zz; ?>" />
                    
                    <div class="form-group row text-left">
                        <div class="col-sm-3" style="padding-top: 5px;">First Name:</div>
                        <div class="col-sm-9">
                            <input class="form-control" placeholder="First Name" name="firstname" value="<?php echo $fname; ?>" required>
                        </div>
                    </div>

                    <div class="form-group row text-left">
                        <div class="col-sm-3" style="padding-top: 5px;">Last Name:</div>
                        <div class="col-sm-9">
                            <input class="form-control" placeholder="Last Name" name="lastname" value="<?php echo $lname; ?>" required>
                        </div>
                    </div>

                    <div class="form-group row text-left">
                        <div class="col-sm-3" style="padding-top: 5px;">Contact #:</div>
                        <div class="col-sm-9">
                            <input class="form-control" placeholder="Phone Number" name="phone" value="<?php echo $phone; ?>" required>
                        </div>
                    </div>

                    <div class="form-group row text-left">
                        <div class="col-sm-3" style="padding-top: 5px;">Branch:</div>
                        <div class="col-sm-9">
                            <select class="form-control" id="branch" name="branch" required>
                                <?php
                                mysqli_data_seek($branchResult, 0); // Reset branch result pointer
                                while ($branchRow = mysqli_fetch_assoc($branchResult)) {
                                    $selected = ($branchRow['BRANCH_ID'] == $branchId) ? 'selected' : '';
                                    echo '<option value="' . $branchRow['BRANCH_ID'] . '" ' . $selected . '>' . $branchRow['BRANCH_NAME'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group row text-left">
    <div class="col-sm-3" style="padding-top: 5px;">Current Province:</div>
    <div class="col-sm-9">
        <input type="text" class="form-control" value="<?php echo $prov; ?>" readonly>
    </div>
</div>

<div class="form-group row text-left">
    <div class="col-sm-3" style="padding-top: 5px;">Change Province:</div>
    <div class="col-sm-9">
        <select class="form-control" id="province" name="province">
            <option value="" disabled>Select Province</option>
            <?php
            $queryProvinces = "SELECT DISTINCT PROVINCE FROM location ORDER BY PROVINCE ASC";
            $resultProvinces = mysqli_query($db, $queryProvinces);
            
            while ($rowProv = mysqli_fetch_assoc($resultProvinces)) {
                $selected = ($rowProv['PROVINCE'] == $prov) ? 'selected' : '';
                echo "<option value='" . $rowProv['PROVINCE'] . "' $selected>" . $rowProv['PROVINCE'] . "</option>";
            }
            ?>
        </select>
    </div>
</div>

<div class="form-group row text-left">
    <div class="col-sm-3" style="padding-top: 5px;">Current City:</div>
    <div class="col-sm-9">
        <input type="text" class="form-control" value="<?php echo $cit; ?>" readonly>
    </div>
</div>

<div class="form-group row text-left">
    <div class="col-sm-3" style="padding-top: 5px;">Change City:</div>
    <div class="col-sm-9">
        <select class="form-control" id="city" name="city">
            <option value="" disabled>Select City</option>
            <?php
            $queryCities = "SELECT DISTINCT CITY FROM location WHERE PROVINCE = '$prov' ORDER BY CITY ASC";
            $resultCities = mysqli_query($db, $queryCities);

            while ($rowCity = mysqli_fetch_assoc($resultCities)) {
                $selected = ($rowCity['CITY'] == $cit) ? 'selected' : '';
                echo "<option value='" . $rowCity['CITY'] . "' $selected>" . $rowCity['CITY'] . "</option>";
            }
            ?>
        </select>
    </div>
</div>
                    <button type="submit" class="btn btn-warning btn-block"><i class="fa fa-edit fa-fw"></i> Update</button>
                    <a type="button" class="btn btn-primary bg-gradient-primary btn-block" href="customer.php">
                        <i class="fas fa-flip-horizontal fa-fw fa-share"></i> Back
                    </a>
                </form>

            </div>
        </div>
    </div>
</center>



<?php
include '../includes/footer.php';
?>