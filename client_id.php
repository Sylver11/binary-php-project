<?php
require_once "conn.php";

$sql="SELECT MAX(ID) FROM clients";








$i=0;
if ($result=mysqli_query($conn,$sql)){
  while ($row = mysqli_fetch_array($result)){
    //   echo $row;
    $highestID =  $row['MAX(ID)'];
$i++;  
} 
echo json_encode($highestID);
}


?>