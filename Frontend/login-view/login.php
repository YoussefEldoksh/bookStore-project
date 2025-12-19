<?php
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_POST["submit"])) {
    require_once "../../Backend/bookdb.php";

    // Check if connection exists
    if (!$conn) {
        echo json_encode(["success" => false, "message" => "Database connection failed"]);
        exit;
    }

    $_usermail = isset($_POST["usermail"]) ? trim($_POST["usermail"]) : "";
    $_password = isset($_POST["userpass"]) ? trim($_POST["userpass"]) : "";
    
    // Validate inputs
    if (empty($_usermail) || empty($_password)) {
        echo json_encode(["success" => false, "message" => "Email and password are required!"]);
        exit;
    }

    if (!filter_var($_usermail, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email format!"]);
        exit;
    }

    // Use prepared statement
    $sql = "SELECT user_id, email, first_name, last_name,username, City, street, apartment, password, type FROM user WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Prepare failed: " . mysqli_error($conn)]);
        exit;
    }
    
    mysqli_stmt_bind_param($stmt, "s", $_usermail);
    
    if (!mysqli_stmt_execute($stmt)) {
        echo json_encode(["success" => false, "message" => "Execute failed: " . mysqli_stmt_error($stmt)]);
        exit;
    }
    
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        echo json_encode(["success" => false, "message" => "Get result failed: " . mysqli_error($conn)]);
        exit;
    }

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        if (password_verify($_password, $user["password"])) {
            // Create a token
            $token = bin2hex(random_bytes(32));
            
            // Start session
            session_start();
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["username"] = $user["first_name"] . " " . $user["last_name"];
            $_SESSION["type"] = $user["type"];
            $_SESSION["token"] = $token;
            
            // Return JSON response
            echo json_encode([
                "success" => true,
                "message" => "Login successful!",
                "token" => $token,
                "user_id" => $user["user_id"],
                "firstname" => $user["first_name"],
                "lastname" =>  $user["last_name"],
                "username" => $user["username"],
                "email" => $user["email"],
                "city" => $user["City"],
                "street" => $user["street"],
                "apartment" => $user["apartment"],
                "type" => $user["type"]
            ]);
        header("refresh:2; url=../home-page/index.html");

            exit;
        } else {
            echo json_encode(["success" => false, "message" => "Incorrect password"]);
            exit;
        }
    } else {
        echo json_encode(["success" => false, "message" => "Email not found"]);
        exit;
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo json_encode(["success" => false, "message" => "Submit button not set"]);
    exit;
}
?>