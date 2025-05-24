<?php 
require_once('redirect.php'); 
allowOnlyManager();
include '../includes/connection.php';

if (!isset($_SESSION['MEMBER_ID'])) {
    header("Location: login.php");
    exit();
}

// Fetch user role
$querya = "SELECT u.TYPE_ID FROM users u WHERE u.ID = " . intval($_SESSION['MEMBER_ID']);
$resulta = mysqli_query($db, $querya) or die(mysqli_error($db));

if ($row = mysqli_fetch_assoc($resulta)) {
    $userTypeID = $row['TYPE_ID'];

    if ($userTypeID == 1) {
        include '../includes/sidebar.php';
    } elseif ($userTypeID == 2) {    
        include '../includes/sidebar_cashier.php';
    } elseif ($userTypeID == 3) {
        include '../includes/sidebar_manger.php';
    } else {
        die('Unauthorized User Type');
    }
} else {
    die('User not found in database.');
}
?>

<!-- Required CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <h4 class="m-0 fw-bold text-primary">
                                <i class="fas fa-user-tie me-2"></i>Employees
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="dataTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="fw-bold">First Name</th>
                                    <th class="fw-bold">Last Name</th>
                                    <th class="fw-bold">Role</th>
                                    <th class="fw-bold">Branch</th>
                                    <th class="fw-bold text-center">Actions</th>
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
                                        echo '<td class="fw-semibold">' . htmlspecialchars($row['FIRST_NAME'] ?? '') . '</td>';
                                        echo '<td>' . htmlspecialchars($row['LAST_NAME'] ?? '') . '</td>';
                                        echo '<td>' . htmlspecialchars($row['JOB_TITLE'] ?? '') . '</td>';
                                        echo '<td>' . htmlspecialchars($row['BRANCH_NAME'] ?? '') . '</td>';
                                        
                                        echo '<td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-light btn-sm rounded-pill dropdown-toggle" type="button" id="dropdownMenuButton' . $row['EMPLOYEE_ID'] . '" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="dropdownMenuButton' . $row['EMPLOYEE_ID'] . '">
                                                        <li>
                                                            <a class="dropdown-item" href="emp_searchfrm.php?action=edit&id=' . $row['EMPLOYEE_ID'] . '">
                                                                <i class="fas fa-eye me-2 text-info"></i>Details
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
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
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<!-- Required Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    $('#dataTable').DataTable({
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search employees..."
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip'
    });

    // Initialize all dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
});
</script>
