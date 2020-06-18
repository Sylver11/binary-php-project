<?php
require_once 'conn.php';

$data = $_GET['client_id'];
$data_decoded = json_decode($data);
$param_contact_name = $data . '%';
$stmt = $conn->prepare("SELECT DISTINCT client_id FROM clients WHERE client_id LIKE ?");
$stmt->bind_param('s', $param_contact_name);
$stmt->execute();
$result = $stmt->get_result();
$tempNum=0;
$arr = array();
while ($row = $result->fetch_assoc()) {
        $arr[$tempNum]= $row["client_id"];
        $tempNum++;
}
echo json_encode($arr);
exit();

?>