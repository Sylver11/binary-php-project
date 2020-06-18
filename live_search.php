<?php
require_once 'conn.php';

$data = $_GET['user_email'] ;
$data_decoded = json_decode($data);
$param_contact_name = $data . '%';
$stmt = $conn->prepare("SELECT DISTINCT user_email FROM users WHERE user_email LIKE ?");
$stmt->bind_param('s', $param_contact_name);
$stmt->execute();
$result = $stmt->get_result();
$tempNum=0;
$arr = array();
while ($row = $result->fetch_assoc()) {
        $arr[$tempNum]= $row["user_email"];
        $tempNum++;
}
echo json_encode($arr);
exit();

?>