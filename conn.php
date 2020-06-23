<?php




$servername = "127.0.0.1";
$username = "justus";
$password = "Sylvester12.";

try {
  $conn = new PDO("mysql:host=$servername;dbname=binary_city", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}









require_once 'users.php';

$conn2 = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
//$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if($conn2->connect_error){

    die("connection failed: " . $conn2->connect_error);
}
?>
