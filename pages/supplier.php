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
        <h4 class="m-0 font-weight-bold">Suppliers</h4>
        <a href="#" data-toggle="modal" data-target="#supplierModal" class="btn btn-light text-primary shadow-sm rounded-circle">
            <i class="fas fa-plus"></i>
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-light text-dark">
                    <tr>
                        <th>Company Name</th>
                        <th>Province</th>
                        <th>City</th>
                        <th>Phone Number</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php                  
                    $query = 'SELECT SUPPLIER_ID, COMPANY_NAME, l.PROVINCE, l.CITY, PHONE_NUMBER 
                              FROM supplier s 
                              JOIN location l ON s.LOCATION_ID = l.LOCATION_ID';
                    $result = mysqli_query($db, $query) or die(mysqli_error($db));

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['COMPANY_NAME'] ?? '') . '</td>';
                        echo '<td>' . htmlspecialchars($row['PROVINCE'] ?? '') . '</td>';
                        echo '<td>' . htmlspecialchars($row['CITY'] ?? '') . '</td>';
                        echo '<td>' . htmlspecialchars($row['PHONE_NUMBER'] ?? '') . '</td>';

                        echo '<td class="text-center"> 
                                <div class="btn-group">
                                    
                                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 dropdown-toggle" data-toggle="dropdown">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right shadow-sm">

                                     <a class="dropdown-item text-info" href="sup_searchfrm.php?action=edit&id=' . $row['SUPPLIER_ID'] . '">
                                            <i class="fas fa-list-alt"></i> Details
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-warning" href="sup_edit.php?action=edit&id=' . $row['SUPPLIER_ID'] . '">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="sup_del.php?action=delete&id=' . $row['SUPPLIER_ID'] . '" onclick="return confirm(\'Are you sure you want to delete this supplier?\')">
                                            <i class="fas fa-trash"></i> Delete
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

  <!-- Customer Modal-->
  <div class="modal fade" id="supplierModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Supplier</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form role="form" method="post" action="sup_transac.php?action=add">
              
              <div class="form-group">
                <input class="form-control" placeholder="Company Name" name="companyname" required>
              </div>

              <div class="form-group">
                <select class="form-control" id="province" placeholder="Province" name="province" required>
                <option value="" disabled selected hidden>Select Province</option>
                </select>
              </div>

              <div class="form-group">
                <select class="form-control" id="city" placeholder="City" name="city" required>
                <option value="" disabled selected hidden>Select Province</option>
                </select>
              </div>

              <div class="form-group">          
            <input class="form-control" placeholder="Phone Number" name="phonenumber">
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
<?php
include '../includes/footer.php';
?>