<html>
 <head>
  <title>Binary PHP project</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
 </head>
 <body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/contacts.php">Contacts</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/clients.php">Clients</a>
          </li>
        </ul>
      </div>
    </nav>
    <div class=container>
   "Welcome to the my PHP project"
   </div>
 </body>


<?php
require_once 'conn.php';

$query = "SELECT id FROM users";
$result = mysqli_query($conn, $query);

if(empty($result)) {
                $query = "CREATE TABLE users (
                          id MEDIUMINT AUTO_INCREMENT,
                          user_email varchar(255) NOT NULL UNIQUE,
                          user_name varchar(255) NOT NULL,
                          user_surname varchar(255) NOT NULL,
                          user_clients_associated varchar(500),
                          PRIMARY KEY (id)
                          )";
                $result = mysqli_query($conn, $query);
}



$sql = "SELECT id FROM clients";
$result2 = mysqli_query($conn, $sql);

if(empty($result2)) {
  $query = "CREATE TABLE clients (
            id MEDIUMINT NOT NULL AUTO_INCREMENT,
            client_id varchar(255) NOT NULL UNIQUE,
            client_name varchar(255) NOT NULL,
            client_contacts_associated varchar(500),
            PRIMARY KEY (id)
            )";
  $result2 = mysqli_query($conn, $query);
}


?>

</html>
