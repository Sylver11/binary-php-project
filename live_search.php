<?php
require_once 'conn.php';

// $sql = "SELECT DISTINCT user_name FROM users WHERE user_name LIKE s%";
$data = $_GET['contact_name'] or $_REQUEST['contact_name'];
$data_decoded = json_decode($data);
$param_contact_name = $data . '%';//$data_decoded['contact_name'];
$stmt = $conn->prepare("SELECT DISTINCT user_name FROM users WHERE user_name LIKE ?");
$stmt->bind_param('s', $param_contact_name);
$stmt->execute();
$result = $stmt->get_result();
$tempNum=0;
$arr = array();
while ($row = $result->fetch_assoc()) {
        $arr[$tempNum]= $row["user_name"];
        $tempNum++;
}
echo json_encode($arr);
exit();



?>