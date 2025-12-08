<!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <title>Assignment 1</title>
    </head>
    <script>
        function validateForm(){
    let username= document.forms["myForm"]["username"].value;
    let usermail= document.forms["myForm"]["usermail"].value;
    let userpass= document.forms["myForm"]["userpass"].value;
    let cpass= document.forms["myForm"]["cpass"].value;
    // Check for empty fields
  if (username === "" || usermail === "" || userpass === "" || cpass === "") {
    alert("All fields must be filled out!");
    return false;
  }

  // Check password length
  if (userpass.length < 8) {
    alert("Password must be at least 8 characters long!");
    return false;
  }

  // Check password match
  if (userpass !== cpass) {
    alert("Passwords do not match!");
    return false;
  }

  return true; // allow submission
}
    </script>
    <body>
        <?php
        require_once "bookdb.php";

        if(isset($_POST["submit"])){
            $_fullname = $_POST["username"];
            $_usermail = $_POST["usermail"];
            $_password = $_POST["userpass"];
            $_confirmpass = $_POST["cpass"];
            $_passwordhash = password_hash($_password, PASSWORD_DEFAULT);
            $sql = " SELECT * FROM user WHERE email = '$_usermail'";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);
            if($rowCount>0){
                echo "<p style='color: red;'> Email already exists!</p>";
            }
            else {
            $sql = "INSERT INTO user (email, name, password)
            VALUES ('$_usermail', '$_fullname', '$_passwordhash')";

             if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green;'>Registration successful!</p>";
             } else {
        echo "<p style='color:red;'>Error: " . mysqli_error($conn). "</p>";
             }
        }
    }
        ?>
        <h1>Register</h1>
        <form name="myForm" method="POST" onsubmit="return validateForm()">
    <label>Name:</label><input type="text" name="username" placeholder="Full Name"><br>
    <label>Email:</label><input type="email" name="usermail" placeholder="Email"><br>
    <label>Password:</label><input type="password" name="userpass" placeholder="Password"><br>
    
    <label>Confirm Password:</label><input type="password" name="cpass" placeholder="Confirm Password"><br>
    <input type="checkbox" id="user_admin" value="Admin">
    <input type="checkbox" id="user_customer" value="Customer">
    <input type="submit" name="submit"value="Register"></form>
    </body>
    </html>
