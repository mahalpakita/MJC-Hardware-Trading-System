<?php 
require_once('redirect.php');
allowOnlyAdmin();
?>


<?php
include '../includes/connection.php';


// Ensure only admin can access this page
if (!isset($_SESSION['MEMBER_ID'])) {
    die("<script>alert('Unauthorized access!'); window.location = 'login.php';</script>");
}

$admin_id = $_SESSION['MEMBER_ID'];

// Validate that the user is an admin
$query_admin = "SELECT TYPE_ID FROM users WHERE ID = ?";
$stmt_admin = mysqli_prepare($db, $query_admin);
mysqli_stmt_bind_param($stmt_admin, "i", $admin_id);
mysqli_stmt_execute($stmt_admin);
$result_admin = mysqli_stmt_get_result($stmt_admin);
$row_admin = mysqli_fetch_assoc($result_admin);

if (!$row_admin || $row_admin['TYPE_ID'] != 1) { // 1 = Admin
    die("<script>alert('Unauthorized action!'); window.history.back();</script>");
}

// Get user ID from the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<script>alert('No user selected!'); window.history.back();</script>");
}

$user_id = intval($_GET['id']); // Convert to integer for security

// Fetch user details (optional, for display)
$query_user = "SELECT USERNAME FROM users WHERE ID = ?";
$stmt_user = mysqli_prepare($db, $query_user);
mysqli_stmt_bind_param($stmt_user, "i", $user_id);
mysqli_stmt_execute($stmt_user);
$result_user = mysqli_stmt_get_result($stmt_user);
$user_data = mysqli_fetch_assoc($result_user);

if (!$user_data) {
    die("<script>alert('User not found!'); window.history.back();</script>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password for <?php echo htmlspecialchars($user_data['USERNAME']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg p-4">
                <h3 class="text-center text-primary mb-4">Change Password for <?php echo htmlspecialchars($user_data['USERNAME']); ?></h3>
                <form method="POST" action="change_password_process.php">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"> <!-- Hidden input to pass user ID -->

                    <div class="mb-3">
                        <label class="form-label">Current Password:</label>
                        <input type="password" class="form-control" placeholder="Enter Current Password" name="current_password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">New Password:</label>
                        <input type="password" class="form-control" placeholder="Enter New Password" name="new_password" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm New Password:</label>
                        <input type="password" class="form-control" placeholder="Confirm New Password" name="confirm_password" required>
                    </div>

                    <button type="submit" class="btn btn-warning w-100"><i class="fas fa-edit"></i> Update Password</button>
                    <a href="user.php" class="btn btn-secondary w-100 mt-2"><i class="fas fa-arrow-left"></i> Back</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
