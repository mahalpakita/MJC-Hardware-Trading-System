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
<?php 
if (!empty($_SESSION['notifications'])) {
    echo '<div id="notificationDiv" class="alert alert-warning alert-dismissible fade show" role="alert" style="display:none;">';
    foreach ($_SESSION['notifications'] as $note) {
        echo "<p>$note</p>";
    }
    echo '<button type="button" class="close" id="closeNotification" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>';
    echo '</div>';
    unset($_SESSION['notifications']); // Clear notifications after showing
}
?>

<div class="card shadow-lg rounded-lg border-0">
<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3 rounded-top">
    <h4 class="m-0 font-weight-bold">Inventory</h4>
    <button id="notificationBell" class="btn btn-light text-primary ml-3 shadow-sm rounded-circle">
                <i class="fas fa-bell"></i>
            </button>
   
</div><div class="card-body">
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
            <thead class="bg-light text-dark">
                <tr>
                    <th>Name</th>
                    <th>Description</th> <!-- Added Description Column -->
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Status</th>
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

                // Updated query to include description and branch name
                $query = 'SELECT p.PRODUCT_ID,  p.SUPPLIER_ID, p.NAME, p.DESCRIPTION, p.QTY_STOCK, p.PRICE, p.EXPIRATION_DATE, b.BRANCH_NAME 
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

                    date_default_timezone_set('Asia/Manila');
                    $current_time = date("Y-m-d h:i A");
                    $product_id = $row['PRODUCT_ID']; // you already have this in your select
                    if (!function_exists('getLatestNotification')) {
                    function getLatestNotification($db, $product_id, $keyword) {
                        $message = null; // ✅ Initialize to avoid "undefined variable" error
                    
                        $stmt = $db->prepare("SELECT MESSAGE FROM stock_notifications 
                                              WHERE PRODUCT_ID = ? AND MESSAGE LIKE CONCAT('%', ?, '%') 
                                              ORDER BY NOTIF_TIME DESC LIMIT 1");
                    
                        $stmt->bind_param("is", $product_id, $keyword);
                        $stmt->execute();
                        $stmt->bind_result($message);
                        $stmt->fetch();
                        $stmt->close();
                    
                        return $message;
                    } }
                    
                    if (!function_exists('insertNotification')) {
                    function insertNotification($db, $product_id, $message) {
                        $stmt = $db->prepare("INSERT INTO stock_notifications (PRODUCT_ID, MESSAGE) VALUES (?, ?)");
                        $stmt->bind_param("is", $product_id, $message);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
                    if (!isset($_SESSION['notifications'])) {
                        $_SESSION['notifications'] = [];
                    }
                    
                    if (!empty($expiration_date) && $expiration_date <= $current_date) {
                        $status = "Expired";
                        $existing_msg = getLatestNotification($db, $product_id, 'expired');
                        if (!$existing_msg) {
                            $msg = "❌ Product '$product_name' ($product_desc) has expired! [" . date("Y-m-d h:i A") . "]";
                            insertNotification($db, $product_id, $msg);
                            $existing_msg = $msg;
                        }
                        $_SESSION['notifications'][] = $existing_msg;
                    
                    } elseif (!empty($expiration_date) && $expiration_date <= date('Y-m-d', strtotime('+7 days'))) {
                        $status = "Expiration Near";
                        $existing_msg = getLatestNotification($db, $product_id, 'nearing expiration');
                        if (!$existing_msg) {
                            $msg = "⚠️ Product '$product_name' ($product_desc) is nearing expiration! [" . date("Y-m-d h:i A") . "]";
                            insertNotification($db, $product_id, $msg);
                            $existing_msg = $msg;
                        }
                        $_SESSION['notifications'][] = $existing_msg;
                    
                    } elseif ($row['QTY_STOCK'] == 0) {
                        $status = "Out of Stock";
                        $existing_msg = getLatestNotification($db, $product_id, 'out of stock');
                        if (!$existing_msg) {
                            $msg = "❌ Product '$product_name' ($product_desc) is out of stock! [" . date("Y-m-d h:i A") . "]";
                            insertNotification($db, $product_id, $msg);
                            $existing_msg = $msg;
                        }
                        $_SESSION['notifications'][] = $existing_msg;
                    
                    } elseif ($row['QTY_STOCK'] <= 5) {
                        $status = "Low Stock";
                        $existing_msg = getLatestNotification($db, $product_id, 'low stock');
                        if (!$existing_msg) {
                            $msg = "⚠️ Product '$product_name' ($product_desc) has low stock ({$row['QTY_STOCK']} left)! [" . date("Y-m-d h:i A") . "]";
                            insertNotification($db, $product_id, $msg);
                            $existing_msg = $msg;
                        }
                        $_SESSION['notifications'][] = $existing_msg;
                    }
                    
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['NAME']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['DESCRIPTION']) . '</td>'; // Added Description column
                    echo '<td>' . htmlspecialchars($row['QTY_STOCK']) . '</td>';
                    echo '<td>' . number_format($row['PRICE'], 2) . '</td>';
                    echo '<td class="fw-bold ' . ($status == "Out of Stock" ? 'text-danger' : ($status == "Low Stock" ? 'text-warning' : 'text-success')) . '">' . $status . '</td>';

                   
                        echo '<td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 dropdown-toggle" data-toggle="dropdown">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right shadow-sm">
                                        <a class="dropdown-item text-info" href="inv_searchfrm.php?action=edit&id=' . $row['PRODUCT_ID'] . '">
                                            <i class="fas fa-list-alt"></i> Details
                                        </a>
                                        ';
                        
                        // Add "Reorder" button if product is Out of Stock or Low Stock.
                        // Redirects to create_po.php with product_id and supplier_id passed via GET.
                        if ($status == "Out of Stock" || $status == "Low Stock") {
                            echo '<div class="dropdown-divider"></div>
                                  <a class="dropdown-item text-success" href="create_po.php?product_id=' . $row['PRODUCT_ID'] . '&supplier_id=' . $row['SUPPLIER_ID'] . '">
                                        <i class="fas fa-truck"></i> Reorder
                                  </a>';
                        }
                       
                 
                    
                    echo '</tr>';
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</div>


<script>
document.getElementById('notificationBell').addEventListener('click', function() {
    var notificationDiv = document.getElementById('notificationDiv');
    // Toggle display: if hidden, show it; if visible, hide it
    if (notificationDiv.style.display === 'none' || notificationDiv.style.display === '') {
        notificationDiv.style.display = 'block';
    } else {
        notificationDiv.style.display = 'none';
    }
});

// Close button for notification container.
document.getElementById('closeNotification').addEventListener('click', function() {
    var notificationDiv = document.getElementById('notificationDiv');
    if (notificationDiv) {
        notificationDiv.style.display = 'none';
    }
});

</script>



<?php
include '../includes/footer.php';
?>

<!-- JavaScript to toggle notifications -->
