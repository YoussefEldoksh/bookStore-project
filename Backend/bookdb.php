
<?php
$hostName = "sql7.freesqldatabase.com";
$dbUser = "sql7812236";
$dbPassword = "R2DRKzTFmz";
$dbName = "sql7812236";
$dbPort = "3306";


<<<<<<< HEAD
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName, $dbPort);
=======
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

>>>>>>> e0ee7c46e097b88d389568bb9f3a27cfda2d98f2
if(!$conn){
    die("Something went wrong!");
}
