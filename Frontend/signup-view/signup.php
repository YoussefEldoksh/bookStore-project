<?php
        require_once "../../Backend/bookdb.php";

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