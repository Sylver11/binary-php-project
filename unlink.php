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

$client_id = $_GET['client_id'];
$user_email = $_GET['user_email'];
$stmt = $conn->prepare( "DELETE FROM connections WHERE contact_email = :user_email AND client_id = :client_id" );
$stmt->bindParam(':user_email', $user_email);        
$stmt->bindParam(':client_id', $client_id);
$stmt->execute();
if( ! $stmt->rowCount() ){
  echo "Deletion failed";
} else{
  echo "You have successfully unlinked " . $client_id . " from " . $user_email;
}

?>
    </div>
  </body>
</html>