<?php
require_once "conn.php";
$stmt = $conn->prepare("SELECT MAX(ID) FROM clients"); 
$stmt->execute(); 
$row = $stmt->fetch();
echo json_encode($row);
?>