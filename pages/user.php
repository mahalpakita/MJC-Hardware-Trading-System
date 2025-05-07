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

<div class="card shadow-lg rounded-lg border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3 rounded-top">
        <h4 class="m-0 font-weight-bold">Admin Account(s)</h4>
       
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-light text-dark">
                    <tr>
                        <th>Name</th>  
                        <th>Username</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php                  
                        $query = "SELECT u.ID, e.FIRST_NAME, e.LAST_NAME, u.USERNAME, t.TYPE, u.STATUS
                                  FROM users u
                                  JOIN employee e ON e.EMPLOYEE_ID = u.EMPLOYEE_ID
                                  JOIN type t ON t.TYPE_ID = u.TYPE_ID
                                  WHERE u.TYPE_ID = 1";
                        $result = mysqli_query($db, $query) or die(mysqli_error($db));

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['FIRST_NAME']) . ' ' . htmlspecialchars($row['LAST_NAME']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['USERNAME']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['TYPE']) . '</td>';
                            
                            // Status badge
                            $status = $row['STATUS'];
                            $badgeClass = ($status == 'Enable') ? 'badge-success' : 'badge-danger';
                            echo '<td><span class="badge ' . $badgeClass . '">' . htmlspecialchars($status) . '</span></td>';
                            echo '<td class="text-center"> 
                            <div class="btn-group">
                               
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 dropdown-toggle" data-toggle="dropdown">
                                    Actions
                                </button>

                                <div class="dropdown-menu dropdown-menu-right shadow-sm">
                                
                                 <a class="dropdown-item text-info" href="us_searchfrm.php?action=edit&id=' . $row['ID'] . '">
                                    <i class="fas fa-list-alt"></i> Details
                                </a>

                                 <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-warning" href="us_edit.php?action=edit&id=' . $row['ID'] . '">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </div>
                            </div> 
                          </td>';
                            echo '</tr>';
                        }
                    ?>         
                </tbody>
            </table>
        </div>
    </div>
</div>



<div class="card shadow-lg rounded-lg border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3 rounded-top">
        <h4 class="m-0 font-weight-bold">Cashier Accounts</h4>
        <a href="#" data-toggle="modal" data-target="#supplierModal" class="btn btn-light text-primary shadow-sm rounded-circle">
            <i class="fas fa-plus"></i>
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-light text-dark">
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Branch</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php                  
                        $query = "SELECT u.ID, e.FIRST_NAME, e.LAST_NAME, u.USERNAME, b.BRANCH_NAME, t.TYPE, u.STATUS
                                  FROM users u
                                  JOIN employee e ON e.EMPLOYEE_ID = u.EMPLOYEE_ID
                                  JOIN type t ON t.TYPE_ID = u.TYPE_ID
                                  JOIN branches b ON e.BRANCH_ID = b.BRANCH_ID
                                  WHERE u.TYPE_ID = 2";

                        $result = mysqli_query($db, $query) or die(mysqli_error($db));

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['FIRST_NAME']) . ' ' . htmlspecialchars($row['LAST_NAME']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['USERNAME']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['BRANCH_NAME']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['TYPE']) . '</td>';
                            
                            // Status badge
                            $status = $row['STATUS'];
                            $badgeClass = ($status == 'Enable') ? 'badge-success' : 'badge-danger';
                            echo '<td><span class="badge ' . $badgeClass . '">' . htmlspecialchars($status) . '</span></td>';

                            echo '<td class="text-center"> 
                                    <div class="btn-group">
                                       
                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 dropdown-toggle" data-toggle="dropdown">
                                            Actions
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-right shadow-sm">
                                        
                                         <a class="dropdown-item text-info" href="us_searchfrm.php?action=edit&id=' . $row['ID'] . '">
                                            <i class="fas fa-list-alt"></i> Details
                                        </a>

                                         <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-warning" href="us_edit.php?action=edit&id=' . $row['ID'] . '">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </div>
                                    </div> 
                                  </td>';
                            echo '</tr>';
                        }
                    ?>         
                </tbody>
            </table>
        </div>
    </div>
</div>


                  
<div class="card shadow-lg rounded-lg border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3 rounded-top">
        <h4 class="m-0 font-weight-bold">Manager Accounts</h4>
        <a href="#" data-toggle="modal" data-target="#supplierModal2" class="btn btn-light text-primary shadow-sm rounded-circle">
            <i class="fas fa-plus"></i>
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-light text-dark">
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Branch</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php                  
                        $query = "SELECT u.ID, e.FIRST_NAME, e.LAST_NAME, u.USERNAME, b.BRANCH_NAME, t.TYPE, u.STATUS
                                  FROM users u
                                  JOIN employee e ON e.EMPLOYEE_ID = u.EMPLOYEE_ID
                                  JOIN type t ON t.TYPE_ID = u.TYPE_ID
                                  JOIN branches b ON e.BRANCH_ID = b.BRANCH_ID
                                  WHERE u.TYPE_ID = 3";

                        $result = mysqli_query($db, $query) or die(mysqli_error($db));

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['FIRST_NAME']) . ' ' . htmlspecialchars($row['LAST_NAME']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['USERNAME']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['BRANCH_NAME']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['TYPE']) . '</td>';
                            
                            // Status badge
                            $status = $row['STATUS'];
                            $badgeClass = ($status == 'Enable') ? 'badge-success' : 'badge-danger';
                            echo '<td><span class="badge ' . $badgeClass . '">' . htmlspecialchars($status) . '</span></td>';

                            echo '<td class="text-center"> 
                            <div class="btn-group">
                               
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 dropdown-toggle" data-toggle="dropdown">
                                    Actions
                                </button>

                                <div class="dropdown-menu dropdown-menu-right shadow-sm">
                                
                                 <a class="dropdown-item text-info" href="us_searchfrm.php?action=edit&id=' . $row['ID'] . '">
                                    <i class="fas fa-list-alt"></i> Details
                                </a>

                                 <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-warning" href="us_edit.php?action=edit&id=' . $row['ID'] . '">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </div>
                            </div> 
                          </td>';
                            echo '</tr>';
                        }
                    ?>         
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';

$sql = "SELECT EMPLOYEE_ID, FIRST_NAME, LAST_NAME, j.JOB_TITLE
        FROM employee e
        JOIN job j ON j.JOB_ID=e.JOB_ID
        order by e.LAST_NAME asc";
$res = mysqli_query($db, $sql) or die ("Bad SQL: $sql");

$opt = "<select class='form-control' name='empid' required>
        <option value='' disabled selected hidden>Select Employee</option>";
  while ($row = mysqli_fetch_assoc($res)) {
    $opt .= "<option value='".$row['EMPLOYEE_ID']."'>".$row['LAST_NAME'].', '.$row['FIRST_NAME'].' - '.$row['JOB_TITLE']."</option>";
  }
$opt .= "</select>";
?>

  <!-- User Account Modal-->
  <div class="modal fade" id="supplierModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add User Account</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form role="form" method="post" action="us_transac.php?action=add">
              
              <div class="form-group">
                <?php
                  echo $opt;
                ?>
              </div>
              <div class="form-group">
                <input class="form-control" placeholder="Username" name="username" id="username" required>
              </div>
              <div class="form-group">
                <input type="password" class="form-control" placeholder="Password" name="password" required>
              </div>
            <hr>
            <button type="submit" class="btn btn-success"><i class="fa fa-check fa-fw"></i>Save</button>
            <button type="reset" class="btn btn-danger"><i class="fa fa-times fa-fw"></i>Reset</button>
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>      
          </form>  
        </div>
      </div>
    </div>
  </div>

   <!-- User Account Modal-->
   <div class="modal fade" id="supplierModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add User Account</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form role="form" method="post" action="us_transac_manager?action=add">
              
              <div class="form-group">
                <?php
                  echo $opt;
                ?>
              </div>
              <div class="form-group">
                <input class="form-control" placeholder="Username" name="username" id="username" required>
              </div>
              <div class="form-group">
                <input type="password" class="form-control" placeholder="Password" name="password" required>
              </div>
            <hr>
            <button type="submit" class="btn btn-success"><i class="fa fa-check fa-fw"></i>Save</button>
            <button type="reset" class="btn btn-danger"><i class="fa fa-times fa-fw"></i>Reset</button>
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>      
          </form>  
        </div>
      </div>
    </div>
  </div>

  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $("#username").on("input", function() {
        var username = $(this).val();
        if (username.length > 0) {
            $.ajax({
                url: "us_transac.php?action=check_username",
                type: "POST",
                data: { username: username },
                success: function(response) {
                    if (response === "taken") {
                        $("#username-error").text("Username is already taken.");
                        $("button[type=submit]").prop("disabled", true);
                    } else {
                        $("#username-error").text("");
                        $("button[type=submit]").prop("disabled", false);
                    }
                }
            });
        } else {
            $("#username-error").text("");
            $("button[type=submit]").prop("disabled", false);
        }
    });
});
</script>
