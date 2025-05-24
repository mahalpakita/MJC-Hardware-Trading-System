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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Edit History</title>

    <!-- SB Admin 2 CSS (Includes Nunito Font) -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Bootstrap 4 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

  
</head>
<body>
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-history"></i> Product Edit History</h4>
            <a href="product_history.php" class="btn btn-light btn-sm">
            <i class="fas fa-list"></i> View Full History
        </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="dataTable">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th onclick="sortTable(0)">Product <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable(1)">Edited By <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable(2)">Field Changed <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable(3)">Old Value <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable(4)">New Value <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable(5)">Difference <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable(6)">Date Edited <i class="fas fa-sort"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT 
                                    p.NAME AS product_name, 
                                    e.FIRST_NAME, 
                                    e.LAST_NAME, 
                                    h.FIELD_CHANGED, 
                                    h.OLD_VALUE, 
                                    h.NEW_VALUE, 
                                    h.EDIT_TIME 
                                FROM product_edit_history h
                                JOIN product p ON h.PRODUCT_ID = p.PRODUCT_ID
                                JOIN users u ON h.USER_ID = u.ID
                                JOIN employee e ON u.EMPLOYEE_ID = e.EMPLOYEE_ID
                                ORDER BY h.EDIT_TIME DESC";

                        $result = $db->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $difference = "Updated";
                                if (is_numeric($row['OLD_VALUE']) && is_numeric($row['NEW_VALUE'])) {
                                    $diff = $row['NEW_VALUE'] - $row['OLD_VALUE'];
                                    $difference = ($diff >= 0) ? "+$diff" : "$diff";
                                }

                               // Assign colors based on field changed
                                $fieldBadge = "badge-secondary";
                                $field = strtolower($row['FIELD_CHANGED']);

                                if (strpos($field, 'price') !== false) {
                                    $fieldBadge = "badge-danger";
                                } elseif (strpos($field, 'quantity') !== false) { // Ensure it's lowercase
                                    $fieldBadge = "badge-warning";
                                } elseif (strpos($field, 'expiration date') !== false) { // Ensure it's lowercase
                                    $fieldBadge = "badge-info";
                                }

                                echo "<tr>
                                        <td>{$row['product_name']}</td>
                                        <td>{$row['FIRST_NAME']} {$row['LAST_NAME']}</td>
                                        <td><span class='badge $fieldBadge'>{$row['FIELD_CHANGED']}</span></td>
                                        <td class='text-muted'>{$row['OLD_VALUE']}</td>
                                        <td><strong>{$row['NEW_VALUE']}</strong></td>
                                        <td><span class='text-primary'>{$difference}</span></td>
                                        <td>" . date("Y-m-d h:i A", strtotime($row['EDIT_TIME'])) . "</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center text-muted'>No edit history available.</td></tr>";
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
    var table = document.getElementById("historyTable");
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

</body>
</html>

<?php
include '../includes/footer.php';
?>