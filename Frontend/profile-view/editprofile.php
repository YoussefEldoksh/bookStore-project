<?php
require "bookdb.php";
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Customer') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email      = trim($_POST['email']);
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $useraprt = $_POST["useraddress-apt"];
    $userstreet = $_POST["useraddress-street"];
    $usercity = $_POST["useraddress-city"];
    $password   = $_POST['password']; 

    //Basic validation
    if (empty($email) || empty($first_name) || empty($last_name) || empty($useraprt) || empty($userstreet) || empty($usercity)) {
        die("All fields except password are required.");
    }

    //If password is provided update with hash
    if (!empty($password)) {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            UPDATE user
            SET email = ?, first_name = ?, last_name = ?, shipping_address = ?, password = ?
            WHERE user_id = ?
        ");
        $stmt->bind_param(
            "sssisssi",
            $email,
            $first_name,
            $last_name,
            $useraprt,
            $userstreet,
            $usercity,
            $hashedPassword,
            $user_id
        );

    } else {
        //Update without changing password
        $stmt = $conn->prepare("
            UPDATE user
            SET email = ?, first_name = ?, last_name = ?, apartment = ?, street_name = ?, City = ?
            WHERE user_id = ?
        ");
        $stmt->bind_param(
            "sssissi",
            $email,
            $first_name,
            $last_name,
            $useraprt,
            $userstreet,
            $usercity,
            $user_id
        );
    }

    if ($stmt->execute()) {
        echo "Profile updated successfully.";
    } else {
        echo "Error updating profile.";
    }
}
?>

<form method="POST">
    <label>Email:</label>
    <input type="email" name="email" required>

    <label>First Name:</label>
    <input type="text" name="first_name" required>

    <label>Last Name:</label>
    <input type="text" name="last_name" required>

    <label>New Password (leave empty to keep current password):</label>
    <input type="password" name="password">

    <button type="submit">Update Profile</button>
</form>