<?php

require_once 'conn.php';

if(isset($_GET['user_email'])){
        $param_user_email = $_GET['user_email'] . '%';
        $stmt = $conn->prepare("SELECT DISTINCT user_email FROM users WHERE user_email LIKE ?");
        $stmt->bindParam(1, $param_user_email, PDO::PARAM_STR);
}
else{
        $param_client_id = $_GET['client_id'] . '%';
        $stmt = $conn->prepare("SELECT DISTINCT client_id FROM clients WHERE client_id LIKE ?");
        $stmt->bindParam(1, $param_client_id, PDO::PARAM_STR);
}
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($result);

?>