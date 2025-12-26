<?php
header('Content-Type: application/json');
require "../../Backend/bookdb.php";
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['type'] !== 'Admin') {
    echo json_encode(["error" => "Unauthorized access."]);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['usermail'] ?? '');
    $first_name = trim($_POST['firstname'] ?? '');
    $last_name = trim($_POST['lastname'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $useraprt = trim($_POST["useraddress-apt"] ?? '');
    $userstreet = trim($_POST["useraddress-street"] ?? '');
    $usercity = trim($_POST["useraddress-city"] ?? '');
    $password = $_POST['userpass'] ?? '';

    // If a new password is provided, hash it
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
    } else {
        // Keep the old password (do not update if empty)
        $password = null;
    }

    // Prepare the update query
    if ($password) {
        // If password is updated
        $query = "UPDATE `user` 
                  SET first_name = ?, last_name = ?, email = ?, username = ?, city = ?, street = ?, apartment = ?, password = ? 
                  WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssssi", $first_name, $last_name, $email, $username, $usercity, $userstreet, $useraprt, $password, $user_id);
    } else {
        // If password is not updated
        $query = "UPDATE `user` 
                  SET first_name = ?, last_name = ?, email = ?, username = ?, city = ?, street = ?, apartment = ? 
                  WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssi", $first_name, $last_name, $email, $username, $usercity, $userstreet, $useraprt, $user_id);
    }

    // Execute the update query
    if ($stmt->execute()) {
        // Return success message in JSON format
        echo json_encode(["success" => "Profile updated successfully!"]);
    } else {
        // Return error message in JSON format
        echo json_encode(["error" => "Error updating profile. Please try again later."]);
    }
}
?>