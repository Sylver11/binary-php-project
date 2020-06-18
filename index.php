<html>
 <head>
  <title>Binary PHP project</title>
 </head>
 <body>
 <ul>
  <li><a href="/">Home</a></li>
  <li><a href="/contacts.php">Contacts</a></li>
  <li><a href="/clients.php">Clients</a></li>
</ul>
   "Welcome to the my PHP project"
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
