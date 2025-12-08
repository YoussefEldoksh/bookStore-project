<?php

if (isset($_POST["submit"])) {

    require_once "bookdb.php";

    $_usermail = $_POST["usermail"];
    $_password = $_POST["userpass"];
    $_usermail = mysqli_real_escape_string($conn, $_usermail);

    $sql = "SELECT * FROM user WHERE email = '$_usermail'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($_password, $user["password"])) {
            session_start();
            $_SESSION["username"] = $user["name"];
            header("Location: form.php");
            exit;
        } else {
            echo "<p style='color:red;'>Incorrect password</p>";
        }
    } else {
        echo "<p style='color:red;'>Email not found</p>";
    }
    mysqli_close($conn);
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<script>
function validateForm(){
    let usermail = document.forms["myForm"]["usermail"].value;
    let userpass = document.forms["myForm"]["userpass"].value;

    if (usermail === "" || userpass === "") {
        alert("All fields must be filled out!");
        return false;
    }

    if (userpass.length < 8) {
        alert("Password must be at least 8 characters long!");
        return false;
    }

    return true;
}
</script>
<body>
    <h1>Login</h1>
    <form name="myForm" method="POST" onsubmit="return validateForm()">
        <label>Email:</label><input type="email" name="usermail" placeholder="Email"><br>
        <label>Password:</label><input type="password" name="userpass" placeholder="Password"><br>
        <input type="submit" name="submit" value="Login">
    </form>
</body>
</html>