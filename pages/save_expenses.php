<?php 
require_once('redirect.php');
restrictOnlyCashier();
?>

<?php
require '../includes/connection.php';


// Redirect if not logged in
if (!isset($_SESSION['MEMBER_ID'])) {
    header("Location: login.php");
    exit();
}

// Generate a unique session ID for tracking current session expenses
if (!isset($_SESSION['EXPENSE_SESSION_ID'])) {
    $_SESSION['EXPENSE_SESSION_ID'] = uniqid();
}

$session_id = $_SESSION['EXPENSE_SESSION_ID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expense_names = $_POST['expense_name'] ?? [];
    $expense_amounts = $_POST['expense_amount'] ?? [];

    if (!empty($expense_names) && !empty($expense_amounts)) {
        foreach ($expense_names as $key => $expense_name) {
            $expense_name = trim($expense_name);
            $amount = floatval($expense_amounts[$key]);

            if (!empty($expense_name) && $amount > 0) {
                $query = "INSERT INTO expenses (expense_name, expense_amount, session_id, created_at) VALUES (?, ?, ?, NOW())";
                if ($stmt = $db->prepare($query)) {
                    $stmt->bind_param("sds", $expense_name, $amount, $session_id);
                    $stmt->execute();
                    $stmt->close();
                } else {
                    die("Database error: " . $db->error);
                }
            }
        }
    }
}

// Redirect back to the expenses page
header("Location: expenses.php");
exit();
?>
