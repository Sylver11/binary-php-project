<html>
 <head>
  <title>Contacts</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
 </head>
 <body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li id="nav_home" class="nav-item active">
          <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
        </li>
        <li id="nav_contacts" class="nav-item">
          <a class="nav-link" href="/contacts.php">Contacts</a>
        </li>
        <li id="nav_clients" class="nav-item">
          <a class="nav-link" href="/clients.php">Clients</a>
        </li>
      </ul>
    </div>
  </nav>
  <div class="container">
<?php
require_once "conn.php";

$data = $_GET['client_id'];
$data2 = $_GET['user_email'];

$stmt = $conn->prepare("SELECT user_clients_associated FROM users WHERE user_email = ?");
$stmt->bind_param('s', $data2);
$stmt->execute();
$result = $stmt->get_result();
$tempNum=0;
$arr = array();
while ($row = $result->fetch_assoc()) {
  if(!empty($row["user_clients_associated"])){
    
    
    $arr = explode(', ', $row["user_clients_associated"]);
    $tempNum++;
  }
        
}

if (($key = array_search($data, $arr)) !== false) {
  unset($arr[$key]);
}

$commaList = implode(', ', $arr);

$stmt3 = $conn->prepare("UPDATE users SET user_clients_associated = ? WHERE user_email = ?");
$stmt3->bind_param('ss', $commaList, $data2);
$stmt3->execute();



$stmt4 = $conn->prepare("SELECT client_contacts_associated FROM clients WHERE client_id = ?");
$stmt4->bind_param('s', $data);
$stmt4->execute();
$result4 = $stmt4->get_result();
$tempNum4=0;
$arr4 = array();
while ($row = $result4->fetch_assoc()) {
  if(!empty($row["client_contacts_associated"])){
    $arr4 = explode(', ', $row["client_contacts_associated"]);
    $tempNum++;
  }  
}


if (($key = array_search($data2, $arr4)) !== false) {
  unset($arr4[$key]);
}
$commaList2 = implode(', ', $arr4);
$stmt2 = $conn->prepare("UPDATE clients SET client_contacts_associated = ? WHERE client_id = ?");

  $stmt2->bind_param('ss', $commaList2, $data);
  $stmt2->execute();

echo "You have successfully unlinked " . $data . " from " . $data2;
exit();


?>
    </div>
  </body>
</html>