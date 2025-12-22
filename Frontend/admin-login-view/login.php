<?php
session_start();  // Move to the very top, before headers

require_once "../../Backend/bookdb.php";
?>


<!DOCTYPE html>
<html lang="en">
</html><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script type="module" src="./login-script.js" defer></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
    <link rel="stylesheet" href="./login-style.css" />
    <title>Document</title>
  </head>
  <body>
    <div id="root">
      <div class="main-frame">
        <div class="left-div">
          <div class="image">
                <img src="../assets/image.jpg" alt="">
          </div>
          <div class="left-statement-container">
            <h2 style="text-align: center; margin-bottom: 1%; font-size: 34px;">More Than Books</h2>
             <p class="left-statement">More than a bookstore—a sanctuary for readers. We unite literature, community, and thoughtful curation to create an immersive world where every page turns into a cherished memory.</p>
          </div>
          
        </div>
        <div class="right-div">
          <div class="title-signin">
            <h2>SIGN IN TO YOUR ACCOUNT</h2>
          </div>
          <div class="signin-statement">
            <p class="statement">
              One subscription for Liteverse, Recogoters, and Spark
            </p>
          </div>

          <div class="divider-container">
            <div class="divider-line"></div>
            <span>sign in with</span>
            <div class="divider-line"></div>
          </div>

          <div class="login-form">
            <form action="../../Backend/login.php" method="POST">
              <input
                type="email"
                placeholder="Email address"
                class="form-input"
                name="usermail"
                required
              />

              <div class="password-container">
                <input
                  type="password"
                  placeholder="Password"
                  class="form-input"
                  name="userpass"
                  required
                />
                <button type="button" class="toggle-password" id="togglePassword">
                  <i class="fa-solid fa-eye-slash" style="font-size: 11px;"></i>
                </button>
              </div>

              <a href="#" class="recovery-link">Recovery Password</a>
              <a href="../signup-view/signup.html" class="recovery-link">Don't have an account ?</a>

              <button type="submit" name="submit" value="1" class="continue-btn" name="submit">Continue</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>