
<?php
$hostName = "sql7.freesqldatabase.com";
$dbUser = "sql7812236";
$dbPassword = "R2DRKzTFmz";
$dbName = "sql7812236";
$dbPort = "3306";


$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName, $dbPort);
if(!$conn){
if(!$conn){
    die("Something went wrong!");
}
}