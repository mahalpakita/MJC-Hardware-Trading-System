<?php 
require_once('redirect.php');
allowOnlyManager();
include '../includes/connection.php';

if (!isset($_SESSION['MEMBER_ID'])) {
    header("Location: login.php");
    exit();
}

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

// Get the logged-in user's branch
$userID = $_SESSION['MEMBER_ID'];
$branchQuery = "SELECT BRANCH_ID FROM users WHERE ID = '$userID'";
$branchResult = mysqli_query($db, $branchQuery) or die(mysqli_error($db));
$branchRow = mysqli_fetch_assoc($branchResult);
$branchID = $branchRow['BRANCH_ID'];
?>

<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Customer Card -->
        <div class="col-md-3">
            <a href="customer.php" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="text-primary fw-bold mb-1">Customers</h6>
                                <h3 class="mb-0 fw-bold">
                                    <?php 
                                    $query = "SELECT COUNT(*) FROM customer WHERE BRANCH_ID = '$branchID'";
                                    $result = mysqli_query($db, $query) or die(mysqli_error($db));
                                    echo mysqli_fetch_array($result)[0];
                                    ?>
                                </h3>
                            </div>
                            <div class="p-3">
                                <i class="fas fa-users fa-2x text-primary"></i>
                            </div>
                        </div>
                        <p class="text-muted mb-0">Total customers in your branch</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Transaction Card -->
        <div class="col-md-3">
            <a href="transaction.php" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="text-warning fw-bold mb-1">Transactions</h6>
                                <h3 class="mb-0 fw-bold">
                                    <?php 
                                    $query = "SELECT COUNT(*) FROM transaction";
                                    $result = mysqli_query($db, $query) or die(mysqli_error($db));
                                    echo mysqli_fetch_array($result)[0];
                                    ?>
                                </h3>
                            </div>
                            <div class="p-3">
                                <i class="fas fa-exchange-alt fa-2x text-warning"></i>
                            </div>
                        </div>
                        <p class="text-muted mb-0">Total transactions processed</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Employee Card -->
        <div class="col-md-3">
            <a href="employee_manager.php" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="text-success fw-bold mb-1">Employees</h6>
                                <h3 class="mb-0 fw-bold">
                                    <?php 
                                    $query = "SELECT COUNT(*) FROM employee WHERE BRANCH_ID = '$branchID'";
                                    $result = mysqli_query($db, $query) or die(mysqli_error($db));
                                    echo mysqli_fetch_array($result)[0];
                                    ?>
                                </h3>
                            </div>
                            <div class="p-3">
                                <i class="fas fa-user-tie fa-2x text-success"></i>
                            </div>
                        </div>
                        <p class="text-muted mb-0">Employees in your branch</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Inventory Card -->
        <div class="col-md-3">
            <a href="Inventory_cashier.php" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="text-info fw-bold mb-1">Inventory</h6>
                                <h3 class="mb-0 fw-bold">
                                    <?php 
                                    $query = "SELECT COUNT(*) FROM product WHERE BRANCH_ID = '$branchID'";
                                    $result = mysqli_query($db, $query) or die(mysqli_error($db));
                                    echo mysqli_fetch_array($result)[0];
                                    ?>
                                </h3>
                            </div>
                            <div class="p-3">
                                <i class="fas fa-boxes fa-2x text-info"></i>
                            </div>
                        </div>
                        <p class="text-muted mb-0">Products in your inventory</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Purchase Order Card -->
        <div class="col-md-3">
            <a href="Create_po.php" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="text-danger fw-bold mb-1">Purchase Orders</h6>
                                <h3 class="mb-0 fw-bold">
                                    <?php 
                                    $query = "SELECT COUNT(*) FROM purchase_orders";
                                    $result = mysqli_query($db, $query) or die(mysqli_error($db));
                                    echo mysqli_fetch_array($result)[0];
                                    ?>
                                </h3>
                            </div>
                            <div class="p-3">
                                <i class="fas fa-truck fa-2x text-danger"></i>
                            </div>
                        </div>
                        <p class="text-muted mb-0">Total purchase orders</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<!-- Add Bootstrap CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<style>
.hover-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.bg-opacity-10 {
    opacity: 0.1;
}

.card {
    border-radius: 1rem;
}

.card-body {
    border-radius: 1rem;
}

.text-muted {
    font-size: 0.875rem;
}
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
