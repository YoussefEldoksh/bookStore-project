<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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

            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["username"] = $user["username"]; // or $user["name"] if that's your column
            $_SESSION["type"] = $user["type"];         // Admin or Customer

            if ($user["type"] === "Admin") {
                header("Location: ../Frontend/admin-page/adminPage.php");
            } else {
                header("Location: ../Frontend/home-page/index.html");
            }
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
