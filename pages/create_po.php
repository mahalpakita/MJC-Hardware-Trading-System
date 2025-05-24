<?php 
require_once('redirect.php');
restrictOnlyCashier();
include '../includes/connection.php';

if (!isset($_SESSION['MEMBER_ID'])) {
    header("Location: login.php");
    exit();
}

// Fetch user role
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
}
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <h4 class="m-0 fw-bold text-primary">
                                <i class="fas fa-file-invoice me-2"></i>Create Purchase Order
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="process_po.php" method="POST">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="supplier_id" class="form-label fw-semibold">Select Supplier:</label>
                                    <select name="supplier_id" id="supplier_id" class="form-select" required>
                                        <option disabled selected hidden>Select Supplier</option>
                                        <?php
                                        $supplier_selected = isset($_GET['supplier_id']) ? $_GET['supplier_id'] : '';
                                        $sql2 = "SELECT DISTINCT SUPPLIER_ID, COMPANY_NAME FROM supplier ORDER BY COMPANY_NAME ASC";
                                        $result2 = mysqli_query($db, $sql2) or die("Error: " . mysqli_error($db));

                                        while ($row = mysqli_fetch_assoc($result2)) {
                                            $selected = ($row['SUPPLIER_ID'] == $supplier_selected) ? "selected" : "";
                                            echo "<option value='" . $row['SUPPLIER_ID'] . "' $selected>" . $row['COMPANY_NAME'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="order_date" class="form-label fw-semibold">Order Date:</label>
                                    <input type="datetime-local" name="order_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="expected_date" class="form-label fw-semibold">Expected Delivery Date:</label>
                                    <input type="date" name="expected_date" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0 rounded-3 mb-4">
                            <div class="card-header bg-light py-3">
                                <h5 class="m-0 fw-semibold">Add Products</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="fw-bold">Product</th>
                                                <th class="fw-bold">Quantity</th>
                                                <th class="fw-bold">Unit Price</th>
                                                <th class="fw-bold text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="productRows">
                                            <tr>
                                                <td>
                                                    <select name="product_id[]" class="form-select product-select" required>
                                                        <option value="">Select Supplier First</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="quantity[]" class="form-control" min="1" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="unit_price[]" class="form-control" readonly>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger btn-sm rounded-pill removeRow">
                                                        <i class="fas fa-trash me-1"></i>Remove
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex gap-2 mt-3">
                                    <button type="button" id="addRow" class="btn btn-primary rounded-pill px-4">
                                        <i class="fas fa-plus me-2"></i>Add Product
                                    </button>
                                    <button type="submit" class="btn btn-success rounded-pill px-4">
                                        <i class="fas fa-save me-2"></i>Submit PO
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<!-- Required Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

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

<!-- Add Bootstrap CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
