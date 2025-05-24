<?php
require('session.php');
confirm_logged_in();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<style type="text/css">
/* Modern Sidebar Styles */
.nav-item {
    margin: 8px 12px;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.nav-item:not(.active):hover {
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateX(5px);
}

.nav-item.active {
    background-color: #ffffff;
    border-radius: 12px;
    padding: 12px 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transform: translateX(5px);
}

.nav-item.active a {
    color: #4e73df !important;
    font-weight: 600;
    font-size: 15px;
    letter-spacing: 0.3px;
}

.nav-item a {
    font-size: 14px;
    font-weight: 500;
    padding: 12px 16px;
    color: rgba(255, 255, 255, 0.8) !important;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    border-radius: 12px;
}

.nav-item a:hover {
    color: #ffffff !important;
}

.nav-item i {
    font-size: 18px;
    margin-right: 12px;
    width: 24px;
    text-align: center;
    transition: all 0.3s ease;
}

.nav-item:hover i {
    transform: scale(1.1);
}

#accordionSidebar {
    height: 100vh;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
    background: linear-gradient(135deg, #4e73df, #224abe);
    padding: 1rem 0;
}

#accordionSidebar::-webkit-scrollbar {
    width: 6px;
}

#accordionSidebar::-webkit-scrollbar-thumb {
    background-color: rgba(255, 255, 255, 0.3);
    border-radius: 10px;
}

.sidebar {
    position: fixed;
    height: 100vh;
    top: 0;
    left: 0;
    z-index: 1030;
    transition: all 0.3s ease;
    width: 250px;
}

.sidebar.toggled {
    width: 100px !important;
}

#content-wrapper {
    margin-left: 250px;
    transition: margin-left 0.3s ease;
}

.sidebar.toggled + #content-wrapper {
    margin-left: 100px;
}

.sidebar-brand {
    padding: 1.5rem 1rem;
    text-align: center;
    background: rgba(255, 255, 255, 0.1);
    margin: 0 1rem 1rem;
    border-radius: 12px;
}

.sidebar-brand img {
    width: 80px;
    height: 80px;
    transition: all 0.3s ease;
}

.sidebar-brand-text {
    color: #ffffff;
    font-size: 1.1rem;
    font-weight: 600;
    margin-top: 0.5rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sidebar-heading {
    color: rgba(255, 255, 255, 0.4);
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    padding: 0 1.5rem;
    margin: 1.5rem 0 0.5rem;
}

.sidebar-divider {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    margin: 1rem 1.5rem;
}

#sidebarToggle {
    background-color: rgba(255, 255, 255, 0.1);
    border: none;
    color: #ffffff;
    padding: 0.5rem;
    transition: all 0.3s ease;
}

#sidebarToggle:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

/* Mobile Responsive Styles */
@media (max-width: 768px) {
    .sidebar {
        width: 250px;
        left: -250px;
        transform: translateX(0);
        transition: transform 0.3s ease;
    }

    .sidebar.show {
        transform: translateX(250px);
    }

    #content-wrapper {
        margin-left: 0;
        width: 100%;
    }

    .sidebar-brand {
        padding: 1rem 0.5rem;
        margin: 0 0.5rem 0.5rem;
    }

    .sidebar-brand img {
        width: 50px;
        height: 50px;
    }

    .nav-item {
        margin: 4px 6px;
    }

    .nav-item a {
        padding: 10px;
    }

    .nav-item i {
        margin-right: 12px;
        font-size: 18px;
    }

    .nav-item span {
        display: inline-block;
    }

    .sidebar-heading {
        text-align: left;
        padding: 0 1.5rem;
    }

    .sidebar-divider {
        margin: 0.5rem 1.5rem;
    }

    .nav-item.active {
        padding: 12px 16px;
    }

    .nav-item.active a {
        font-size: 14px;
    }

    /* Overlay when sidebar is open */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1029;
    }

    .sidebar-overlay.show {
        display: block;
    }
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    .nav-item.active {
        background-color: rgba(255, 255, 255, 0.15);
    }

    .nav-item.active a {
        color: #ffffff !important;
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
            <a class="sidebar-brand d-flex flex-column align-items-center justify-content-center" href="index_manager.php">
                <img src="../img/clogo.png" alt="Company Logo">
                <div class="sidebar-brand-text">Sales and Inventory System</div>
            </a>

            <hr class="sidebar-divider">
            
            <li class="nav-item <?php echo ($current_page == 'index_manager.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="index_manager.php">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Home</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Management</div>

            <li class="nav-item <?php echo ($current_page == 'customer.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="customer.php">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Customers</span>
                </a>
            </li>

            <li class="nav-item <?php echo ($current_page == 'employee_manager.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="employee_manager.php">
                    <i class="fas fa-fw fa-user-tie"></i>
                    <span>Employees</span>
                </a>
            </li>

            <li class="nav-item <?php echo ($current_page == 'inventory_cashier.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="inventory_cashier.php">
                    <i class="fas fa-fw fa-boxes"></i>
                    <span>Inventory</span>
                </a>
            </li>

            <li class="nav-item <?php echo ($current_page == 'create_po.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="create_po.php">
                    <i class="fas fa-fw fa-truck"></i>
                    <span>Purchase Orders</span>
                </a>
            </li>

            <li class="nav-item <?php echo ($current_page == 'transaction.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="transaction.php">
                    <i class="fas fa-fw fa-exchange-alt"></i>
                    <span>Transactions</span>
                </a>
            </li>

            <hr class="sidebar-divider d-none d-md-block">
            
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle">
                    <i class="fas fa-angle-left"></i>
                </button>
            </div>
        </ul>
        <?php include_once 'topbar.php'; ?>

</body>
</html>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    const contentWrapper = document.getElementById('content-wrapper');
    let touchStartX = 0;
    let touchEndX = 0;
    const swipeThreshold = 50;

    // Create overlay element
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);

    // Touch event handlers
    document.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    }, false);

    document.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, false);

    function handleSwipe() {
        const swipeDistance = touchEndX - touchStartX;
        
        if (Math.abs(swipeDistance) > swipeThreshold) {
            if (swipeDistance > 0) {
                // Swipe right - show sidebar
                sidebar.classList.add('show');
                overlay.classList.add('show');
            } else {
                // Swipe left - hide sidebar
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        }
    }

    // Close sidebar when clicking overlay
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    });

    // Handle sidebar toggle button
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
    }
});
</script>