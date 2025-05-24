<?php 
require_once('redirect.php');
restrictOnlyAdmin();
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
// Initialize notification session
if (!isset($_SESSION['notifications'])) {
    $_SESSION['notifications'] = [];
}

?>

<!-- Toast Notification Container -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="notificationToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-warning text-dark">
            <i class="fas fa-bell me-2"></i>
            <strong class="me-auto">Notifications</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <?php 
            if (!empty($_SESSION['notifications'])) {
                foreach ($_SESSION['notifications'] as $note) {
                    echo "<p class='mb-2'>$note</p>";
                }
                unset($_SESSION['notifications']);
            }
            ?>
        </div>
    </div>
</div>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <h4 class="m-0 fw-bold text-primary">
                                <i class="fas fa-boxes me-2"></i>Inventory
                            </h4>
                            <button id="notificationBell" class="btn btn-light text-primary ms-3 rounded-circle position-relative">
                                <i class="fas fa-bell"></i>
                                <?php if (!empty($_SESSION['notifications'])): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo count($_SESSION['notifications']); ?>
                                </span>
                                <?php endif; ?>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="dataTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="fw-bold">Name</th>
                                    <th class="fw-bold">Description</th>
                                    <th class="fw-bold">Quantity</th>
                                    <th class="fw-bold">Price</th>
                                    <th class="fw-bold">Status</th>
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

                                $query = 'SELECT p.PRODUCT_ID, p.SUPPLIER_ID, p.NAME, p.DESCRIPTION, p.QTY_STOCK, p.PRICE, p.EXPIRATION_DATE, b.BRANCH_NAME 
                                         FROM product p 
                                         LEFT JOIN branches b ON p.BRANCH_ID = b.BRANCH_ID';

                                if ($userTypeID == 2 || $userTypeID == 3) {
                                    $query .= " WHERE p.BRANCH_ID = " . intval($userBranchID);
                                }

                                $result = mysqli_query($db, $query) or die(mysqli_error($db));

                                while ($row = mysqli_fetch_assoc($result)) {
                                    $status = "Available";
                                    $current_date = date('Y-m-d');
                                    $expiration_date = $row['EXPIRATION_DATE'];
                                    $product_name = htmlspecialchars($row['NAME']);
                                    $product_desc = htmlspecialchars($row['DESCRIPTION']);

                                    if (!empty($expiration_date) && $expiration_date <= $current_date) {
                                        $status = "Expired";
                                        $_SESSION['notifications'][] = "⚠️ Product '$product_name' ($product_desc) has expired!";
                                    } elseif (!empty($expiration_date) && $expiration_date <= date('Y-m-d', strtotime('+7 days'))) {
                                        $status = "Expiration Near";
                                        $_SESSION['notifications'][] = "⚠️ Product '$product_name' ($product_desc) is nearing expiration!";
                                    } elseif ($row['QTY_STOCK'] == 0) {
                                        $status = "Out of Stock";
                                        $_SESSION['notifications'][] = "❌ Product '$product_name' ($product_desc) is out of stock!";
                                    } elseif ($row['QTY_STOCK'] <= 5) {
                                        $status = "Low Stock";
                                        $_SESSION['notifications'][] = "⚠️ Product '$product_name' ($product_desc) has low stock ({$row['QTY_STOCK']} left)!";
                                    }
                                    
                                    $statusClass = match($status) {
                                        'Expired', 'Out of Stock' => 'bg-danger',
                                        'Low Stock', 'Expiration Near' => 'bg-warning',
                                        default => 'bg-success'
                                    };

                                    echo '<tr>';
                                    echo '<td class="fw-semibold">' . $product_name . '</td>';
                                    echo '<td>' . htmlspecialchars($row['DESCRIPTION']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['QTY_STOCK']) . '</td>';
                                    echo '<td>₱' . number_format($row['PRICE'], 2) . '</td>';
                                    echo '<td><span class="badge ' . $statusClass . ' rounded-pill px-3 py-2">' . $status . '</span></td>';
                                    echo '<td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-light btn-sm rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">';
                                    
                                    echo '<li>
                                            <a class="dropdown-item" href="inv_searchfrm.php?action=edit&id=' . $row['PRODUCT_ID'] . '">
                                                <i class="fas fa-list-alt me-2 text-info"></i>Details
                                            </a>
                                          </li>';
                                    
                                    if ($status == "Out of Stock" || $status == "Low Stock") {
                                        echo '<li><hr class="dropdown-divider"></li>
                                              <li>
                                                <a class="dropdown-item text-success" href="create_po.php?product_id=' . $row['PRODUCT_ID'] . '&supplier_id=' . $row['SUPPLIER_ID'] . '">
                                                    <i class="fas fa-truck me-2"></i>Reorder
                                                </a>
                                              </li>';
                                    }
                                    
                                    echo '</ul>
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

<script>
document.getElementById('notificationBell').addEventListener('click', function() {
    var toast = new bootstrap.Toast(document.getElementById('notificationToast'));
    toast.show();
});
</script>

<?php
include '../includes/footer.php';
?>

<!-- Add Bootstrap CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
