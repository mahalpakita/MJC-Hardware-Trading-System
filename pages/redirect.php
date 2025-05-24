<?php
include '../includes/connection.php'; // Ensure database connection is included
session_start();

if (!isset($_SESSION['MEMBER_ID'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Get user type
$query = "SELECT TYPE_ID FROM users WHERE ID = " . intval($_SESSION['MEMBER_ID']);
$result = mysqli_query($db, $query) or die(mysqli_error($db));
$row = mysqli_fetch_assoc($result);
$userTypeID = $row['TYPE_ID'];

// ✅ RESTRICT FUNCTIONS: Prevent specific user types
function restrictOnlyAdmin() {
    global $userTypeID;
    if ($userTypeID == 1) {
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
    global $userTypeID;
    if ($userTypeID == 3) {
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
    global $userTypeID;
    if ($userTypeID == 2) {
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
    global $userTypeID;
    if ($userTypeID != 1) {
        $redirectPage = ($userTypeID == 3) ? 'index_manager.php' : 'pos.php';
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
    global $userTypeID;
    if ($userTypeID != 3) {
        $redirectPage = ($userTypeID == 1) ? 'index.php' : 'pos.php';
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
    global $userTypeID;
    if ($userTypeID != 2) {
        $redirectPage = ($userTypeID == 1) ? 'index.php' : 'index_manager.php';
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
