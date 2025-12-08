<?php
$hostName = "localHost";
$dbUser = "root";
$dbPassword = "";
$dbName = "bookstore";

$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
if(!$conn){
    die("Something went wrong!");
}
?>