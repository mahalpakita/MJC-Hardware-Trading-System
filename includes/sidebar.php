<?php
require('session.php');
confirm_logged_in();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<style type="text/css">
.nav-item.active {
    background-color: #3751ff;
    border-radius: 12px;
    padding: 10px 16px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease-in-out;
}

.nav-item.active a {
    color: white !important;
    font-weight: bold;
    font-size: 21px;
    letter-spacing: 0.6px;
}

.nav-item.active:hover {
    background-color: #2c3ebc;
    box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
}

.nav-item a {
    font-size: 20px;
    font-weight: bold;
    padding: 14px 22px;
    transition: color 0.3s ease-in-out;
}

.nav-item i {
    font-size: 22px;
    margin-right: 10px;
    transition: transform 0.3s ease-in-out;
}

.nav-item:hover i {
    transform: scale(1.1);
}

#accordionSidebar {
    height: 100vh;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #4e73df transparent;
    background: linear-gradient(135deg, #4e73df, #3751ff);
}

#accordionSidebar::-webkit-scrollbar {
    width: 8px;
}

#accordionSidebar::-webkit-scrollbar-thumb {
    background-color: #4e73df;
    border-radius: 12px;
}

.sidebar {
    position: fixed;
    height: 100vh;
    top: 0;
    left: 0;
    z-index: 1030;
    transition: width 0.3s ease-in-out;
}

.sidebar.toggled {
    width: 100px !important;
    overflow-y: auto !important;
}

#content-wrapper {
    margin-left: 225px;
    transition: margin-left 0.3s ease-in-out;
}

.sidebar.toggled + #content-wrapper {
    margin-left: 100px;
}

#overlay {
    position: fixed;
    display: none;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 2;
    cursor: pointer;
}

#text {
    position: absolute;
    top: 50%;
    left: 50%;
    font-size: 50px;
    color: white;
    transform: translate(-50%, -50%);
}

@media (max-width: 768px) {
    #accordionSidebar {
        border-radius: 0;
    }
    #content-wrapper {
    margin-left: 100px;
    }
    .sidebar {
    left: -10px;
}
    .sidebar.toggled + #content-wrapper {
    margin-left: 0px;
    }
    #accordionSidebar {
        width: 90px; /* Make sidebar smaller */
    }
    #content-wrapper {
        margin-left: 70px;
    }
    .nav-item.active {
    border-radius: 12px;
    padding: 1px 0px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
    }
    .sidebar-brand img {
        width: 60px; /* Smaller logo */
        height: 60px;
    }
    .nav-item i {
        font-size: 18px; /* Scale down icons */
        margin-right: 30px;
    }
    .nav-item a {
        font-size: 16px; /* Reduce font size for smaller screens */
        padding: 8px 12px;
    }
    .nav-item span {
    margin-right: 60px;
    font-size: 6px;
    }
        .small-space {
        display: none;
    }
    h2.text-primary.font-weight-bold.mb-0 {
        font-size: 18px;
    }
}
</style>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Sales and Inventory System</title>
  <link rel="icon" type="image/png" href="../img/logo.png">
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php
        $current_page = basename($_SERVER['PHP_SELF']);
        ?>
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <div class="small-space" style="height: 5px;"></div>
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <img src="../img/clogo.png" alt="Company Logo" width="100" height="100">
                <div class="sidebar-brand-text mx-3">Sales and Inventory System</div>
            </a>
            <div class="small-space" style="height: 5px;"></div>
            <hr class="sidebar-divider my-0">
            <li class="nav-item <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Home</span>
                </a>
            </li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Tables</div>
            <li class="nav-item <?php echo ($current_page == 'customer.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="customer.php">
                    <i class="fas fa-fw fa-child"></i>
                    <span>Customer</span>
                </a>
            </li>
            <li class="nav-item <?php echo ($current_page == 'employee.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="employee.php">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Employee</span>
                </a>
            </li>
            <li class="nav-item <?php echo ($current_page == 'inventory.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="inventory.php">
                    <i class="fas fa-fw fa-clipboard-list"></i>
                    <span>Inventory</span>
                </a>
            </li>
            <li class="nav-item <?php echo ($current_page == 'manage_po.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="manage_po.php">
                    <i class="fas fa-fw fa-truck"></i>
                    <span>P.O.</span>
                </a>
            </li>
            <li class="nav-item <?php echo ($current_page == 'supplier.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="supplier.php">
                    <i class="fas fa-fw fa-warehouse"></i>
                    <span>Supplier</span>
                </a>
            </li>
            <li class="nav-item <?php echo ($current_page == 'transaction.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="transaction.php">
                    <i class="fas fa-fw fa-retweet"></i>
                    <span>Transactions</span>
                </a>
            </li>
            <li class="nav-item <?php echo ($current_page == 'user.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="user.php">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Accounts</span>
                </a>
            </li>
            <li class="nav-item <?php echo ($current_page == 'edit_history.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="edit_history.php">
                    <i class="fas fa-fw fa-history"></i>
                    <span>History</span>
                </a>
            </li>
            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <?php include_once 'topbar_admin.php'; ?>

</body>
</html>