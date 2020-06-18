<?php
require_once "conn.php";

$data = $_GET['client_name'] or $_REQUEST['client_name'];
$data2 = $_GET['contact_email'];
$stmt = $conn->prepare("SELECT user_clients_associated FROM users WHERE user_email = ?");
$stmt->bind_param('s', $data2);
$stmt->execute();
$result = $stmt->get_result();
$tempNum=0;
$arr = array();
while ($row = $result->fetch_assoc()) {
  if(!empty($row["user_clients_associated"])){
    $arr[$tempNum]= $row["user_clients_associated"];
    $tempNum++;
  }
        
}

array_push($arr, $data);

$stmt2 = $conn->prepare("UPDATE clients SET client_contacts_associated = ? WHERE client_name = ?");

foreach($arr as $conns){
  $stmt2->bind_param('ss', $data2, $conns);
  $stmt2->execute();
} 

$commaList = implode(', ', $arr);

$stmt3 = $conn->prepare("UPDATE users SET user_clients_associated = ? WHERE user_email = ?");
$stmt3->bind_param('ss', $commaList, $data2);
$stmt3->execute();

exit();


?>