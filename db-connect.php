<?php
// DB Host
$host = "localhost";
// DB Username
$uname = "root";
// DB Password
$password = "root";
// DB Name
$dbname = "dummy_db";


$conn = new mysqli($host, $uname, $password, $dbname);
if(!$conn){
    die("Database Connection Failed.");
}
?>