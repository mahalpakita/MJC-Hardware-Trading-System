    <?php 
require_once('redirect.php');
allowOnlyAdmin();
?>

    <?php
    include '../includes/connection.php';
   

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

 <script>
    const roleField = document.getElementById('role');
    const branchField = document.getElementById('branch');

    roleField.addEventListener('change', function () {
        // Clear branch dropdown
        branchField.innerHTML = '';

        // Check if the selected role is Admin
        if (roleField.value == '3') { // Role ID 3 = Admin
            // Add a "null" option for Admin
            branchField.innerHTML += '<option value="" selected>None</option>';
        } else {
            // Add branch options for other roles
            branchField.innerHTML += '<option value="1">Branch 1</option>';
            branchField.innerHTML += '<option value="2">Branch 2</option>';
        }
    });
    // Trigger the change event on page load to handle the pre-selected role
    roleField.dispatchEvent(new Event('change'));
</script>


<?php
    // JOB SELECT OPTION TAB
    $sql = "SELECT DISTINCT JOB_TITLE, JOB_ID FROM job";
    $result = mysqli_query($db, $sql) or die ("Bad SQL: $sql");

    $roleOptions = "<select class='form-control' name='jobs' required>
                    <option value='' disabled>Select Role</option>";
    while ($row = mysqli_fetch_assoc($result)) {
        $roleOptions .= "<option value='".$row['JOB_ID']."'>".$row['JOB_TITLE']."</option>";
    }
    $roleOptions .= "</select>";

    // BRANCH SELECT OPTION TAB
    $sqlBranch = "SELECT DISTINCT BRANCH_NAME, BRANCH_ID FROM branches";
    $resultBranch = mysqli_query($db, $sqlBranch) or die ("Bad SQL: $sqlBranch");

    $branchOptions = "<select class='form-control' name='branch' required>
                    <option value='' disabled>Select Branch</option>";
    while ($row = mysqli_fetch_assoc($resultBranch)) {
        $branchOptions .= "<option value='".$row['BRANCH_ID']."'>".$row['BRANCH_NAME']."</option>";
    }
    $branchOptions .= "</select>";

    $query = 'SELECT EMPLOYEE_ID, FIRST_NAME, LAST_NAME, EMAIL, PHONE_NUMBER, j.JOB_ID, j.JOB_TITLE, HIRED_DATE, l.PROVINCE, l.CITY, b.BRANCH_NAME, b.BRANCH_ID 
            FROM employee e 
            JOIN location l ON l.LOCATION_ID=e.LOCATION_ID 
            JOIN job j ON j.JOB_ID=e.JOB_ID 
            JOIN branches b ON b.BRANCH_ID=e.BRANCH_ID 
            WHERE EMPLOYEE_ID ='.$_GET['id'];
    $result = mysqli_query($db, $query) or die(mysqli_error($db));
    while ($row = mysqli_fetch_array($result)) {
        $zz = $row['EMPLOYEE_ID'];
        $fname = $row['FIRST_NAME'];
        $lname = $row['LAST_NAME'];
        $email = $row['EMAIL'];
        $phone = $row['PHONE_NUMBER'];
        $jobId = $row['JOB_ID'];
        $jobb = $row['JOB_TITLE'];
        $hdate = $row['HIRED_DATE'];
        $prov = $row['PROVINCE'];
        $cit = $row['CITY'];
        $branchName = $row['BRANCH_NAME']; // Branch Name
        $branchId = $row['BRANCH_ID'];     // Branch ID
    }
    $id = $_GET['id'];
    ?>
    <center>
        
<div class="card shadow mb-4 col-xs-12 col-md-8 border-bottom-primary">
    <div class="card-header py-3">
        <h4 class="m-2 font-weight-bold text-primary">Edit Employee</h4>
        <hr>
        <div class="card-body">

            <form role="form" method="post" action="emp_edit1.php">
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
                <div class="col-sm-3" style="padding-top: 5px;">Gender:</div>
                <div class="col-sm-9">
                    <select class="form-control" name="gender" required>
                    <option value="Male" <?php if ('Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ('Female') echo 'selected'; ?>>Female</option>
                    </select>
                </div>
                </div>

                <div class="form-group row text-left">
                    <div class="col-sm-3" style="padding-top: 5px;">Email:</div>
                    <div class="col-sm-9">
                        <input class="form-control" placeholder="Email" name="email" value="<?php echo $email; ?>" required>
                    </div>
                </div>

                <div class="form-group row text-left">
                    <div class="col-sm-3" style="padding-top: 5px;">Contact #:</div>
                    <div class="col-sm-9">
                        <input class="form-control" placeholder="Phone Number" name="phone" value="<?php echo $phone; ?>" required>
                    </div>
                </div>

            <div class="form-group row text-left">
                <div class="col-sm-3" style="padding-top: 5px;">Role:</div>
                <div class="col-sm-9">
                    <select class="form-control" id="role" name="jobs" required>
                        <option value="1" <?php if ($jobb == 'Manager') echo 'selected'; ?>>Manager</option>
                        <option value="2" <?php if ($jobb == 'Cashier') echo 'selected'; ?>>Cashier</option>
                        <option value="3" <?php if ($jobb == 'Admin') echo 'selected'; ?>>Admin</option>
                    </select>
                </div>
            </div>

                <div class="form-group row text-left">
                    <div class="col-sm-3" style="padding-top: 5px;">Branch:</div>
                    <div class="col-sm-9">
                        <select class="form-control" id="branch" name="branch">
                            <!-- Default branch options -->
                            <option value="1" <?php if (isset($branch) && $branch == 'Branch 1') echo 'selected'; ?>>Branch 1</option>
                            <option value="2" <?php if (isset($branch) && $branch == 'Branch 2') echo 'selected'; ?>>Branch 2</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row text-left">
                    <div class="col-sm-3" style="padding-top: 5px;">Hired Date:</div>
                    <div class="col-sm-9">
                        <input placeholder="Hired Date" type="date" id="FromDate" name="hireddate" value="<?php echo $hdate; ?>" class="form-control" required>
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
        <select class="form-control" id="provincee" name="provincee">
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
        <select class="form-control" id="cityy" name="cityy">
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
                <a type="button" class="btn btn-primary bg-gradient-primary btn-block" href="employee.php">
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
