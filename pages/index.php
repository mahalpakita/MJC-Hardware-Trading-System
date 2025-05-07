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
$sidebars = [1 => 'sidebar.php', 2 => 'sidebar_cashier.php', 3 => 'sidebar_manager.php'];
include '../includes/' . ($sidebars[$userTypeID] ?? die('Unauthorized User Type'));

$sections = [
    'customer' => ['Customers', 'customer', 'fas fa-child', 'customer'],
    'transaction' => ['Transaction', 'transaction', 'fas fa-retweet', 'Transaction'],
    'employee' => ['Employees', 'employee', 'fas fa-user', 'employee'],
    'user' => ['Accounts', 'user', 'fas fa-users', 'users'],
    'inventory' => ['Inventory', 'Inventory', 'fas fa-clipboard-list', 'product'],
    'edit_history' => ['Edit History', 'edit_history', 'fas fa-history', 'product_edit_history'],
    'supplier' => ['Supplier', 'supplier', 'fas fa-warehouse', 'supplier'],
    'po' => ['P.O', 'manage_po', 'fas fa-truck', 'purchase_orders']
];
?>

<div class="row show-grid">
    <?php foreach ($sections as $key => [$title, $link, $icon, $table]): ?>
        <div class="col-md-3">
            <div class="col-md-12 mb-3">
                <a href="<?= $link ?>.php" style="text-decoration: none;">
                    <div class="card border-left-<?= $key == 'transaction' || $key == 'po' ? 'danger' : ($key == 'supplier' || $key == 'user'|| $key == 'inventory' ? 'warning' : 'primary') ?> shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-0">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1 text-<?= $key == 'transaction' || $key == 'po' ? 'danger' : ($key == 'supplier' || $key == 'user' ? 'warning' : 'primary') ?>">
                                        <?= $title ?>
                                    </div>
                                    <div class="h6 mb-0 font-weight-bold text-gray-800">
                                        <?php 
                                        $query = "SELECT COUNT(*) FROM $table";
                                        $result = mysqli_query($db, $query) or die(mysqli_error($db));
                                        echo mysqli_fetch_array($result)[0] . ' Record(s)';
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="<?= $icon ?> fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<br>
<br>
<br>
<br>    
<br>     
<br>
<br>
<br>    
<br>
<br>
<br>
<br>  
<br>  
  

<?php include '../includes/footer.php'; ?>
