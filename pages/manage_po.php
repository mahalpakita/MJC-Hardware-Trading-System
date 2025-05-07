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

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4 text-center text-primary">Manage Purchase Orders</h2>
        <div class="table-responsive">
        <table class="table table-hover table-bordered text-center" id="dataTable">
            <thead>
                <tr>
                  
                    <th>Supplier</th>
                    <th>Order Date</th>
                    <th>Expected Delivery</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
          
                        <td><?php echo $row['COMPANY_NAME']; ?></td>
                        <td><?php echo date("M d, Y", strtotime($row['ORDER_DATE'])); ?></td>
                        <td><?php echo date("M d, Y", strtotime($row['EXPECTED_DELIVERY_DATE'])); ?></td>
                        <td>
                            <span class="badge 
                                <?php echo ($row['STATUS'] == 'Completed') ? 'bg-success' : 
                                            (($row['STATUS'] == 'Pending') ? 'bg-warning' : 'bg-danger'); ?>">
                                <?php echo $row['STATUS']; ?>
                            </span>
                        </td>
                        <td>
                            <a href="view_po.php?po_id=<?php echo $row['PO_ID']; ?>" class="btn btn-info btn-sm">View</a>
                            <button class="btn btn-warning btn-sm editBtn" data-id="<?php echo $row['PO_ID']; ?>" 
                                    data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                            <button class="btn btn-danger btn-sm deleteBtn" data-id="<?php echo $row['PO_ID']; ?>">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</div>
<!-- Edit PO Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editModalLabel">Edit Purchase Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST" action="update_po.php">
                <div class="modal-body">
                    <input type="hidden" name="po_id" id="po_id">
                    <div class="mb-3">
                        <label class="form-label">Expected Delivery Date:</label>
                        <input type="date" name="expected_date" id="expected_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status:</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editModal');

    editModal.addEventListener('hidden.bs.modal', function () {
        // Ensure modal and backdrop are removed properly
        document.body.classList.remove('modal-open');
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    });
});
</script>


<script>
    // Populate Edit Modal with data
    $(".editBtn").click(function() {
        let po_id = $(this).data("id");
        $("#po_id").val(po_id);
    });

    // Delete Confirmation
    $(".deleteBtn").click(function() {
        let po_id = $(this).data("id");
        if (confirm("Are you sure you want to delete this purchase order?")) {
            window.location.href = "delete_po.php?po_id=" + po_id;
        }
    });
</script>

</body>
</html>

<script>
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
                new bootstrap.Modal(document.getElementById('editModal')).show();
            });
    });
});

// Delete PO
document.querySelectorAll('.deleteBtn').forEach(button => {
    button.addEventListener('click', function() {
        let po_id = this.getAttribute('data-id');
        if (confirm("Are you sure you want to delete this PO?")) {
            window.location.href = `delete_po.php?po_id=${po_id}`;
        }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<?php include '../includes/footer.php'; ?>
