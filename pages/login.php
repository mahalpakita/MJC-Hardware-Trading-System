<?php require('session.php'); ?>
<?php if(logged_in()){ ?>
<script type="text/javascript">
    window.location = "index.php";
</script>

<?php }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Sales and Inventory | Login</title> 
   <link rel="icon" type="image/png" href="../img/logo.png">

  <!-- Bootstrap CSS -->
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome for Icons -->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600,700&display=swap" rel="stylesheet">

  <!-- Custom CSS -->
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #4e73df 30%, #1cc88a 100%);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: fadeIn 0.8s ease-in-out;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
      overflow: hidden;
      animation: slideUp 0.6s ease-in-out;
    }

    .login-image {
      display: flex;
      align-items: center;
      justify-content: center;
      background: #fff;
    }

    .login-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .login-form {
      padding: 40px;
      background: #fff;
      border-radius: 0 15px 15px 0;
    }

    .login-form h1 {
      font-size: 26px;
      font-weight: 600;
      color: #333;
      margin-bottom: 20px;
    }

    .form-control {
      border-radius: 25px;
      padding: 12px 20px;
      font-size: 14px;
      transition: 0.3s;
    }

    .form-control:focus {
      box-shadow: 0 0 10px rgba(78, 115, 223, 0.4);
      border-color: #4e73df;
    }

    .btn-primary {
      border-radius: 25px;
      font-size: 16px;
      padding: 12px;
      transition: 0.3s;
    }

    .btn-primary:hover {
      background: #2e59d9;
    }

    .password-container {
      position: relative;
    }

    .eye-icon {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #aaa;
      transition: 0.3s;
    }

    .eye-icon:hover {
      color: #333;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes slideUp {
      from {
        transform: translateY(30px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }
  </style>
</head>

<body>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10 col-md-10">
        <div class="card o-hidden border-0 shadow-lg">
          <div class="row">
            <!-- Left Side Image -->
            <div class="col-lg-6 d-none d-lg-block login-image">
              <img src="../img/logo.png" alt="MJC Hardware Trading">
            </div>
            
            <!-- Right Side Login Form -->
            <div class="col-lg-6">
              <div class="login-form">
                <br>
                <div class="text-center">
                  <h1 class="h4 text-gray-900">MJC Hardware Trading</h1>
                </div>
                <form class="user" role="form" action="processlogin.php" method="post">
                  <div class="form-group">
                    <input class="form-control form-control-user" placeholder="Username" name="user" type="text" autofocus required>
                  </div>
                  <div class="form-group password-container">
                    <input class="form-control form-control-user" id="password" placeholder="Password" name="password" type="password" required>
                    <i class="fas fa-eye-slash eye-icon" id="togglePassword"></i> 
                  </div>
                  <div class="form-group">
                    <div class="custom-control custom-checkbox small">
                      <input type="checkbox" class="custom-control-input" id="customCheck">
                      <label class="custom-control-label" for="customCheck">Remember Me</label>
                    </div>
                  </div>
                  <button class="btn btn-primary btn-user btn-block" type="submit" name="btnlogin">Login</button>
                  <hr>
                </form>
              </div>
            </div>
          </div>  
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JavaScript -->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Password Toggle Script -->
  <script>
    document.querySelector('#togglePassword').addEventListener('click', function () {
      const passwordField = document.querySelector('#password');
      const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordField.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });
  </script>

</body>
</html>
