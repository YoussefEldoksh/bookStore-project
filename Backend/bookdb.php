<?php
$hostName = "localhost";       // Local server
$dbUser = "root";              // Default XAMPP MySQL user
$dbPassword = "";              // Default XAMPP MySQL password (usually empty)
$dbName = "bookstore";         // Your local database name
$dbPort = 3306;

$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName, $dbPort);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected successfully";
?>
