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

<!-- Required CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <h4 class="m-0 fw-bold text-primary">
                                <i class="fas fa-truck me-2"></i>Suppliers
                            </h4>
                        </div>
                        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#supplierModal">
                            <i class="fas fa-plus me-2"></i>Add Supplier
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="dataTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="fw-bold">Company Name</th>
                                    <th class="fw-bold">Province</th>
                                    <th class="fw-bold">City</th>
                                    <th class="fw-bold">Phone Number</th>
                                    <th class="fw-bold text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php                  
                                $query = 'SELECT SUPPLIER_ID, COMPANY_NAME, l.PROVINCE, l.CITY, PHONE_NUMBER 
                                        FROM supplier s 
                                        JOIN location l ON s.LOCATION_ID = l.LOCATION_ID';
                                $result = mysqli_query($db, $query) or die(mysqli_error($db));

                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<tr>';
                                    echo '<td class="fw-semibold">' . htmlspecialchars($row['COMPANY_NAME'] ?? '') . '</td>';
                                    echo '<td>' . htmlspecialchars($row['PROVINCE'] ?? '') . '</td>';
                                    echo '<td>' . htmlspecialchars($row['CITY'] ?? '') . '</td>';
                                    echo '<td>' . htmlspecialchars($row['PHONE_NUMBER'] ?? '') . '</td>';

                                    echo '<td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-light btn-sm rounded-pill dropdown-toggle" type="button" id="dropdownMenuButton' . $row['SUPPLIER_ID'] . '" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="dropdownMenuButton' . $row['SUPPLIER_ID'] . '">
                                                    <li>
                                                        <a class="dropdown-item" href="sup_searchfrm.php?action=edit&id=' . $row['SUPPLIER_ID'] . '">
                                                            <i class="fas fa-eye me-2 text-info"></i>Details
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="sup_edit.php?action=edit&id=' . $row['SUPPLIER_ID'] . '">
                                                            <i class="fas fa-edit me-2 text-warning"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="sup_del.php?action=delete&id=' . $row['SUPPLIER_ID'] . '" 
                                                           onclick="return confirm(\'Are you sure you want to delete this supplier?\')">
                                                            <i class="fas fa-trash me-2"></i>Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>';
                                    echo '</tr>';
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

<!-- Supplier Modal -->
<div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="supplierModalLabel">
                    <i class="fas fa-truck-loading me-2"></i>Add New Supplier
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form role="form" method="post" action="sup_transac.php?action=add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Company Name:</label>
                        <input type="text" class="form-control" name="companyname" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Province:</label>
                        <select class="form-select" id="province" name="province" required>
                            <option value="" disabled selected>Select Province</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">City:</label>
                        <select class="form-select" id="city" name="city" required>
                            <option value="" disabled selected>Select City</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Phone Number:</label>
                        <input type="tel" class="form-control" name="phonenumber" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Supplier
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
            searchPlaceholder: "Search suppliers..."
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip'
    });

    // Initialize all dropdowns
    var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });

    // Load provinces
    fetch('get_provinces.php')
        .then(response => response.json())
        .then(data => {
            const provinceSelect = document.getElementById('province');
            data.forEach(province => {
                const option = document.createElement('option');
                option.value = province;
                option.textContent = province;
                provinceSelect.appendChild(option);
            });
        });

    // Handle province change
    document.getElementById('province').addEventListener('change', function() {
        const citySelect = document.getElementById('city');
        citySelect.innerHTML = '<option value="" disabled selected>Select City</option>';
        
        if (this.value) {
            fetch(`get_cities.php?province=${encodeURIComponent(this.value)}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city;
                        option.textContent = city;
                        citySelect.appendChild(option);
                    });
                });
        }
    });
});
</script>
