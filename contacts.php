<html>
 <head>
  <title>Contacts</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 </head>


<?php 
require_once 'conn.php';

$user_name = $user_surname = $user_email = "";
$user_name_err = $user_surname_err = $user_email_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
  if(empty(trim($_POST["user_email"]))){
    $username_err = "Please enter a email.";
  } else{
    $sql = "SELECT id FROM users WHERE user_email = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){

        mysqli_stmt_bind_param($stmt, "s", $param_user_email);

        $param_user_email = trim($_POST["user_email"]);

        if(mysqli_stmt_execute($stmt)){

            mysqli_stmt_store_result($stmt);
            
            if(mysqli_stmt_num_rows($stmt) == 1){
                $user_email_err = "This email is already taken.";
            } else{
                if (!filter_var(trim($_POST["user_email"]), FILTER_VALIDATE_EMAIL)) {
                    $user_email_err = "Invalid email format";
                }
                else{
                    $user_email = trim($_POST["user_email"]);
                }
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        mysqli_stmt_close($stmt);
      }
  }


  if(empty(trim($_POST["user_name"]))){
    $user_name_err = "Please enter your first name.";
   } else{
      $user_name = trim($_POST["user_name"]);
    }


  if(empty(trim($_POST["user_surname"]))){
      $user_surname_err = "Please enter your surname.";
    } else{
        $user_surname = trim($_POST["user_surname"]);
      }

  if(empty($user_email_err) && empty($user_name_err) && empty($user_surname_err)){
        
    $sql = "INSERT INTO users (user_name, user_surname, user_email) VALUES (?, ?, ?)";
   
    if($stmt = mysqli_prepare($link, $sql)){

      mysqli_stmt_bind_param($stmt, "sss", $param_user_name, $param_user_surname, $param_user_email);

      $param_user_name = $user_name;
      $param_user_surname = $user_surname;
      $param_user_email = $user_email;
      

      if(mysqli_stmt_execute($stmt)){
          header("location: contacts.php");
      } else{
          echo "Something went wrong. Please try again later.";
      }

      mysqli_stmt_close($stmt);
    }
  }
mysqli_close($link);
}

?> 

<body>

    <ul>
      <li><a href="/">Home</a></li>
      <li><a href="/contacts.php">Contacts</a></li>
      <li><a href="/clients.php">Clients</a></li>
    </ul>

    <div class="wrapper">
        <h2>Add Contact</h2>
        <p>Please fill this form to add a contact.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($user_name_err)) ? 'has-error' : ''; ?>">
                <label>Name</label>
                <input type="text" name="user_name" class="form-control" value="<?php echo $user_name; ?>">
                <span class="help-block"><?php echo $user_name_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($user_surname_err)) ? 'has-error' : ''; ?>">
                <label>Surname</label>
                <input type="text" name="user_surname" class="form-control" value="<?php echo $user_surname; ?>">
                <span class="help-block"><?php echo $user_surname_err; ?></span>
            </div>  
            <div class="form-group <?php echo (!empty($user_email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="email" name="user_email" class="form-control" value="<?php echo $user_email; ?>">
                <span class="help-block"><?php echo $user_email_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>  
        </form>
    </div>


  <h4>List of all contacts:</h4>
  <ul>
  <?php
    $sql = "SELECT * FROM users ORDER BY user_surname ASC";
    $i=0;
    if ($result=mysqli_query($conn,$sql)){
      if(mysqli_num_rows($result)!==0) {
      while ($row = mysqli_fetch_array($result)){
        echo "<li> " . $row['user_name'] ." " . $row['user_surname'] . " " . $row['user_email'];
        if($row['user_clients_associated'] == '') { echo "<ul><li> No clients linked </li></ul></li>"; }
        else{
          $array = explode(', ', $row['user_clients_associated']);
          foreach($array as $value) {echo "<ul><li> " . $value . "</li><button type='delete' onclick='location.href=\"unlink.php?user_email=" . $row['user_email'] . "&client_id=" . $value . "\";'>Remove link</button></ul>"; }}
        echo " </li><form class='search_form' autocomplete='off'>";
        echo "<input type='text' class= 'search'>";
        echo "<button type='submit'>Link</button>";
        echo "</form>";
    $i++;  
    } }
    else{
      echo "No contact(s) found.";
    } 
  } 
     
?>
</ul>




<h4>List of all clients:</h4>
  <ul>
  <?php
    $sql = "SELECT * FROM clients ORDER BY client_name ASC";
    $i=0;
    if ($result=mysqli_query($conn,$sql)){
      if(mysqli_num_rows($result)!==0) {
      while ($row = mysqli_fetch_array($result)){
        echo "<li> " . $row['client_name'] ." " . $row['client_id'];
        if($row['client_contacts_associated'] == '') { echo "<ul><li> No contacts linked </li></ul></li>"; }
        else{echo "<ul><li> " . $row['client_contacts_associated'] . "</li></ul></li>"; }
    $i++;  
    } }
    else{
      echo "No client(s) found.";
    } 
  }   
?>
</ul>
 </body>



<script>
$( document ).ready(function() {

  $('.search_form').on("submit", function(e) {
        e.preventDefault()
        var contact_string = $(this).prev('li').text()
        var re = /(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/;
        var user_email = re.exec(contact_string);
        var object = this
        var client_id = this[0].value
        $.ajax({ 
            type: 'get',
            url: "make_connection.php", 
            data: {client_id: client_id, user_email: user_email[0]}, 
            dataType: 'json',
            success: function() { 
              $(object).find('input').val('');
            }
       });

        });


  $(document).on("keyup", ".search", function (e) {
            e.preventDefault();
            var currentFocus;
            var input_element = this;
            var url = "live-search-client.php";
            var a, b, i, val = this.value;
            var name = this.value
                $.ajax({
                type: "GET",
                url: url,
                data: {client_id: name}, 
                dataType: 'json',
                success: function (returnData) {
                    if (returnData.length <= 5){
                      var names = [], range = returnData.length;
                    }
                    else{
                     var names = [], range = 5
                    }
                    for (i = 0; i < range; i++) {
                        names[i] = returnData[i]
                    }
                    closeAllLists();
                    if (!val) { return false;}
                    currentFocus = -1;
                    var a = document.createElement("DIV");
                    a.setAttribute("id", "autocomplete-list");
                    a.setAttribute("class", "autocomplete-items");
                    input_element.parentNode.appendChild(a);
                    for (i = 0; i < names.length; i++) {
                        if (names[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                            var b = document.createElement("DIV");
                            b.innerHTML = "<strong>" + names[i].substr(0, val.length) + "</strong>";
                            b.innerHTML += names[i].substr(val.length);
                            b.innerHTML += "<input type='hidden' value='" + names[i] + "'>";
                            b.addEventListener("click", function(e) {
                                input_element.value = this.getElementsByTagName("input")[0].value
                                closeAllLists();
                            });
                            a.appendChild(b);
                        }
                    }


            function closeAllLists(elmnt) {
                var x = document.getElementsByClassName("autocomplete-items");
                for (var i = 0; i < x.length; i++) {
                    if (elmnt != x[i] && elmnt != input_element) {
                    x[i].parentNode.removeChild(x[i]);
                    }
                }
            }
            document.addEventListener("click", function (e) {
                closeAllLists(e.target);
            });

                }




            });
        });

});

</script>



</html>
