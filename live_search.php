<?php
require_once 'conn.php';

$data = $_GET['contact_name'] or $_REQUEST['contact_name'];
$data_decoded = json_decode($data);
$param_contact_name = $data . '%';
$stmt = $conn->prepare("SELECT DISTINCT client_name FROM clients WHERE client_name LIKE ?");
$stmt->bind_param('s', $param_contact_name);
$stmt->execute();
$result = $stmt->get_result();
$tempNum=0;
$arr = array();
while ($row = $result->fetch_assoc()) {
        $arr[$tempNum]= $row["client_name"];
        $tempNum++;
}
echo json_encode($arr);
exit();

?>