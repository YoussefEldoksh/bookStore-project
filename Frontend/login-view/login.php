<?php

if (isset($_POST["submit"])) {

        require_once "../../Backend/bookdb.php";

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
