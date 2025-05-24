    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white topbar mb-4 static-top shadow" style="position: relative; z-index: 1;">
          
          <!-- Company Name - Always visible -->
          <div class="d-flex justify-content-center flex-grow-1">
            <a href="<?php 
              $query = "SELECT TYPE_ID FROM users WHERE ID = " . intval($_SESSION['MEMBER_ID']);
              $result = mysqli_query($db, $query) or die(mysqli_error($db));
              $row = mysqli_fetch_assoc($result);
              $userType = $row['TYPE_ID'];
              
              if($userType == 2) { // Cashier
                  echo 'pos.php';
              } else { // Manager or Admin
                  echo 'index_manager.php';
              }
            ?>" class="text-decoration-none text-center">
              <h2 class="text-primary font-weight-bold mb-0 d-none d-lg-block">MJC Hardware Trading</h2>
              <h4 class="text-primary font-weight-bold mb-0 d-lg-none">MJC Hardware</h4>
            </a>
          </div>

          <!-- Mobile Menu Toggle -->
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <!-- Collapsible Navigation -->
          <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
      

              <div class="topbar-divider d-none d-sm-block"></div>

              <!-- Nav Item - User Information -->
              <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['FIRST_NAME'] . ' ' . $_SESSION['LAST_NAME']; ?></span>
                  <img class="img-profile rounded-circle"
                  <?php
                    if($_SESSION['GENDER']=='Male'){
                      echo 'src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTS0rikanm-OEchWDtCAWQ_s1hQq1nOlQUeJr242AdtgqcdEgm0Dg"';
                    }else{
                      echo 'src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSNngF0RFPjyGl4ybo78-XYxxeap88Nvsyj1_txm6L4eheH8ZBu"';
                    }
                  ?>>
                </a>

                <?php 
                  $query = 'SELECT ID, FIRST_NAME,LAST_NAME,USERNAME,PASSWORD
                            FROM users u
                            JOIN employee e ON e.EMPLOYEE_ID=u.EMPLOYEE_ID';
                  $result = mysqli_query($db, $query) or die (mysqli_error($db));
        
                  while ($row = mysqli_fetch_assoc($result)) {
                            $a = $_SESSION['MEMBER_ID'];
                  }
                ?>

                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown" style="z-index: 1031;">
                  <a class="dropdown-item" href="settings.php?action=edit&id=<?php echo $a; ?>" style="color: black !important; text-decoration: none;">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    <span style="color: black !important;">Settings</span>
                  </a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal" style="color: black !important; text-decoration: none;">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    <span style="color: black !important;">Logout</span>
                  </a>
                </div>
              </li>
            </ul>
          </div>
        </nav>
        <!-- End of Topbar -->
          
        <!-- Begin Page Content -->
        <div class="container-fluid">