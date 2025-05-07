
<?php 
require_once('redirect.php'); 
allowOnlyManager();
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
        <h4 class="m-0 font-weight-bold">Employees</h4>
       
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-light text-dark">
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Role</th>
                        <th>Branch</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $queryUser = "SELECT TYPE_ID, BRANCH_ID FROM users WHERE ID = " . intval($_SESSION['MEMBER_ID']);
                    $resultUser = mysqli_query($db, $queryUser) or die(mysqli_error($db));

                    if ($rowUser = mysqli_fetch_assoc($resultUser)) {
                        $userTypeID = $rowUser['TYPE_ID'];
                        $userBranchID = $rowUser['BRANCH_ID'];

                        $query = "SELECT e.EMPLOYEE_ID, e.FIRST_NAME, e.LAST_NAME, j.JOB_TITLE, b.BRANCH_NAME 
                                  FROM employee e 
                                  JOIN job j ON e.JOB_ID = j.JOB_ID 
                                  JOIN branches b ON e.BRANCH_ID = b.BRANCH_ID";

                        if ($userTypeID == 2 || $userTypeID == 3) {
                            $query .= " WHERE e.BRANCH_ID = " . intval($userBranchID);
                        }

                        $result = mysqli_query($db, $query) or die(mysqli_error($db));

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['FIRST_NAME'] ?? '') . '</td>';
                            echo '<td>' . htmlspecialchars($row['LAST_NAME'] ?? '') . '</td>';
                            echo '<td>' . htmlspecialchars($row['JOB_TITLE'] ?? '') . '</td>';
                            echo '<td>' . htmlspecialchars($row['BRANCH_NAME'] ?? '') . '</td>';
                            
                            echo '<td class="text-center"> 
                                 
                                  
                                         <a class="dropdown-item text-info" href="emp_searchfrm.php?action=edit&id=' . $row['EMPLOYEE_ID'] . '">
                                            <i class="fas fa-list-alt"></i> Details
                                        </a>
                                      
                                  </td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
