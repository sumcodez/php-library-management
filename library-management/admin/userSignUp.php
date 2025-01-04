<?php
    ob_start(); // Start output buffering

    include 'controllers/AuthController.php';

    $authentication = new Authenticator();
    $authentication->handleSignUp();
    ob_end_flush(); // End output buffering
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Registration Page</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="../../index2.html"><b>Library</b></a>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Register a new membership</p>

      <form method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="First Name" name="first_name" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Last Name" name="last_name" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name="email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
            <select name="country" class="form-control" id="country">
                <option value="India">India</option>
                <option value="UK">UK</option>
                <option value="USA">USA</option>
            </select>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" id="password" name="password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Retype password" id="retype_password" name="retype_password" required>
          <small id="passwordError" class="form-text text-danger" style="display: none;">Passwords do not match.</small>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
               I agree to the <a href="#">terms</a>
              </label>
            </div>
          </div> -->
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block" style="margin-left: 110px;">Register</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <!-- <div class="social-auth-links text-center">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i>
          Sign up using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i>
          Sign up using Google+
        </a>
      </div> -->

      <a href="userLogin.php" class="text-center" style="margin-left: 78px;">I already have a account</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.min.js"></script>





<script>
    // const form = document.querySelector('form');
    // const passwordField = document.getElementById('password');
    // const confirmPasswordField = document.getElementById('retype_password');
    // const passwordError = document.getElementById('passwordError');
    // const submitButton = document.querySelector('button[type="submit"]');

    // form.addEventListener('submit', function (event) {
    //     let isValid = true;

    //     // Check password length and complexity
    //     if (passwordField.value.length < 8 || 
    //         !/[A-Za-z]/.test(passwordField.value) || 
    //         !/[0-9]/.test(passwordField.value)) {
    //         isValid = false;
    //         passwordError.textContent = 
    //             'Password must be at least 8 characters long and include both letters and numbers.';
    //         passwordError.style.display = 'block';
    //     } else if (passwordField.value !== confirmPasswordField.value) {
    //         // Check if passwords match
    //         isValid = false;
    //         passwordError.textContent = 'Passwords do not match.';
    //         passwordError.style.display = 'block';
    //     } else {
    //         passwordError.style.display = 'none'; // Hide the error message if valid
    //     }

    //     // Prevent form submission if validation fails
    //     if (!isValid) {
    //         event.preventDefault();
    //     }
    // });

    // Hide error message when user types in password fields
    // [passwordField, confirmPasswordField].forEach(field => {
    //     field.addEventListener('input', () => {
    //         passwordError.style.display = 'none';
    //     });
    // });

const passwordField = document.getElementById('password');
const confirmPasswordField = document.getElementById('retype_password');
const passwordError = document.getElementById('passwordError');

function validatePasswords() {
    let errorMessage = '';
    
    // Check password length and complexity
    if (passwordField.value.length < 8 || 
        !/[A-Za-z]/.test(passwordField.value) || 
        !/[0-9]/.test(passwordField.value)) {
        errorMessage = 'Password must be at least 8 characters long and include both letters and numbers.';
    } 
    // Check if passwords match
    else if (passwordField.value !== confirmPasswordField.value) {
        errorMessage = 'Passwords do not match.';
    }

    // Update error message visibility based on validation
    passwordError.textContent = errorMessage;
    passwordError.style.display = errorMessage ? 'block' : 'none';
}

// Add input event listeners for real-time validation
[passwordField, confirmPasswordField].forEach(field => {
    field.addEventListener('input', validatePasswords);
});


</script>





</body>
</html>
