<?php
header('Content-Type: application/json');
require "../../Backend/bookdb.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Not authenticated"]);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email      = trim($_POST['usermail'] ?? '');
    $first_name = trim($_POST['firstname'] ?? '');
    $last_name  = trim($_POST['lastname'] ?? '');
    $username   = trim($_POST['username'] ?? '');
    $useraprt   = $_POST["useraddress-apt"] ?? '';
    $userstreet = $_POST["useraddress-street"] ?? '';
    $usercity   = $_POST["useraddress-city"] ?? '';
    $password   = $_POST['userpass'] ?? '';

    if (empty($email) || empty($username) || empty($first_name) || empty($last_name) || empty($useraprt) || empty($userstreet) || empty($usercity)) {
        echo json_encode(["success" => false, "message" => "All fields except password are required."]);
        exit;
    }

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("
            UPDATE user
            SET email = ?, first_name = ?, last_name = ?, City = ?, street = ?, apartment = ?, password = ?, username = ?
            WHERE user_id = ?
        ");
        $stmt->bind_param("ssssssssi", $email, $first_name, $last_name, $usercity, $userstreet, $useraprt, $hashedPassword, $username, $user_id);
    } else {
        $stmt = $conn->prepare("
            UPDATE user
            SET email = ?, first_name = ?, last_name = ?, City = ?, street = ?, apartment = ?, username = ?
            WHERE user_id = ?
        ");
        $stmt->bind_param("sssssssi", $email, $first_name, $last_name, $usercity, $userstreet, $useraprt, $username, $user_id);
    }

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Profile updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error updating profile."]);
    }
}
?>