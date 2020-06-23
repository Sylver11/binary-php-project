<?php
require_once 'conn.php';

$data = $_GET['user_email'] ;
$data_decoded = json_decode($data);
$param_contact_name = $data . '%';
$stmt = $conn->prepare("SELECT DISTINCT user_email FROM users WHERE user_email LIKE ?");
// $stmt->bind_param('s', $param_contact_name);
$stmt->execute($param_contact_name);
$result = $stmt->fetch();
// print_r($result);
$tempNum=0;
$arr = array();
while ($row = $result) {
        $arr[$tempNum]= $row["user_email"];
        $tempNum++;
}
echo json_encode($arr);
exit();

?>