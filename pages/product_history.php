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
$querya = "SELECT TYPE_ID FROM users WHERE ID = " . intval($_SESSION['MEMBER_ID']);
$resulta = mysqli_query($db, $querya) or die(mysqli_error($db));

if ($row = mysqli_fetch_assoc($resulta)) {
    $userTypeID = $row['TYPE_ID'];

    // Sidebar based on user type
    if ($userTypeID == 1) {
        include '../includes/sidebar.php';
    } elseif ($userTypeID == 2) {    
        include '../includes/sidebar_cashier.php';
    } elseif ($userTypeID == 3) {
        include '../includes/sidebar_manager.php';
    } else {
        die('Unauthorized User Type');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product History</title>

    <!-- SB Admin 2 CSS -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Bootstrap 4 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-history"></i> Product History</h4>
            <button onclick="window.history.back();" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="dataTable">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th onclick="sortTable(0)">Product Code <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable(1)">Name <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable(2)">Description <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable(3)">Quantity <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable(4)">Price <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable(5)">Supplier <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable(6)">Branch <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable(7)">Action <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable(8)">Date <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable(9)">User <i class="fas fa-sort"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT h.*, s.COMPANY_NAME AS SUPPLIER, b.BRANCH_NAME AS BRANCH 
                                  FROM product_history h 
                                  LEFT JOIN supplier s ON h.SUPPLIER_ID = s.SUPPLIER_ID 
                                  LEFT JOIN branches b ON h.BRANCH_ID = b.BRANCH_ID 
                                  ORDER BY h.ACTION_DATE DESC";
                        $result = mysqli_query($db, $query);

                        if ($result->num_rows > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Assign badge color based on action type
                                $actionBadge = ($row['ACTION_TYPE'] == 'Added') ? 'badge-success' : 'badge-danger';

                                echo "<tr>
                                        <td>{$row['PRODUCT_CODE']}</td>
                                        <td>{$row['NAME']}</td>
                                        <td>{$row['DESCRIPTION']}</td>
                                        <td>{$row['QTY_STOCK']}</td>
                                        <td>₱" . number_format($row['PRICE'], 2) . "</td>
                                        <td>{$row['SUPPLIER']}</td>
                                        <td>{$row['BRANCH']}</td>
                                        <td><span class='badge $actionBadge'>{$row['ACTION_TYPE']}</span></td>
                                        <td>" . date("Y-m-d h:i A", strtotime($row['ACTION_DATE'])) . "</td>
                                        <td>{$row['USER']}</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10' class='text-center text-muted'>No product history available.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function sortTable(columnIndex) {
    var table = document.getElementById("dataTable");
    var rows = Array.from(table.rows).slice(1); // Exclude the header row
    var isAscending = table.getAttribute("data-order") === "asc";

    rows.sort((rowA, rowB) => {
        var cellA = rowA.cells[columnIndex].innerText.trim();
        var cellB = rowB.cells[columnIndex].innerText.trim();

        // Convert numeric values
        var numA = parseFloat(cellA.replace(/[^0-9.-]/g, ""));
        var numB = parseFloat(cellB.replace(/[^0-9.-]/g, ""));
        if (!isNaN(numA) && !isNaN(numB)) {
            return isAscending ? numA - numB : numB - numA;
        }

        return isAscending ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
    });

    table.setAttribute("data-order", isAscending ? "desc" : "asc");

    // Append sorted rows back to the table
    rows.forEach(row => table.tBodies[0].appendChild(row));
}
</script>

<!-- Bootstrap 4 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php include '../includes/footer.php'; ?>
