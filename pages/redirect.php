<?php
include '../includes/connection.php'; // Ensure database connection is included
session_start();

if (!isset($_SESSION['MEMBER_ID'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Get user type
$query = "SELECT t.TYPE FROM users u
          JOIN type t ON t.TYPE_ID = u.TYPE_ID
          WHERE u.ID = " . intval($_SESSION['MEMBER_ID']);
$result = mysqli_query($db, $query) or die(mysqli_error($db));
$row = mysqli_fetch_assoc($result);
$userType = $row['TYPE'];

// ✅ RESTRICT FUNCTIONS: Prevent specific user types
function restrictOnlyAdmin() {
    global $userType;
    if ($userType == 'Admin') {
        echo "<script>
                window.onload = function() {
                    alert('Restricted Page! This page is not accessible for Admins.');
                    window.location = 'index.php';
                }
              </script>";
        exit();
    }
}

function restrictOnlyManager() {
    global $userType;
    if ($userType == 'Manager') {
        echo "<script>
                window.onload = function() {
                    alert('Restricted Page! This page is not accessible for Managers.');
                    window.location = 'index_manager.php';
                }
              </script>";
        exit();
    }
}

function restrictOnlyCashier() {
    global $userType;
    if ($userType == 'Cashier') {
        echo "<script>
                window.onload = function() {
                    alert('Restricted Page! This page is not accessible for Cashiers.');
                    window.location = 'pos.php';
                }
              </script>";
        exit();
    }
}

// ✅ ALLOW-ONLY FUNCTIONS: Allow one type, restrict others
function allowOnlyAdmin() {
    global $userType;
    if ($userType != 'Admin') {
        $redirectPage = ($userType == 'Manager') ? 'index_manager.php' : 'pos.php';
        echo "<script>
                window.onload = function() {
                    alert('Restricted Page! Only Admins are allowed.');
                    window.location = '$redirectPage';
                }
              </script>";
        exit();
    }
}

function allowOnlyManager() {
    global $userType;
    if ($userType != 'Manager') {
        $redirectPage = ($userType == 'Admin') ? 'index.php' : 'pos.php';
        echo "<script>
                window.onload = function() {
                    alert('Restricted Page! Only Managers are allowed.');
                    window.location = '$redirectPage';
                }
              </script>";
        exit();
    }
}

function allowOnlyCashier() {
    global $userType;
    if ($userType != 'Cashier') {
        $redirectPage = ($userType == 'Admin') ? 'index.php' : 'index_manager.php';
        echo "<script>
                window.onload = function() {
                    alert('Restricted Page! Only Cashiers are allowed.');
                    window.location = '$redirectPage';
                }
              </script>";
        exit();
    }
}
?>
