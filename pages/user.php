<?php 
require_once('redirect.php');
allowOnlyAdmin();
?>

<?php
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
        include '../includes/sidebar_manager.php';
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
    <!-- Admin Accounts Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <h4 class="m-0 fw-bold text-primary">
                                <i class="fas fa-user-shield me-2"></i>Admin Accounts
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="adminTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="fw-bold">Name</th>
                                    <th class="fw-bold">Username</th>
                                    <th class="fw-bold">Type</th>
                                    <th class="fw-bold">Status</th>
                                    <th class="fw-bold text-center">Actions</th>
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
                                    echo '<td class="fw-semibold">' . htmlspecialchars($row['FIRST_NAME']) . ' ' . htmlspecialchars($row['LAST_NAME']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['USERNAME']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['TYPE']) . '</td>';
                                    
                                    $status = $row['STATUS'];
                                    $badgeClass = ($status == 'Enable') ? 'bg-success' : 'bg-danger';
                                    echo '<td><span class="badge ' . $badgeClass . '">' . htmlspecialchars($status) . '</span></td>';
                                    
                                    echo '<td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-light btn-sm rounded-pill dropdown-toggle" type="button" id="dropdownMenuButton' . $row['ID'] . '" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="dropdownMenuButton' . $row['ID'] . '">
                                                    <li>
                                                        <a class="dropdown-item" href="us_searchfrm.php?action=edit&id=' . $row['ID'] . '">
                                                            <i class="fas fa-eye me-2 text-info"></i>Details
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item" href="us_edit.php?action=edit&id=' . $row['ID'] . '">
                                                            <i class="fas fa-edit me-2 text-warning"></i>Edit
                                                        </a>
                                                    </li>
                                                </ul>
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
        </div>
    </div>

    <!-- Cashier Accounts Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <h4 class="m-0 fw-bold text-primary">
                                <i class="fas fa-cash-register me-2"></i>Cashier Accounts
                            </h4>
                        </div>
                        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#cashierModal">
                            <i class="fas fa-plus me-2"></i>Add Cashier
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="cashierTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="fw-bold">Name</th>
                                    <th class="fw-bold">Username</th>
                                    <th class="fw-bold">Branch</th>
                                    <th class="fw-bold">Type</th>
                                    <th class="fw-bold">Status</th>
                                    <th class="fw-bold text-center">Actions</th>
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
                                    echo '<td class="fw-semibold">' . htmlspecialchars($row['FIRST_NAME']) . ' ' . htmlspecialchars($row['LAST_NAME']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['USERNAME']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['BRANCH_NAME']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['TYPE']) . '</td>';
                                    
                                    $status = $row['STATUS'];
                                    $badgeClass = ($status == 'Enable') ? 'bg-success' : 'bg-danger';
                                    echo '<td><span class="badge ' . $badgeClass . '">' . htmlspecialchars($status) . '</span></td>';
                                    
                                    echo '<td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-light btn-sm rounded-pill dropdown-toggle" type="button" id="dropdownMenuButton' . $row['ID'] . '" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="dropdownMenuButton' . $row['ID'] . '">
                                                    <li>
                                                        <a class="dropdown-item" href="us_searchfrm.php?action=edit&id=' . $row['ID'] . '">
                                                            <i class="fas fa-eye me-2 text-info"></i>Details
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item" href="us_edit.php?action=edit&id=' . $row['ID'] . '">
                                                            <i class="fas fa-edit me-2 text-warning"></i>Edit
                                                        </a>
                                                    </li>
                                                </ul>
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
        </div>
    </div>

    <!-- Manager Accounts Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <h4 class="m-0 fw-bold text-primary">
                                <i class="fas fa-user-tie me-2"></i>Manager Accounts
                            </h4>
                        </div>
                        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#managerModal">
                            <i class="fas fa-plus me-2"></i>Add Manager
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="managerTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="fw-bold">Name</th>
                                    <th class="fw-bold">Username</th>
                                    <th class="fw-bold">Branch</th>
                                    <th class="fw-bold">Type</th>
                                    <th class="fw-bold">Status</th>
                                    <th class="fw-bold text-center">Actions</th>
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
                                    echo '<td class="fw-semibold">' . htmlspecialchars($row['FIRST_NAME']) . ' ' . htmlspecialchars($row['LAST_NAME']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['USERNAME']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['BRANCH_NAME']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['TYPE']) . '</td>';
                                    
                                    $status = $row['STATUS'];
                                    $badgeClass = ($status == 'Enable') ? 'bg-success' : 'bg-danger';
                                    echo '<td><span class="badge ' . $badgeClass . '">' . htmlspecialchars($status) . '</span></td>';
                                    
                                    echo '<td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-light btn-sm rounded-pill dropdown-toggle" type="button" id="dropdownMenuButton' . $row['ID'] . '" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="dropdownMenuButton' . $row['ID'] . '">
                                                    <li>
                                                        <a class="dropdown-item" href="us_searchfrm.php?action=edit&id=' . $row['ID'] . '">
                                                            <i class="fas fa-eye me-2 text-info"></i>Details
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item" href="us_edit.php?action=edit&id=' . $row['ID'] . '">
                                                            <i class="fas fa-edit me-2 text-warning"></i>Edit
                                                        </a>
                                                    </li>
                                                </ul>
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
        </div>
    </div>
</div>

<!-- Cashier Modal -->
<div class="modal fade" id="cashierModal" tabindex="-1" aria-labelledby="cashierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="cashierModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Add New Cashier
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form role="form" method="post" action="us_transac.php?action=add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Employee:</label>
                        <?php
                        $sql = "SELECT EMPLOYEE_ID, FIRST_NAME, LAST_NAME, j.JOB_TITLE
                                FROM employee e
                                JOIN job j ON j.JOB_ID=e.JOB_ID
                                WHERE j.JOB_TITLE = 'Cashier'
                                ORDER BY e.LAST_NAME ASC";
                        $res = mysqli_query($db, $sql) or die ("Bad SQL: $sql");

                        echo "<select class='form-select' name='empid' required>
                                <option value='' disabled selected>Select Employee</option>";
                        while ($row = mysqli_fetch_assoc($res)) {
                            echo "<option value='".$row['EMPLOYEE_ID']."'>".$row['LAST_NAME'].', '.$row['FIRST_NAME'].' - '.$row['JOB_TITLE']."</option>";
                        }
                        echo "</select>";
                        ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username:</label>
                        <input type="text" class="form-control" name="username" id="username" required>
                        <div id="username-error" class="text-danger mt-1"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password:</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Manager Modal -->
<div class="modal fade" id="managerModal" tabindex="-1" aria-labelledby="managerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="managerModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Add New Manager
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form role="form" method="post" action="us_transac_manager.php?action=add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Employee:</label>
                        <?php
                        $sql = "SELECT EMPLOYEE_ID, FIRST_NAME, LAST_NAME, j.JOB_TITLE
                                FROM employee e
                                JOIN job j ON j.JOB_ID=e.JOB_ID
                                WHERE j.JOB_TITLE = 'Manager'
                                ORDER BY e.LAST_NAME ASC";
                        $res = mysqli_query($db, $sql) or die ("Bad SQL: $sql");

                        echo "<select class='form-select' name='empid' required>
                                <option value='' disabled selected>Select Employee</option>";
                        while ($row = mysqli_fetch_assoc($res)) {
                            echo "<option value='".$row['EMPLOYEE_ID']."'>".$row['LAST_NAME'].', '.$row['FIRST_NAME'].' - '.$row['JOB_TITLE']."</option>";
                        }
                        echo "</select>";
                        ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username:</label>
                        <input type="text" class="form-control" name="username" id="username2" required>
                        <div id="username-error2" class="text-danger mt-1"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password:</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save
                    </button>
                </div>
            </form>
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
    // Initialize DataTables
    $('#adminTable, #cashierTable, #managerTable').DataTable({
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search users..."
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip'
    });

    // Initialize all dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });

    // Username availability check for cashier
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

    // Username availability check for manager
    $("#username2").on("input", function() {
        var username = $(this).val();
        if (username.length > 0) {
            $.ajax({
                url: "us_transac_manager.php?action=check_username",
                type: "POST",
                data: { username: username },
                success: function(response) {
                    if (response === "taken") {
                        $("#username-error2").text("Username is already taken.");
                        $("button[type=submit]").prop("disabled", true);
                    } else {
                        $("#username-error2").text("");
                        $("button[type=submit]").prop("disabled", false);
                    }
                }
            });
        } else {
            $("#username-error2").text("");
            $("button[type=submit]").prop("disabled", false);
        }
    });
});
</script>
