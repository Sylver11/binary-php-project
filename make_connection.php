<?php
require_once "conn.php";

$client_id = $_GET['client_id'] ;
$contact_email = $_GET['user_email'];
$stmt = $conn->prepare("SELECT client_id FROM connections WHERE client_id = ? AND contact_email = ?");
$stmt->bindParam(1, $client_id, PDO::PARAM_STR);
$stmt->bindParam(2, $contact_email, PDO::PARAM_STR);
$stmt->execute();

if( ! $stmt->rowCount() ) {
    $stmt = $conn->prepare("INSERT INTO connections (client_id, contact_email) VALUES (?, ?)");
    $stmt->bindParam(1, $client_id, PDO::PARAM_STR);
    $stmt->bindParam(2, $contact_email, PDO::PARAM_STR);
    $stmt->execute();
    echo json_encode('success');
}else{
    echo json_encode('Sorry this connection already exists');
}

?>