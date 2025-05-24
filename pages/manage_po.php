<?php 
include '../includes/connection.php';
session_start();

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

// Fetch all purchase orders
$sql = "SELECT po.PO_ID, s.COMPANY_NAME, po.ORDER_DATE, po.EXPECTED_DELIVERY_DATE, po.STATUS
        FROM purchase_orders po
        JOIN supplier s ON po.SUPPLIER_ID = s.SUPPLIER_ID
        ORDER BY po.ORDER_DATE DESC";
$result = mysqli_query($db, $sql) or die(mysqli_error($db));

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Purchase Orders</title>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 90%;
        }
        .table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        .table th {
            background: #343a40;
            color: white;
            text-align: center;
        }
        .table tbody tr:hover {
            background: #f1f3f5;
        }
        .btn {
            border-radius: 6px;
        }
        .modal-content {
            border-radius: 10px;
        }
    </style>
    
</head>
<body>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <h4 class="m-0 fw-bold text-primary">
                                <i class="fas fa-shopping-cart me-2"></i>Purchase Orders
                            </h4>
                        </div>
                        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#newPOModal">
                            <i class="fas fa-plus me-2"></i>New Purchase Order
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="dataTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="fw-bold">Supplier</th>
                                    <th class="fw-bold">Order Date</th>
                                    <th class="fw-bold">Expected Delivery</th>
                                    <th class="fw-bold">Status</th>
                                    <th class="fw-bold text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)) { 
                                    $statusClass = match($row['STATUS']) {
                                        'Completed' => 'bg-success',
                                        'Pending' => 'bg-warning',
                                        default => 'bg-danger'
                                    };
                                ?>
                                    <tr>
                                        <td class="fw-semibold"><?php echo htmlspecialchars($row['COMPANY_NAME']); ?></td>
                                        <td><?php echo date("M d, Y", strtotime($row['ORDER_DATE'])); ?></td>
                                        <td><?php echo date("M d, Y", strtotime($row['EXPECTED_DELIVERY_DATE'])); ?></td>
                                        <td>
                                            <span class="badge <?php echo $statusClass; ?> rounded-pill px-3 py-2">
                                                <?php echo $row['STATUS']; ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-light btn-sm rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                    <li>
                                                        <a class="dropdown-item" href="view_po.php?po_id=<?php echo $row['PO_ID']; ?>">
                                                            <i class="fas fa-eye me-2 text-info"></i>View
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item editBtn" href="#" data-id="<?php echo $row['PO_ID']; ?>" 
                                                           data-bs-toggle="modal" data-bs-target="#editModal">
                                                            <i class="fas fa-edit me-2 text-warning"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger deleteBtn" href="#" data-id="<?php echo $row['PO_ID']; ?>">
                                                            <i class="fas fa-trash me-2"></i>Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit PO Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Purchase Order
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST" action="update_po.php">
                <div class="modal-body">
                    <input type="hidden" name="po_id" id="po_id">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Expected Delivery Date:</label>
                        <input type="date" name="expected_date" id="expected_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status:</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Changes
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
    // Initialize DataTable
    $('#dataTable').DataTable({
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search purchase orders..."
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip'
    });

    // Edit PO
    document.querySelectorAll('.editBtn').forEach(button => {
        button.addEventListener('click', function() {
            let po_id = this.getAttribute('data-id');
            fetch(`get_po.php?po_id=${po_id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('po_id').value = data.PO_ID;
                    document.getElementById('expected_date').value = data.EXPECTED_DELIVERY_DATE;
                    document.getElementById('status').value = data.STATUS;
                });
        });
    });

    // Delete PO
    document.querySelectorAll('.deleteBtn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            let po_id = this.getAttribute('data-id');
            if (confirm("Are you sure you want to delete this purchase order?")) {
                window.location.href = `delete_po.php?po_id=${po_id}`;
            }
        });
    });
});
</script>
<!-- Add Bootstrap CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

</body>
</html>
