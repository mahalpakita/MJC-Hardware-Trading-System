<?php


// Clear only the specific session variable
unset($_SESSION['EXPENSE_SESSION_ID']);

session_write_close();

// Redirect only if the request came from another page
if (isset($_GET['redirect'])) {
    header("Location: " . $_GET['redirect']);
    exit();
}
