<?php 
require_once('redirect.php');
restrictOnlyCashier();
?>
<?php 
include '../includes/connection.php';

if (!isset($_SESSION['MEMBER_ID'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
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
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Purchase Order</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- You may include Bootstrap CSS/JS if not already included -->
</head>
<body>

<?php
// Assume $db connection is available or include your connection file here:
include '../includes/connection.php';
?>

<!-- Purchase Order Form -->
<div class="mb-3">  
    <form action="process_po.php" method="POST">
        <!-- Select Supplier -->
        <label for="supplier_id" class="form-label">Select Supplier:</label>
        <select name="supplier_id" id="supplier_id" class="form-control" required>
            <option disabled selected hidden>Select Supplier</option>
            <?php
            // Pre-select supplier if passed via GET parameters
            $supplier_selected = isset($_GET['supplier_id']) ? $_GET['supplier_id'] : '';
            $sql2 = "SELECT DISTINCT SUPPLIER_ID, COMPANY_NAME FROM supplier ORDER BY COMPANY_NAME ASC";
            $result2 = mysqli_query($db, $sql2) or die("Error: " . mysqli_error($db));

            while ($row = mysqli_fetch_assoc($result2)) {
                $selected = ($row['SUPPLIER_ID'] == $supplier_selected) ? "selected" : "";
                echo "<option value='" . $row['SUPPLIER_ID'] . "' $selected>" . $row['COMPANY_NAME'] . "</option>";
            }
            ?>
        </select>

        <!-- Order Date -->
        <label for="order_date" class="form-label">Order Date:</label>
        <input type="datetime-local" name="order_date" class="form-control" required>

        <!-- Expected Delivery Date -->
        <label for="expected_date" class="form-label">Expected Delivery Date:</label>
        <input type="date" name="expected_date" class="form-control" required>

        <!-- products Table -->
        <h5>Add products</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="productRows">
                <tr>
                    <td>
                        <select name="product_id[]" class="form-control product-select" required>
                            <!-- Will be dynamically populated via AJAX -->
                            <option value="">Select Supplier First</option>
                        </select>
                    </td>
                    <td><input type="number" name="quantity[]" class="form-control" min="1" required></td>
                    <td><input type="text" name="unit_price[]" class="form-control" readonly></td>
                    <td><button type="button" class="btn btn-danger removeRow">Remove</button></td>
                </tr>
            </tbody>
        </table>
        <button type="button" id="addRow" class="btn btn-primary">Add product</button>
        <button type="submit" class="btn btn-success">Submit PO</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Get preselected parameters from URL (set by PHP)
    var preselectedSupplier = "<?php echo isset($_GET['supplier_id']) ? $_GET['supplier_id'] : ''; ?>";
    var preselectedproduct = "<?php echo isset($_GET['product_id']) ? $_GET['product_id'] : ''; ?>";

    // Modified function to update the product dropdown with an optional callback
    function updateproductDropdown(supplierID, dropdown, callback) {
        if (!supplierID) {
            dropdown.html('<option value="">Select Supplier First</option>');
            if(callback) callback();
            return;
        }

        // Fetch products based on supplier via AJAX
        $.ajax({
            url: "fetch_products.php",
            type: "POST",
            data: { supplier_id: supplierID },
            success: function(response) {
                dropdown.html(response);
                if(callback) callback();
            }
        });
    }

    // When supplier is changed, update all product dropdowns
    $("#supplier_id").change(function() {
        var supplierID = $(this).val();
        $(".product-select").each(function() {
            updateproductDropdown(supplierID, $(this));
        });
    });

    // Bind product selection event to update the corresponding unit price field
    function bindproductEvents(row) {
        row.find(".product-select").change(function() {
            let selectedOption = $(this).find(":selected");
            row.find("input[name='unit_price[]']").val(selectedOption.data("price") || "0.00");
        });
    }

    // Add product Row functionality
    $("#addRow").click(function() {
        let newRow = $("#productRows tr:first").clone();
        newRow.find("input").val(""); // Clear input values

        // Get selected supplier ID
        let supplierID = $("#supplier_id").val();
        updateproductDropdown(supplierID, newRow.find(".product-select"));

        $("#productRows").append(newRow);
        bindproductEvents(newRow);
    });

    // Remove Row functionality
    $(document).on("click", ".removeRow", function() {
        if ($("#productRows tr").length > 1) {
            $(this).closest("tr").remove();
        }
    });

    // Initially bind events for the first row
    bindproductEvents($("#productRows tr:first"));

    // If supplier is preselected via GET, trigger change and update the first row's product dropdown
    if(preselectedSupplier !== "") {
        $("#supplier_id").val(preselectedSupplier).trigger('change');

        // Use callback to wait until the first product dropdown is populated, then set the product
        updateproductDropdown(preselectedSupplier, $("#productRows tr:first .product-select"), function(){
            if(preselectedproduct !== "") {
                $("#productRows tr:first .product-select").val(preselectedproduct).trigger('change');
            }
        });
    }
});
</script>

</body>
</html>
