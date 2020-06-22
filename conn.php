<?php


require_once 'users.php';

$conn = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
//$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if($conn->connect_error){

    die("connection failed: " . $conn->connect_error);
}
?>
