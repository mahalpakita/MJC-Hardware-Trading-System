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

    // Sidebar based on user type
    if ($userTypeID == 1) {
        include '../includes/sidebar.php';
    } elseif ($userTypeID == 2) {    
        include '../includes/sidebar_cashier.php';
    } elseif ($userTypeID == 3) {
        include '../includes/sidebar_manger.php';
    } else {
        die('Unauthorized User Type');
    }
}

// Initialize notification session
if (!isset($_SESSION['notifications'])) {
    $_SESSION['notifications'] = [];
}
?>

<!-- Hidden Notification Container -->
<?php 
if (!empty($_SESSION['notifications'])) {
    echo '<div id="notificationDiv" class="alert alert-warning alert-dismissible fade show" role="alert" style="display:none;">';
    foreach ($_SESSION['notifications'] as $note) {
        echo "<p>$note</p>";
    }
    // Remove the data-dismiss attribute so the element is not removed from the DOM.
    echo '<button type="button" class="close" id="closeNotification" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>';
    echo '</div>';
    unset($_SESSION['notifications']); // Clear notifications after showing
}
?>

<div class="card shadow-lg rounded-lg border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3 rounded-top">
        <div class="d-flex align-items-center">
            <h4 class="m-0 font-weight-bold">Inventory</h4>
            <!-- Bell button for notifications -->
            <button id="notificationBell" class="btn btn-light text-primary ml-3 shadow-sm rounded-circle">
                <i class="fas fa-bell"></i>
            </button>
        </div>
        <a href="#" data-toggle="modal" data-target="#aModal" class="btn btn-light text-primary shadow-sm rounded-circle">
            <i class="fas fa-plus"></i>
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-light text-dark">
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Branch</th>
                        <?php if ($userTypeID != 3) { echo '<th>Action</th>'; } ?>
                    </tr>
                </thead>
                <tbody>
                <?php
                $queryUser = "SELECT TYPE_ID, BRANCH_ID FROM users WHERE ID = " . intval($_SESSION['MEMBER_ID']);
                $resultUser = mysqli_query($db, $queryUser) or die(mysqli_error($db));

                if ($rowUser = mysqli_fetch_assoc($resultUser)) {
                    $userTypeID = $rowUser['TYPE_ID'];
                    $userBranchID = $rowUser['BRANCH_ID'];

                    // Updated Query to fetch inventory, including SUPPLIER_ID for reorder functionality
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
                        
                        date_default_timezone_set('Asia/Manila');
                        $current_time = date("Y-m-d h:i A");
                        $product_id = $row['PRODUCT_ID']; // you already have this in your select
                        if (!function_exists('getLatestNotification')) {
                        function getLatestNotification($db, $product_id, $keyword) {
                            $message = null; // ✅ Initialize to avoid "undefined variable" error
                        
                            $stmt = $db->prepare("SELECT MESSAGE FROM stock_notifications 
                            WHERE PRODUCT_ID = ? AND LOWER(MESSAGE) LIKE CONCAT('%', LOWER(?), '%') 
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
                        echo '<td>' . $product_name . '</td>';
                        echo '<td>' . htmlspecialchars($row['DESCRIPTION']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['QTY_STOCK']) . '</td>';
                        echo '<td>' . number_format($row['PRICE'], 2) . '</td>';
                        echo '<td class="fw-bold ' . 
                            ($status == "Expired" ? 'text-danger' : 
                            ($status == "Out of Stock" ? 'text-danger' : 
                            ($status == "Low Stock" ? 'text-warning' : 
                            ($status == "Expiration Near" ? 'text-warning' : 'text-success')))) . 
                            '">' . $status . '</td>';
                        echo '<td>' . htmlspecialchars($row['BRANCH_NAME']) . '</td>';

                        if ($userTypeID != 3) {
                            echo '<td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 dropdown-toggle" data-toggle="dropdown">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right shadow-sm">
                                            <a class="dropdown-item text-info" href="inv_searchfrm.php?action=edit&id=' . $row['PRODUCT_ID'] . '">
                                                <i class="fas fa-list-alt"></i> Details
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-warning" href="inv_edit.php?action=edit&id=' . $row['PRODUCT_ID'] . '">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>';
                                
                            echo '<div class="dropdown-divider"></div>
                                  <a class="dropdown-item text-danger" href="inv_del.php?action=delete&id=' . $row['PRODUCT_ID'] . '" onclick="return confirm(\'Are you sure you want to delete this item?\')">
                                      <i class="fas fa-trash"></i> Delete
                                  </a>
                                </div>
                              </div>
                              </td>';
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

<?php include '../includes/footer.php'; ?>

<!-- JavaScript to toggle notifications -->
<script>
// Toggle notification container when bell is clicked.
document.getElementById('notificationBell').addEventListener('click', function() {
    var notificationDiv = document.getElementById('notificationDiv');
    if (notificationDiv) {
        if (notificationDiv.style.display === 'none' || notificationDiv.style.display === '') {
            notificationDiv.style.display = 'block';
        } else {
            notificationDiv.style.display = 'none';
        }
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
