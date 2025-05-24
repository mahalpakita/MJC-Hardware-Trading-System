<?php 
require_once('redirect.php');
allowOnlyAdmin();
?>
<?php
include '../includes/connection.php';

if (isset($_GET['action']) && $_GET['action'] === 'add') {
    // Get user input
    $emp = $_POST['empid'] ?? null;
    $user = $_POST['username'] ?? null;
    $pass = $_POST['password'] ?? null;

    // Check for empty fields
    if (empty($emp) || empty($user) || empty($pass)) {
        echo "<script>
                alert('All fields are required.');
                window.history.back();
              </script>";
        exit;
    }

    // Check if username already exists
    $checkQuery = "SELECT * FROM users WHERE USERNAME = ?";
    $stmt = mysqli_prepare($db, $checkQuery);
    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>
                alert('Username is already taken. Please choose another.');
                window.history.back();
              </script>";
        exit;
    }
    mysqli_stmt_close($stmt);

    // Fetch BRANCH_ID from the employee table
    $branchQuery = "SELECT BRANCH_ID FROM employee WHERE EMPLOYEE_ID = ?";
    $stmt = mysqli_prepare($db, $branchQuery);
    mysqli_stmt_bind_param($stmt, "i", $emp);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    if (!$row) {
        echo "<script>
                alert('Invalid Employee ID.');
                window.history.back();
              </script>";
        exit;
    }

    $branch_id = $row['BRANCH_ID'];
    mysqli_stmt_close($stmt);

    // Hash the password securely
    $hashed_password = sha1($pass);

    // Insert new user into the users table with default STATUS 'Enable'
    $query = "INSERT INTO users (EMPLOYEE_ID, USERNAME, PASSWORD, TYPE_ID, BRANCH_ID, STATUS)
              VALUES (?, ?, ?, '2', ?, 'Enable')";

    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "issi", $emp, $user, $hashed_password, $branch_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
                alert('User successfully added.');
                window.location = 'user.php';
              </script>";
    } else {
        echo "<script>
                alert('Error adding user.');
                window.history.back();
              </script>";
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($db);
}
?>
