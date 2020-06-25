<html>
 <head>
  <title>Contacts</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
 </head>


<?php 
require_once 'conn.php';
require 'eager-loading.php';

$contact_name = $contact_surname = $contact_email = "";
$contact_name_err = $contact_surname_err = $contact_email_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(empty(trim($_POST["contact_email"]))){
    $contact_email_err = "Please enter a email.";
  } else{
    $param_contact_email = trim($_POST["contact_email"]);
    $sql = "SELECT id FROM contacts WHERE contact_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $param_contact_email, PDO::PARAM_STR);
    if($stmt->execute()){
        if($stmt->rowCount() > 0){
            $contact_email_err = "This email is already taken.";
        } 
        else{
            if (!filter_var(trim($_POST["contact_email"]), FILTER_VALIDATE_EMAIL)) {
              $contact_email_err = "Invalid email format";
            }
            else{
              $contact_email = trim($_POST["contact_email"]);
            }
        }
    }
    else{
      echo "Oops! Something went wrong. Please try again later.";
    }
  }

  if(empty(trim($_POST["contact_name"]))){
    $contact_name_err = "Please enter your first name.";
   } else{
      $contact_name = trim($_POST["contact_name"]);
    }

  if(empty(trim($_POST["contact_surname"]))){
      $contact_surname_err = "Please enter your surname.";
    } else{
        $contact_surname = trim($_POST["contact_surname"]);
      }

  if(empty($contact_email_err) && empty($contact_name_err) && empty($contact_surname_err)){
    $sql = "INSERT INTO contacts (contact_name, contact_surname, contact_email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $contact_name, PDO::PARAM_STR);
    $stmt->bindParam(2, $contact_surname, PDO::PARAM_STR);
    $stmt->bindParam(3, $contact_email, PDO::PARAM_STR);

    if($stmt->execute()){
        header("location: contacts.php");
    } else{
        echo "Something went wrong. Please try again later.";
      }
    }
  }

?> 

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
    <div class="wrapper">
        <h2>Add Contact</h2>
        <p>Please fill this form to add a contact.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($contact_name_err)) ? 'has-error' : ''; ?>">
                <label>Name</label>
                <input type="text" name="contact_name" class="form-control" value="<?php echo $contact_name; ?>">
                <span class="help-block"><?php echo $contact_name_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($contact_surname_err)) ? 'has-error' : ''; ?>">
                <label>Surname</label>
                <input type="text" name="contact_surname" class="form-control" value="<?php echo $contact_surname; ?>">
                <span class="help-block"><?php echo $contact_surname_err; ?></span>
            </div>  
            <div class="form-group <?php echo (!empty($contact_email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="email" name="contact_email" class="form-control" value="<?php echo $contact_email; ?>">
                <span class="help-block"><?php echo $contact_email_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Add Contact">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>  
        </form>
    </div>
    <h4>List of all contacts:</h4>
    <ul>
    <?php
    $list = new Eager('contacts', 'contact_surname', 'connections', 'contact_email', 'contact_email', 'client_id', 'big', $db_username, $db_password);
    echo $list->output();
    ?>

    </ul>
    <h4>List of all clients:</h4>
      <ul>
      <?php
      $small_list = new Eager('clients', 'client_name', 'connections', 'client_id', 'client_id', 'contact_email', 'small', $db_username, $db_password);
      echo $small_list->output();
      ?>
    </ul>
  </div>
</body>



<script>
$( document ).ready(function() {
  $("#nav_home").removeClass("active");
  $("#nav_contacts").addClass("active");
  $("#nav_clients").removeClass("active");
  $('.link_contact').click(function(){
      $(this).parents().next('.search_form').css('display', 'block')
      $(this).css('display', 'none')
    })
  $('.search_form').on("submit", function(e) {
        e.preventDefault()
        var contact_string = $(this).prev('li').text()
        var re = /(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/;
        var contact_email = re.exec(contact_string);
        var object = this
        var client_id = this[0].value
        $.ajax({ 
            type: 'get',
            url: "make_connection.php", 
            data: {client_id: client_id, contact_email: contact_email[0]}, 
            dataType: 'json',
            success: function(data) { 
              if(data == 'success'){
                alert(data);
              }
              else{
                alert(data);
              }
              $(object).find('input').val('');
            }
       });

        });


  $(document).on("keyup", ".search", function (e) {
            e.preventDefault();
            var currentFocus;
            var input_element = this;
            var url = "live_search.php";
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
                        names[i] = returnData[i]['client_id']
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
