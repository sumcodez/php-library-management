<?php
    ob_start(); // Start output buffering

    include 'controllers/AuthController.php';

    $authentication = new Authenticator();
    $authentication->login();
    ob_end_flush(); // End output buffering

?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">

<div class="login-box">
  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success_msg">
        <?= htmlspecialchars($_GET['success']) ?>
    </div>
  <?php endif; ?>
  <div class="login-logo">
    <a href="../../index2.html"><b>Library</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to access dashboard</p>

      <form method="post">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name="email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div> -->
          <!-- /.col -->
          <div class="col-5">
            <button type="submit" class="btn btn-primary btn-block" style="margin-left: 100px;">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <!-- <div class="social-auth-links text-center mb-3">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
        </a>
      </div> -->
      <!-- /.social-auth-links -->

      <!-- <p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p> -->
      <p class="mb-0">
        <a href="userSignUp.php" class="text-center" style="margin-left: 90px;">Register a new account</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<script>
    setTimeout(() => {
        const alert = document.getElementById('success_msg');
        const alert_failed = document.getElementById('failed_msg');
        const url = new URL(window.location.href); // Get the current URL
        if (alert) {
            alert.classList.remove('show');
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 1000);
            url.searchParams.delete('success'); // Remove the 'success' query parameter
            window.history.replaceState({}, document.title, url.toString()); // Update the URL without reloading
        }

        if (alert_failed) {
            alert.classList.remove('show');
            alert.classList.add('fade');
            setTimeout(() => alert_failed.remove(), 1000);
            url.searchParams.delete('failed'); // Remove the 'success' query parameter
            window.history.replaceState({}, document.title, url.toString()); // Update the URL without reloading
        }
    }, 2000);
</script>

<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.min.js"></script>

</body>
</html>
