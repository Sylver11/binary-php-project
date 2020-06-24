<?php
require_once "conn.php";

$client_id = $_GET['client_id'] ;
$contact_email = $_GET['user_email'];



$stmt = $conn->prepare("INSERT INTO connections (client_id, contact_email) VALUES (?, ?)");
// $stmt = $conn->prepare($sql);
$stmt->bindParam(1, $client_id, PDO::PARAM_STR);
$stmt->bindParam(2, $contact_email, PDO::PARAM_STR);
// $stmt->bind_param('ss', $client_id, $contact_email);
$stmt->execute();
// $result = $stmt->get_result();
// $tempNum=0;
// $arr = array();
// while ($row = $result->fetch_assoc()) {
//   if(!empty($row["user_clients_associated"])){
//     $arr = explode(', ', $row["user_clients_associated"]);
//     $tempNum++;
//   }
// }
// if(in_array($data, $arr)){
//  $dosomething = '';
// }else{
//   array_push($arr, $data);
// }

// $commaList = implode(', ', $arr);
// $stmt3 = $conn->prepare("UPDATE users SET user_clients_associated = ? WHERE user_email = ?");
// $stmt3->bind_param('ss', $commaList, $data2);
// $stmt3->execute();


// $stmt4 = $conn->prepare("SELECT client_contacts_associated FROM clients WHERE client_id = ?");
// $stmt4->bind_param('s', $data);
// $stmt4->execute();
// $result4 = $stmt4->get_result();
// $tempNum4=0;
// $arr4 = array();
// while ($row = $result4->fetch_assoc()) {
//   if(!empty($row["client_contacts_associated"])){
//     $arr4 = explode(', ', $row["client_contacts_associated"]);
//     $tempNum++;
//   }
// }

// if(in_array($data2, $arr4)){
//   $dosomething = '';
//  }else{
//    array_push($arr4, $data2);
//  }
// $commaList4 = implode(', ', $arr4);
// $stmt2 = $conn->prepare("UPDATE clients SET client_contacts_associated = ? WHERE client_id = ?");
// $stmt2->bind_param('ss', $commaList4, $data);
// $stmt2->execute();

echo json_encode('success');


exit();


?>