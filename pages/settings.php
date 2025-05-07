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

// JOB SELECT OPTION TAB
$sql = "SELECT DISTINCT TYPE, TYPE_ID FROM type";
$result = mysqli_query($db, $sql) or die ("Bad SQL: $sql");

$opt = "<select class='form-control' name='type'>";
  while ($row = mysqli_fetch_assoc($result)) {
    $opt .= "<option value='".$row['TYPE_ID']."'>".$row['TYPE']."</option>";
  }

$opt .= "</select>";

        $query = "SELECT ID, e.FIRST_NAME, e.LAST_NAME, e.GENDER, USERNAME, e.EMAIL, PHONE_NUMBER, j.JOB_TITLE, e.HIRED_DATE, t.TYPE, l.PROVINCE, l.CITY
                      FROM users u
                      join employee e on u.EMPLOYEE_ID = e.EMPLOYEE_ID
                      join job j on e.JOB_ID=j.JOB_ID
                      join location l on e.LOCATION_ID=l.LOCATION_ID
                      join type t on u.TYPE_ID=t.TYPE_ID
                      WHERE ID =".$_SESSION['MEMBER_ID'];
        $result = mysqli_query($db, $query) or die(mysqli_error($db));
          while($row = mysqli_fetch_array($result))
          {  
                $zz= $row['ID'];
                $a= $row['FIRST_NAME'];
                $b=$row['LAST_NAME'];
                $c=$row['GENDER'];
                $d=$row['USERNAME'];
                $f=$row['EMAIL'];
                $g=$row['PHONE_NUMBER'];
                $h=$row['JOB_TITLE'];
                $i=$row['HIRED_DATE'];
                $j=$row['PROVINCE'];
                $k=$row['CITY'];
                $l=$row['TYPE'];
          }
          $id = isset($_GET['id']) ? $_GET['id'] : null;

      ?>
<center>
<div class="card shadow mb-4 col-xs-12 col-md-8 border-bottom-primary">
            <div class="card-header py-3">
              <h4 class="m-2 font-weight-bold text-primary">Edit Account Info</h4>
              <hr>
            
            <div class="card-body">
      

            <form role="form" method="post" action="settings_edit.php">
              <input type="hidden" name="id" value="<?php echo $zz; ?>" />

              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                 First Name:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="First Name" name="firstname" value="<?php echo $a; ?>" required>
                </div>
              </div>
              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Last Name:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Last Name" name="lastname" value="<?php echo $b; ?>" required>
                </div>
              </div>
              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Gender:
                </div>  
                <div class="col-sm-9">
                <select class='form-control' name='gender' required>
    <option value="Male" <?php if ($c == 'Male') echo 'selected'; ?>>Male</option>
    <option value="Female" <?php if ($c == 'Female') echo 'selected'; ?>>Female</option>
</select>
                </div>
              </div>
              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Username:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Username" name="username" value="<?php echo $d; ?>" required>
                </div>
              </div>
      

              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Email:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Email" name="email" value="<?php echo $f; ?>" >
                </div>
              </div>
              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Contact #:
                </div>
                <div class="col-sm-9">
                   <input class="form-control" placeholder="Contact #" name="phone" value="<?php echo $g; ?>" >
                </div>
              </div>
              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Role:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Role" name="role" value="<?php echo $h; ?>" readonly>
                </div>
              </div>

              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                 Hired Date:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Hired Date" name="hireddate" value="<?php echo $i; ?>" required>
                </div>
              </div>

            
          
              <div class="form-group row text-left">
    <div class="col-sm-3" style="padding-top: 5px;">Current Province:</div>
    <div class="col-sm-9">
        <input type="text" class="form-control" value="<?php echo $j; ?>" readonly>
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
                $selected = ($rowProv['PROVINCE'] == $j) ? 'selected' : '';
                echo "<option value='" . $rowProv['PROVINCE'] . "' $selected>" . $rowProv['PROVINCE'] . "</option>";
            }
            ?>
        </select>
    </div>
</div>

<div class="form-group row text-left">
    <div class="col-sm-3" style="padding-top: 5px;">Current City:</div>
    <div class="col-sm-9">
        <input type="text" class="form-control" value="<?php echo $k; ?>" readonly>
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
                $selected = ($rowCity['CITY'] == $k) ? 'selected' : '';
                echo "<option value='" . $rowCity['CITY'] . "' $selected>" . $rowCity['CITY'] . "</option>";
            }
            ?>
        </select>
    </div>
</div>


              <div class="form-group row text-left text-primary">
                <div class="col-sm-3" style="padding-top: 5px;">
                  Account Type:
                </div>
                <div class="col-sm-9">
                  <input class="form-control" placeholder="Account Type" name="type" value="<?php echo $l; ?>" readonly>
                </div>
              </div>
              

                <button type="submit" class="btn btn-warning btn-block"><i class="fa fa-edit fa-fw"></i>Update</button>   
                <a type="button" class="btn btn-primary bg-gradient-primary btn-block" href="javascript:history.back();">
                <i class="fas fa-flip-horizontal fa-fw fa-share"></i> Back
                </a>

              </form>  
            </div>
          </div>        
          </center>
<?php
include '../includes/footer.php';
?>

