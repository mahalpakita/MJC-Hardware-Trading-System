<?php

require('../includes/connection.php');
require('session.php');

if (isset($_POST['btnlogin'])) {
    $users = trim($_POST['user']);
    $upass = trim($_POST['password']);
    $h_upass = sha1($upass);

    if ($upass == '') {
        echo "<script>
                alert('Invalid credentials! Contact your administrator.');
                window.location = 'login.php';
              </script>";
    } else {
        // ✅ Modify SQL query to include STATUS
        $sql = "SELECT u.ID, u.STATUS, e.FIRST_NAME, e.LAST_NAME, e.GENDER, e.EMAIL, e.PHONE_NUMBER, 
                       j.JOB_TITLE, l.PROVINCE, l.CITY, t.TYPE
                FROM users u
                JOIN employee e ON e.EMPLOYEE_ID = u.EMPLOYEE_ID
                JOIN location l ON e.LOCATION_ID = l.LOCATION_ID
                JOIN job j ON e.JOB_ID = j.JOB_ID
                JOIN type t ON t.TYPE_ID = u.TYPE_ID
                WHERE u.USERNAME = '$users' AND u.PASSWORD = '$h_upass'";

        $result = $db->query($sql);

        if ($result) {
            if ($result->num_rows > 0) {
                $found_user = mysqli_fetch_array($result);

                // ✅ Check if the user is active
                if ($found_user['STATUS'] !== 'Enable') {
                    echo "<script>
                            alert('Your account is Disable. Please contact the administrator.');
                            window.location = 'login.php';
                          </script>";
                    exit();
                }

                // ✅ Store session variables
                $_SESSION['MEMBER_ID'] = $found_user['ID'];
                $_SESSION['FIRST_NAME'] = $found_user['FIRST_NAME'];
                $_SESSION['LAST_NAME'] = $found_user['LAST_NAME'];
                $_SESSION['GENDER'] = $found_user['GENDER'];
                $_SESSION['EMAIL'] = $found_user['EMAIL'];
                $_SESSION['PHONE_NUMBER'] = $found_user['PHONE_NUMBER'];
                $_SESSION['JOB_TITLE'] = $found_user['JOB_TITLE'];
                $_SESSION['PROVINCE'] = $found_user['PROVINCE'];
                $_SESSION['CITY'] = $found_user['CITY'];
                $_SESSION['TYPE'] = $found_user['TYPE'];

                // ✅ Redirect user based on role
                if ($_SESSION['TYPE'] == 'Admin') {
                    echo "<script>
                            alert('Welcome!');
                            window.location = 'index.php';
                          </script>";
                } elseif ($_SESSION['TYPE'] == 'Cashier') {
                    echo "<script>
                            alert('Welcome!');
                            window.location = 'pos.php';
                          </script>";
                } elseif ($_SESSION['TYPE'] == 'Manager') {
                    echo "<script>
                            alert('Welcome!');
                            window.location = 'index_manager.php';
                          </script>";
                }
            } else {
                echo "<script>
                        alert('Invalid credentials! Contact your administrator.');
                        window.location = 'login.php';
                      </script>";
            }
        } else {
            echo "Error: " . $sql . "<br>" . $db->error;
        }
    }
}

$db->close();
?>
