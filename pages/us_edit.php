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


<?php
 $zz = intval($_GET['id']); // Get user ID from URL or session

 // Fetch user status from the database
 $query = "SELECT STATUS FROM users WHERE ID = $zz";
 $result = mysqli_query($db, $query);
 
 if ($row = mysqli_fetch_assoc($result)) {
  $currentStatus = ($row['STATUS'] == "Enable" || $row['STATUS'] == 1) ? "Enable" : "Disable";
} else {
     $currentStatus = "Enable"; // Default value in case of error
 }
// JOB SELECT OPTION TAB
$sql = "SELECT DISTINCT TYPE, TYPE_ID FROM type";
$result = mysqli_query($db, $sql) or die ("Bad SQL: $sql");

$opt = "<select class='form-control' name='type'>";
  while ($row = mysqli_fetch_assoc($result)) {
    $opt .= "<option value='".$row['TYPE_ID']."'>".$row['TYPE']."</option>";
  }

  

$opt .= "</select>";
$id = $_GET['id'];
$query = "SELECT ID, e.FIRST_NAME, e.LAST_NAME, e.GENDER, USERNAME, e.EMAIL, 
                  PHONE_NUMBER, j.JOB_TITLE, e.HIRED_DATE, t.TYPE, l.PROVINCE, 
                  l.CITY, b.BRANCH_NAME, u.STATUS
           FROM users u
           JOIN employee e ON u.EMPLOYEE_ID = e.EMPLOYEE_ID
           JOIN job j ON e.JOB_ID = j.JOB_ID
           JOIN location l ON e.LOCATION_ID = l.LOCATION_ID
           JOIN type t ON u.TYPE_ID = t.TYPE_ID
           JOIN branches b ON e.BRANCH_ID = b.BRANCH_ID
           WHERE ID = $id";
$result = mysqli_query($db, $query) or die(mysqli_error($db));

while ($row = mysqli_fetch_array($result)) {  
    $zz = $row['ID'];
    $a = $row['FIRST_NAME'];
    $b = $row['LAST_NAME'];
    $c = $row['GENDER'];
    $d = $row['USERNAME'];
    $f = $row['EMAIL'];
    $g = $row['PHONE_NUMBER'];
    $h = $row['JOB_TITLE'];
    $i = $row['HIRED_DATE'];
    $j = $row['PROVINCE'];
    $k = $row['CITY'];
    $l = $row['TYPE'];
    $m = $row['BRANCH_NAME'];
    $status = $row['STATUS']; // Fetch user status
}

            $id = $_GET['id'];
            // Fetch branch options
$sqlBranch = "SELECT BRANCH_ID, BRANCH_NAME FROM branches ORDER BY BRANCH_NAME ASC";
$resultBranch = mysqli_query($db, $sqlBranch) or die("Bad SQL: $sqlBranch");

$branchOptions = "<select class='form-control' name='branch_id' required>
        <option value='' disabled hidden>Select Branch</option>";
while ($row = mysqli_fetch_assoc($resultBranch)) {
   
    $branchOptions .= "<option value='".$row['BRANCH_ID']."' $m>".$row['BRANCH_NAME']."</option>";
}
$branchOptions .= "</select>";
      ?>

  <center><div class="card shadow mb-4 col-xs-12 col-md-8 border-bottom-primary">
            <div class="card-header py-3">
              <h4 class="m-2 font-weight-bold text-primary">Edit User Account</h4>
              <hr>
            <div class="card-body">
            <form role="form" method="post" action="us_edit1.php">
              <input type="hidden" name="id" value="<?php echo $zz; ?>" />

              <div class="form-group row text-left">
                <div class="col-sm-3" style="padding-top: 5px;">
                 First Name:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="First Name" name="firstname" value="<?php echo $a; ?>" required>
                </div>
              </div>
              <div class="form-group row text-left">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Last Name:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Last Name" name="lastname" value="<?php echo $b; ?>" required>
                </div>
              </div>
              <div class="form-group row text-left">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Gender:
                </div>
                <div class="col-sm-9">
                <select class='form-control' name='gender' required>
                  <option value="" disabled hidden>Select Gender</option>
                  <option value="Male" <?php if ($c == "Male") echo "selected"; ?>>Male</option>
                  <option value="Female" <?php if ($c == "Female") echo "selected"; ?>>Female</option>
                </select>
                </div>
              </div>
              <div class="form-group row text-left">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Username:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Username" name="username" value="<?php echo $d; ?>" >
                </div>
              </div>

              <div class="form-group row text-left">
                <div class="col-sm-3">Branch:</div>
                <div class="col-sm-9">
                    <?php echo $branchOptions; ?>
                </div>
            </div>

              

              <div class="form-group row text-left">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Email:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Email" name="email" value="<?php echo $f; ?>" required>
                </div>
              </div>
              <div class="form-group row text-left">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Contact #:
                </div>
                <div class="col-sm-9">
                   <input class="form-control" placeholder="Contact #" name="phone" value="<?php echo $g; ?>" required>
                </div>
              </div>
              <div class="form-group row text-left">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Hired Date:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Hired Date" name="hireddate" value="<?php echo $i; ?>" required>
                </div>
              </div>
              <div class="form-group row text-left">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Province:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Province" name="province" value="<?php echo $j; ?>" required>
                </div>
              </div>
              <div class="form-group row text-left">
                <div class="col-sm-3" style="padding-top: 5px;">
                 City / Municipality:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="City / Municipality" name="city" value="<?php echo $k; ?>" required>
                </div>
              </div>
              <div class="form-group row text-left">
                <div class="col-sm-3" style="padding-top: 5px;">
                  Account Type:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Account Type" name="type" value="<?php echo $l; ?>" readonly>
                </div>
              </div>
              <input type="hidden" name="status" value="<?php echo $status; ?>">

              </div>  
              <button type="button" class="btn btn-<?php echo ($currentStatus === "Enable") ? "danger" : "success"; ?> btn-block" 
        onclick="toggleUserStatus(<?php echo $zz; ?>, '<?php echo htmlspecialchars($currentStatus, ENT_QUOTES, 'UTF-8'); ?>')">
    <i class="fas fa-user-slash"></i> <?php echo ($currentStatus === "Enable") ? "Disable" : "Enable"; ?> User
</button>


            <button type="button" class="btn btn-info btn-block" onclick="changePassword(<?php echo $zz; ?>)">
          <i class="fas fa-key"></i> Change Password
        </button>
        <button type="button" class="btn btn-success btn-block" 
    onclick="if(confirm('Are you sure you want to reset this user\'s password?')) 
             window.location.href='reset_password.php?id=<?php echo $zz; ?>';">
    <i class="fas fa-key"></i> Reset Password
</button>


                <button type="submit" class="btn btn-warning btn-block"><i class="fa fa-edit fa-fw"></i>Update</button>
              </form>  
    <a  type="button" class="btn btn-primary bg-gradient-primary btn-block" href="user.php?"> <i class="fas fa-flip-horizontal fa-fw fa-share"></i> Back </a> 
            </div>
            
          </div></center>

<script>
function toggleUserStatus(userId, currentStatus) {
    let cleanedStatus = currentStatus.trim().toLowerCase(); 
    let newStatus = (cleanedStatus === "Enable") ? "Disable" : "Enable";
    let confirmMessage = `Are you sure you want to ${newStatus.toLowerCase()} this user?`;

    if (confirm(confirmMessage)) {
        window.location.href = "us_toggle_status.php?id=" + encodeURIComponent(userId) + "&status=" + encodeURIComponent(newStatus);
    }
}


function changePassword(userID) { 
    window.location.href = "change_password.php?id=" + userID;
}
</script>

<?php
include'../includes/footer.php';
?>