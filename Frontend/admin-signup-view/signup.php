<?php
session_start();  // Move to the very top, before headers

require_once "../../Backend/bookdb.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script type="module" src="./signup-script.js" defer></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="./signup-style.css" />
  <title>Document</title>
</head>

<body>
  <div id="root">
    <div class="main-frame">
      <div class="left-div">
        <div class="image">
          <img src="../assets/adminsignup.jpg" alt="" />
        </div>
        <div class="left-statement-container">
          <h2 style="text-align: center; margin-bottom: 1%; font-size: 34px">
            Where Stories Live
          </h2>
          <p class="left-statement">
            As an admin, you'll have full control over books, orders, users and can easily track order requests, add and modify existing books and view reports.
          </p>
        </div>
      </div>
      <div class="right-div">
        <div class="title-signin">
          <h2>SIGN UP TO YOUR ACCOUNT</h2>
        </div>
        <div class="signin-statement">
          <p class="statement">
            Welcome to where Stories Live
          </p>
        </div>

        <div class="login-form">
          <div id="form-message"></div>
          <form action="../../Backend/registration.php" method="POST">
            <input type="hidden" name="type" value="Admin">
            <div style="display: flex; gap: 5px">
              <input type="text" placeholder="First name" class="form-input" name="firstname" required />
              <input type="text" placeholder="Last name" class="form-input" name="lastname" required />
            </div>
            <input type="email" placeholder="Email address" class="form-input" name="usermail" required />
            <input type="text" placeholder="Username" class="form-input" name="username" required />
            <div style="width: 100%; display: flex; gap: 5px;">

              <input type="text" placeholder="City" class="form-input" name="useraddress-city" required />
              <input type="text" placeholder="Street" class="form-input" name="useraddress-street" required />
              <input type="text" placeholder="Apt#" class="form-input" name="useraddress-apt" required />
            </div>

            <div class="password-container">
              <input type="password" placeholder="Password" class="form-input" name="userpass" required />
              <button type="button" class="toggle-password" id="togglePassword">
                <i class="fa-solid fa-eye-slash" style="font-size: 11px"></i>
              </button>
            </div>
            <a href="../admin-login-view/login.php" class="recovery-link">have an account ?</a>
            <button type="submit" class="continue-btn" name="submit">Continue</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>