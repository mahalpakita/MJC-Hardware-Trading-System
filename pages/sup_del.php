<?php 
require_once('redirect.php');
allowOnlyAdmin();
?>


<?php
include '../includes/connection.php';
include '../includes/error_handler.php';

try {
// Check if the 'action' parameter is set and the value is 'delete'
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $supplierId = intval($_GET['id']);
    
    // Query to delete the supplier from the database
    $deleteQuery = "DELETE FROM supplier WHERE SUPPLIER_ID = {$supplierId}";
    $deleteResult = mysqli_query($db, $deleteQuery);
    
    if ($deleteResult) {
        echo '<script type="text/javascript">
                alert("Supplier deleted successfully.");
                window.location = "supplier.php"; // Redirect to supplier management page
              </script>';
    } else {
        echo '<script type="text/javascript">
                alert("Error deleting supplier. Please try again.");
                window.location = "supplier.php"; // Redirect to supplier management page
              </script>';
    }
}
} catch (Exception $e) {
  mysqli_rollback($db); // Rollback on error
  showError($e->getMessage());
}
?>
