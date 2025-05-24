<?php
include '../includes/connection.php';
include '../includes/error_handler.php'; 

try {
    // Check if the 'action' parameter is set and the value is 'delete'
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $customerId = intval($_GET['id']);
        
        // Query to delete the customer from the database
        $deleteQuery = "DELETE FROM customer WHERE CUST_ID = {$customerId}";
        $deleteResult = mysqli_query($db, $deleteQuery);
        
        if ($deleteResult) {
            echo '<script type="text/javascript">
                    alert("Customer deleted successfully.");
                    window.location = "customer.php"; // Redirect to customer page
                  </script>';
        } else {
            throw new Exception("Failed to delete customer. It may be linked to existing transactions.");
        }
    }
} catch (Exception $e) {
    // Log actual error (useful for debugging)
    error_log("Error deleting customer: " . $e->getMessage());

    // Show a more user-friendly error message
    echo '<script type="text/javascript">
            alert("Error: Unable to delete customer. Please ensure there are no associated transactions.");
            window.location = "customer.php"; // Redirect to customer page
          </script>';
}
?>
