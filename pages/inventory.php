<?php 
require_once('redirect.php');
allowOnlyAdmin();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../includes/connection.php';

// Set consistent collation for the connection
mysqli_set_charset($db, "utf8mb4");
mysqli_query($db, "SET NAMES utf8mb4 COLLATE utf8mb4_general_ci");

// Debug database connection
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['MEMBER_ID'])) {
    header("Location: login.php");
    exit();
}

// Fetch user role with error logging
$querya = "SELECT u.TYPE_ID FROM users u WHERE u.ID = " . intval($_SESSION['MEMBER_ID']);
$resulta = mysqli_query($db, $querya);
if (!$resulta) {
    error_log("Error fetching user role: " . mysqli_error($db));
    die("Error fetching user role: " . mysqli_error($db));
}

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

// Debug: Log the current user's ID and type
error_log("Current User ID: " . $_SESSION['MEMBER_ID']);
error_log("Current User Type: " . $userTypeID);
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
                                <i class="fas fa-boxes me-2"></i>Inventory Management
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
                        <button type="button" class="btn btn-primary rounded-pill px-4" onclick="openProductModal()">
                            <i class="fas fa-plus me-2"></i>Add New Item
                        </button>
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
                                    <th class="fw-bold">Date Stock In</th>
                                    <th class="fw-bold">Status</th>
                                    <th class="fw-bold">Branch</th>
                                    <?php if ($userTypeID != 3) { echo '<th class="fw-bold text-center">Actions</th>'; } ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $queryUser = "SELECT TYPE_ID, BRANCH_ID FROM users WHERE ID = " . intval($_SESSION['MEMBER_ID']);
                            $resultUser = mysqli_query($db, $queryUser);
                            if (!$resultUser) {
                                error_log("Error fetching user details: " . mysqli_error($db));
                                die("Error fetching user details: " . mysqli_error($db));
                            }

                            if ($rowUser = mysqli_fetch_assoc($resultUser)) {
                                $userTypeID = $rowUser['TYPE_ID'];
                                $userBranchID = $rowUser['BRANCH_ID'];

                                // Debug: Log user branch ID
                                error_log("User Branch ID: " . $userBranchID);

                                // Updated Query with error logging
                                $query = 'SELECT p.PRODUCT_ID, p.SUPPLIER_ID, p.NAME, p.DESCRIPTION, p.QTY_STOCK, p.PRICE, p.DATE_STOCK_IN, p.EXPIRATION_DATE, b.BRANCH_NAME 
                                          FROM product p 
                                          LEFT JOIN branches b ON p.BRANCH_ID = b.BRANCH_ID';

                                if ($userTypeID == 2 || $userTypeID == 3) {
                                    $query .= " WHERE p.BRANCH_ID = " . intval($userBranchID);
                                }

                                // Debug: Log the final query
                                error_log("Final Query: " . $query);

                                $result = mysqli_query($db, $query);
                                if (!$result) {
                                    error_log("Error fetching products: " . mysqli_error($db));
                                    die("Error fetching products: " . mysqli_error($db));
                                }

                                // Debug: Log number of products found
                                $num_rows = mysqli_num_rows($result);
                                error_log("Number of products found: " . $num_rows);

                                while ($row = mysqli_fetch_assoc($result)) {
                                    // Debug: Log each product's data
                                    error_log("Processing product: " . print_r($row, true));

                                    $status = "Available";
                                    $current_date = date('Y-m-d');
                                    $expiration_date = $row['EXPIRATION_DATE'];
                                    $product_name = htmlspecialchars($row['NAME']);
                                    $product_desc = htmlspecialchars($row['DESCRIPTION']);
                                    
                                    // Format the date stock in
                                    $date_stock_in = !empty($row['DATE_STOCK_IN']) ? date('M d, Y', strtotime($row['DATE_STOCK_IN'])) : 'N/A';
                                    
                                    date_default_timezone_set('Asia/Manila');
                                    $current_time = date("Y-m-d h:i A");
                                    $product_id = $row['PRODUCT_ID'];

                                    if (!function_exists('getLatestNotification')) {
                                        function getLatestNotification($db, $product_id, $keyword) {
                                            $message = null;
                                            // Modified query to handle collation properly
                                            $stmt = $db->prepare("SELECT MESSAGE FROM stock_notifications 
                                                                WHERE PRODUCT_ID = ? 
                                                                AND LOWER(CONVERT(MESSAGE USING utf8mb4)) LIKE LOWER(CONVERT(? USING utf8mb4)) 
                                                                ORDER BY NOTIF_TIME DESC LIMIT 1");
                                            
                                            if (!$stmt) {
                                                error_log("Prepare failed: " . $db->error);
                                                return null;
                                            }

                                            $stmt->bind_param("is", $product_id, $keyword);
                                            
                                            if (!$stmt->execute()) {
                                                error_log("Execute failed: " . $stmt->error);
                                                $stmt->close();
                                                return null;
                                            }

                                            $stmt->bind_result($message);
                                            $stmt->fetch();
                                            $stmt->close();
                                            
                                            return $message;
                                        }
                                    }
                                    
                                    if (!function_exists('insertNotification')) {
                                        function insertNotification($db, $product_id, $message) {
                                            // Modified query to handle collation properly
                                            $stmt = $db->prepare("INSERT INTO stock_notifications (PRODUCT_ID, MESSAGE) VALUES (?, CONVERT(? USING utf8mb4))");
                                            
                                            if (!$stmt) {
                                                error_log("Prepare failed: " . $db->error);
                                                return false;
                                            }

                                            $stmt->bind_param("is", $product_id, $message);
                                            
                                            if (!$stmt->execute()) {
                                                error_log("Execute failed: " . $stmt->error);
                                                $stmt->close();
                                                return false;
                                            }

                                            $stmt->close();
                                            return true;
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
                                    echo '<td>' . $date_stock_in . '</td>';
                                    echo '<td><span class="badge ' . $statusClass . ' rounded-pill px-3 py-2">' . $status . '</span></td>';
                                    echo '<td>' . htmlspecialchars($row['BRANCH_NAME']) . '</td>';

                                    if ($userTypeID != 3) {
                                        echo '<td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-light btn-sm rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                        <li>
                                                            <a class="dropdown-item" href="inv_searchfrm.php?action=edit&id=' . $row['PRODUCT_ID'] . '">
                                                                <i class="fas fa-list-alt me-2 text-info"></i>Details
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="inv_edit.php?action=edit&id=' . $row['PRODUCT_ID'] . '">
                                                                <i class="fas fa-edit me-2 text-warning"></i>Edit
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="inv_del.php?action=delete&id=' . $row['PRODUCT_ID'] . '" 
                                                               onclick="return confirm(\'Are you sure you want to delete this item?\')">
                                                                <i class="fas fa-trash me-2"></i>Delete
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                              </td>';
                                    }
                                    echo '</tr>';
                                }
                            } else {
                                error_log("No user details found for ID: " . $_SESSION['MEMBER_ID']);
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

<!-- JavaScript for notifications -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap Toast
    var toastEl = document.getElementById('notificationToast');
    if (toastEl) {
        var toast = new bootstrap.Toast(toastEl, {
            autohide: false
        });

        // Show notifications when bell is clicked
        var notificationBell = document.getElementById('notificationBell');
        if (notificationBell) {
            notificationBell.addEventListener('click', function() {
                toast.show();
            });
        }
    }

    // Initialize DataTable
    var dataTable = document.getElementById('dataTable');
    if (dataTable) {
        $('#dataTable').DataTable({
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search inventory..."
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip'
        });
    }

    // Initialize modal
    const modalElement = document.getElementById('aModal');
    if (modalElement) {
        // Set initial z-index
        modalElement.style.zIndex = 1050;
        
        // Add click event listener to ensure modal is clickable
        modalElement.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Handle modal shown event
        modalElement.addEventListener('shown.bs.modal', function () {
            // Ensure modal is on top
            modalElement.style.zIndex = 1050;
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.style.zIndex = 1040;
            }
        });

        // Add form validation
        const form = modalElement.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const dateInput = document.getElementById('datestock');
                if (dateInput && !dateInput.value) {
                    e.preventDefault();
                    alert('Please select a valid date');
                    dateInput.value = '<?php echo date('Y-m-d'); ?>';
                }
            });
        }
    }
});

// Global modal instance
let productModalInstance = null;

function openProductModal() {
    const modalElement = document.getElementById('aModal');
    if (!modalElement) return;

    if (!productModalInstance) {
        productModalInstance = new bootstrap.Modal(modalElement, {
            backdrop: true,
            keyboard: true
        });
    }
    
    // Reset form if it exists
    const form = modalElement.querySelector('form');
    if (form) {
        form.reset();
    }
    
    // Show modal
    productModalInstance.show();
    
    // Ensure modal is clickable
    setTimeout(() => {
        modalElement.style.pointerEvents = 'auto';
        modalElement.style.zIndex = 1050;
    }, 100);
}

// Handle modal events
const modalElement = document.getElementById('aModal');
if (modalElement) {
    modalElement.addEventListener('hidden.bs.modal', function () {
        // Remove backdrop
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
        // Remove modal-open class and reset body styles
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    });
}

// Handle escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && productModalInstance) {
        productModalInstance.hide();
    }
});

// Handle window resize
window.addEventListener('resize', function() {
    if (productModalInstance) {
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }
});
</script>

<!-- Add Bootstrap CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<style>
.modal {
    pointer-events: auto !important;
}
.modal-dialog {
    pointer-events: auto !important;
}
.modal-content {
    pointer-events: auto !important;
}
.modal-backdrop {
    z-index: 1040 !important;
}
.modal {
    z-index: 1050 !important;
}
</style>
