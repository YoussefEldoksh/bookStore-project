
<?php
$hostName = "localHost";
$hostName = "sql7.freesqldatabase.com";
$dbUser = "root";
$dbUser = "sql7812236";
$dbPassword = "";
$dbPassword = "R2DRKzTFmz";
$dbName = "bookstore";
$dbName = "sql7812236";
$dbPort = "3306";


$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName, $dbPort);
if(!$conn){
if(!$conn){
    die("Something went wrong!");
}
}