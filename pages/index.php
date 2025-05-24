<?php 
require_once('redirect.php');

?>

<?php 

ob_start(); // Prevents header errors
include '../includes/connection.php';

// Debugging: Uncomment if needed
// echo '<pre>'; print_r($_SESSION); echo '</pre>'; die();

if (!isset($_SESSION['MEMBER_ID'])) {
    header("Location: login.php");
    exit();
}

// Fetch user type
$query = "SELECT TYPE_ID FROM users WHERE ID = " . intval($_SESSION['MEMBER_ID']);
$result = mysqli_query($db, $query) or die(mysqli_error($db));
$userTypeID = mysqli_fetch_assoc($result)['TYPE_ID'] ?? die('User not found');

// Include the correct sidebar
$sidebars = [1 => 'sidebar.php', 2 => 'sidebar_cashier.php', 3 => 'sidebar_manger.php'];
include '../includes/' . ($sidebars[$userTypeID] ?? die('Unauthorized User Type'));

$sections = [
    'customer' => ['customers', 'customer', 'fas fa-users', 'customer', 'primary'],
    'transaction' => ['transactions', 'transaction', 'fas fa-exchange-alt', 'transaction', 'warning'],
    'employee' => ['employees', 'employee', 'fas fa-user-tie', 'employee', 'success'],
    'user' => ['accounts', 'user', 'fas fa-user-shield', 'users', 'info'],
    'inventory' => ['inventory', 'inventory', 'fas fa-boxes', 'product', 'warning'],
    'edit_history' => ['edit History', 'edit_history', 'fas fa-history', 'product_edit_history', 'secondary'],
    'supplier' => ['suppliers', 'supplier', 'fas fa-warehouse', 'supplier', 'warning'],
    'po' => ['purchase Orders', 'manage_po', 'fas fa-truck', 'purchase_orders', 'danger']
];
?>

<div class="container-fluid py-4">
    <div class="row g-4">
        <?php foreach ($sections as $key => [$title, $link, $icon, $table, $color]): ?>
            <div class="col-md-3">
                <a href="<?= $link ?>.php" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="text-<?= $color ?> fw-bold mb-1"><?= $title ?></h6>
                                    <h3 class="mb-0 fw-bold">
                                        <?php 
                                        $query = "SELECT COUNT(*) FROM $table";
                                        $result = mysqli_query($db, $query) or die(mysqli_error($db));
                                        echo mysqli_fetch_array($result)[0];
                                        ?>
                                    </h3>
                                </div>
                                <div class="p-3">
                                    <i class="<?= $icon ?> fa-2x text-<?= $color ?>"></i>
                                </div>
                            </div>
                            <p class="text-muted mb-0">Total <?= strtolower($title) ?> records</p>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<!-- Add Bootstrap CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<style>
.hover-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.bg-opacity-10 {
    opacity: 0.1;
}

.card {
    border-radius: 1rem;
}

.card-body {
    border-radius: 1rem;
}

.text-muted {
    font-size: 0.875rem;
}
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
