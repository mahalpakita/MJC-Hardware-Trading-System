<!-- Employee select and script -->
<?php
$sqlforjob = "SELECT DISTINCT JOB_TITLE, JOB_ID FROM job order by JOB_ID asc";
$result = mysqli_query($db, $sqlforjob) or die ("Bad SQL: $sqlforjob");

$job = "<select class='form-control' name='jobs' required>
        <option value='' disabled selected hidden>Select Job</option>";
  while ($row = mysqli_fetch_assoc($result)) {
    $job .= "<option value='".$row['JOB_ID']."'>".$row['JOB_TITLE']."</option>";
  }

$job .= "</select>";


// Fetch suppliers for the dropdown
$sql2 = "SELECT DISTINCT SUPPLIER_ID, COMPANY_NAME FROM supplier ORDER BY COMPANY_NAME ASC";
$result2 = mysqli_query($db, $sql2) or die("Bad SQL: $sql2");

$sup = "<select class='form-control' name='supplier'required>
        <option disabled selected hidden>Select Supplier</option>";
while ($row = mysqli_fetch_assoc($result2)) {
    $sup .= "<option value='" . $row['SUPPLIER_ID'] . "'>" . $row['COMPANY_NAME'] . "</option>";
}
$sup .= "</select>";
?>
<script>
  document.addEventListener("DOMContentLoaded", function() {
  var cityObj = new City();
  cityObj.showProvinces("#province");
  cityObj.showCities("#city");
  
  var cityObj2 = new City();
  cityObj2.showProvinces("#provincee");
  cityObj2.showCities("#cityy");

  console.log(cityObj.getProvinces());
  console.log(cityObj.getAllCities());
});

</script>

<?php
// Fetch user role and branch ID
$queryUser = "SELECT TYPE_ID, BRANCH_ID FROM users WHERE ID = " . intval($_SESSION['MEMBER_ID']);
$resultUser = mysqli_query($db, $queryUser) or die(mysqli_error($db));

if ($rowUser = mysqli_fetch_assoc($resultUser)) {
    $userTypeID = $rowUser['TYPE_ID']; // 1 = Admin, 2 = Manager
    $userBranchID = $rowUser['BRANCH_ID'];
}
?>

<!-- Customer Modal-->
<div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Customer</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form role="form" method="post" action="cust_transac.php?action=add" onsubmit="return validateForm()">
          
          <div class="form-group">
            <input class="form-control" placeholder="First Name" name="firstname" required>
          </div>
          <div class="form-group">
            <input class="form-control" placeholder="Last Name" name="lastname" required>
          </div>
          <div class="form-group">
            <input class="form-control" placeholder="Phone Number" name="phonenumber" pattern="[0-9]+" title="Please enter numbers only" required>
          </div>

          <?php if ($userTypeID == 1) { ?>
            <!-- Admin: Can select any branch -->
            <div class="form-group">
              <select class="form-control" name="branch" required>
                <option value="" disabled selected>Select Branch</option>
                <?php
                $query = 'SELECT BRANCH_ID, BRANCH_NAME FROM branches';
                $result = mysqli_query($db, $query) or die(mysqli_error($db));
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="'.$row['BRANCH_ID'].'">'.$row['BRANCH_NAME'].'</option>';
                }
                ?>
              </select>
            </div>
          <?php } else { ?>
            <!-- Manager: Automatically assign their branch -->
            <input type="hidden" name="branch" value="<?php echo $userBranchID; ?>">
          <?php } ?>

          <div class="form-group">
            <select class="form-control" id="province" name="province" required>
              <option value="" disabled selected hidden>Select Province</option>
            </select>
          </div>
          <div class="form-group">
            <select class="form-control" id="city" name="city" required>
              <option value="" disabled selected hidden>Select City</option>
            </select>
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


  <!-- Employee Modal-->
  <div class="modal fade" id="employeeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Employee</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form role="form" method="post" action="emp_transac.php?action=add">          
              <div class="form-group">
                <input class="form-control" placeholder="First Name" name="firstname" required>
              </div>
              <div class="form-group">
                <input class="form-control" placeholder="Last Name" name="lastname" required>
              </div>
              <div class="form-group">
                  <select class='form-control' name='gender' required>
                    <option value="" disabled selected hidden>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                  </select>
              </div>
              <div class="form-group">
                <input class="form-control" placeholder="Email" name="email" >
              </div>
              <div class="form-group">
              <input class="form-control" placeholder="Phone Number" name="phonenumber" pattern="[0-9]+" title="Please enter numbers only" >
              </div>
              <div class="form-group">
                <?php
                  echo $job;
                ?>
              </div>
              <div class="form-group">
            <select class="form-control" name="branch" required>
              <option value="" disabled selected>Select Branch</option>
              <?php
              $query = 'SELECT BRANCH_ID, BRANCH_NAME FROM branches';
              $result = mysqli_query($db, $query) or die(mysqli_error($db));
              while ($row = mysqli_fetch_assoc($result)) {
                  echo '<option value="'.$row['BRANCH_ID'].'">'.$row['BRANCH_NAME'].'</option>';
              }
              ?>
            </select>
          </div>
          
          <div class="form-group">
                <input placeholder="Hired Date" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="FromDate" name="hireddate" class="form-control" />
              </div>
              
                <div class="form-group">
            <select class="form-control" id="provincee" name="provincee" required>
              <option value="" disabled selected hidden>Select Province</option>
            </select>
          </div>
          <div class="form-group">
            <select class="form-control" id="cityy" name="cityy" required>
              <option value="" disabled selected hidden>Select City</option>
            </select>
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

  <!-- Delete Modal-->
  <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="DeleteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Confirm Delete</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Are you sure do you want to delete?</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-danger btn-ok">Delete</a>
        </div>
      </div>
    </div>
  </div>

 
<!-- Product Modal-->
<div class="modal fade" id="aModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form role="form" method="post" action="pro_transac.php?action=add">
                    <div class="form-group">
                        <input class="form-control" id="prodcode" placeholder="Product Code" name="prodcode" >
                    </div>
                    <div class="form-group">
                        <input class="form-control" placeholder="Name" name="name" required>
                    </div>
                    <div class="form-group">
                        <textarea rows="5" cols="50" class="form-control" placeholder="Description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <input type="number" min="1" max="999999999" class="form-control" placeholder="Quantity" name="quantity" required>
                    </div>
                    <div class="form-group">
                        <input type="number" min="1" max="9999999999" class="form-control" id="price" placeholder="Price" name="price" required>
                    </div>

                    <!-- Branch Selection -->
                    <div class="form-group">
                        <select class="form-control" name="branch_id" required>
                            <option value="" disabled selected>Select Branch</option>
                            <?php
                                $branch_query = "SELECT BRANCH_ID, BRANCH_NAME FROM branches";
                                $branch_result = mysqli_query($db, $branch_query);
                                while ($row = mysqli_fetch_assoc($branch_result)) {
                                    echo "<option value='".$row['BRANCH_ID']."'>".$row['BRANCH_NAME']."</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <!-- Supplier Selection -->
                    <div class="form-group">
                        <label for="supplier">Supplier</label>
                        <?php
                            $sql2 = "SELECT DISTINCT SUPPLIER_ID, COMPANY_NAME FROM supplier ORDER BY COMPANY_NAME ASC";
                            $result2 = mysqli_query($db, $sql2) or die("Bad SQL: $sql2");

                            echo "<select class='form-control' name='supplier' id='supplier' required>";
                            echo "<option disabled selected hidden>Select Supplier</option>";
                            while ($row = mysqli_fetch_assoc($result2)) {
                                echo "<option value='" . $row['SUPPLIER_ID'] . "'>" . $row['COMPANY_NAME'] . "</option>";
                            }
                            echo "</select>";
                        ?>
                    </div>

                    <div class="form-group">
                        <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control" placeholder="Date Stock In" name="datestock" required>
                    </div>
                    <div class="form-group">
                        <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control" placeholder="Expiration Date" name="expiration">
                    </div>

                    <!-- Remarks Field -->
                    <div class="form-group">
                        <textarea class="form-control" rows="3" placeholder="Remarks (Optional)" name="remarks"></textarea>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check fa-fw"></i> Save</button>
                    <button type="reset" class="btn btn-danger"><i class="fa fa-times fa-fw"></i> Reset</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>



  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body"><?php echo  $_SESSION['FIRST_NAME']; ?> are you sure do you want to logout?</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="logout.php">Logout</a>
        </div>
      </div>
    </div>
  </div>



    <script>
        $('#confirm-delete').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            
            $('.debug-url').html('Delete URL: <strong>' + $(this).find('.btn-ok').attr('href') + '</strong>');
        });
    </script>

    

