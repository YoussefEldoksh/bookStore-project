<?php
session_start();  // Move to the very top, before headers

header('Content-Type: application/json');
require_once "../../Backend/bookdb.php";

if(isset($_POST["submit"])){
    $_firstname = $_POST["firstname"];
    $_lastname = $_POST["lastname"];
    $_usermail = $_POST["usermail"];
    $_username = $_POST["username"];
    $_password = $_POST["userpass"];
    $_shippingaddress = $_POST["useraddress"];
    
    // Validate inputs
    if (empty($_firstname) || empty($_lastname) || empty($_usermail) || empty($_password) || empty($_shippingaddress) || empty($_username)) {
        echo json_encode(["success" => false, "message" => "All fields are required!"]);
        exit;
    } 
    else if (strlen($_password) < 8) {
        echo json_encode(["success" => false, "message" => "Password must be at least 8 characters long!"]);
        exit;
    }
    else if (!filter_var($_usermail, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Email invalid!"]);
        exit;
    }
    else {
        $_passwordhash = password_hash($_password, PASSWORD_DEFAULT);
        
        // Check if email or username already exists
        $sql = "SELECT user_id FROM user WHERE email = ? OR username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        if (!$stmt) {
            echo json_encode(["success" => false, "message" => "Database error: " . mysqli_error($conn)]);
            exit;
        }
        
        mysqli_stmt_bind_param($stmt, "ss", $_usermail, $_username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if(mysqli_num_rows($result) > 0){
            echo json_encode(["success" => false, "message" => "Email or username already exists!"]);
            mysqli_stmt_close($stmt);
            exit;
        }
        
        mysqli_stmt_close($stmt);
        
        // Insert new user (add username to INSERT)
        $sql = "INSERT INTO user (email, username, first_name, last_name, password, shipping_address, type) 
                VALUES (?, ?, ?, ?, ?, ?, 'Customer')";
        
        $stmt = mysqli_prepare($conn, $sql);
        
        if (!$stmt) {
            echo json_encode(["success" => false, "message" => "Database error: " . mysqli_error($conn)]);
            exit;
        }
        
        mysqli_stmt_bind_param($stmt, "ssssss", $_usermail, $_username, $_firstname, $_lastname, $_passwordhash, $_shippingaddress);
        
        if (mysqli_stmt_execute($stmt)) {
            $sql = "SELECT user_id, email, username, first_name, last_name, shipping_address, type FROM user WHERE email = ?";
            $stmt = mysqli_prepare($conn, $sql);
            
            if (!$stmt) {
                echo json_encode(["success" => false, "message" => "Database error: " . mysqli_error($conn)]);
                exit;
            }
            
            mysqli_stmt_bind_param($stmt, "s", $_usermail);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
            
            if (!$user) {
                echo json_encode(["success" => false, "message" => "Failed to retrieve user data"]);
                mysqli_stmt_close($stmt);
                exit;
            }
            
            $token = bin2hex(random_bytes(32));
            
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["username"] = $user["username"];  // Store actual username
            $_SESSION["shipping_address"] = $user["shipping_address"];
            $_SESSION["type"] = $user["type"];
            $_SESSION["token"] = $token;
            
            echo json_encode([
                "success" => true,
                "message" => "Registration successful!",
                "token" => $token,
                "user_id" => $user["user_id"],
                "username" => $user["username"],
                "email" => $user["email"],
                "type" => $user["type"]
            ]);
            
            mysqli_stmt_close($stmt);
            
        } else {
            echo json_encode(["success" => false, "message" => "Registration failed: " . mysqli_stmt_error($stmt)]);
            mysqli_stmt_close($stmt);
        }
    }
}
?>