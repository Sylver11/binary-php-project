<html>
 <head>
  <title>Contacts</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 </head>


<?php 
require_once 'conn.php';

$client_name = $client_id = "";
$client_name_err = $client_id_err = "";

if (!empty($_POST['client_name'])){

  if(empty(trim($_POST["client_name"]))){
    $client_name_err = "Please enter a name";
   } else{
      $client_name = trim($_POST["client_name"]);
    }


  if(empty(trim($_POST["client_id"]))){
      $client_id_err = "Please enter an ID.";
    } else{
        $client_id = trim($_POST["client_id"]);
      }





  if(empty($client_id_err) && empty($client_name_err)){
        
    $sql = "INSERT INTO clients (client_name, client_id) VALUES (?, ?)";
   
    if($stmt = mysqli_prepare($link, $sql)){

      mysqli_stmt_bind_param($stmt, "ss", $param_client_name, $param_client_id);

      $param_client_name = $client_name;
      $param_client_id = $client_id;
      

      if(mysqli_stmt_execute($stmt)){
          header("location: clients.php");
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
    <div class="wrapper">
        <h2>Add Contact</h2>
        <p>Please fill this form to add a contact.</p>
        <form class="form_add_client" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($client_name_err)) ? 'has-error' : ''; ?>">
                <label>Name</label>
                <input id="client_name"type="text" name="client_name" class="form-control" value="<?php echo $client_name; ?>">
                <span class="help-block"><?php echo $client_name_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($client_id_err)) ? 'has-error' : ''; ?>">
                <label>Client ID</label>
                <input readonly id="client_id" type="text" name="client_id" class="form-control" value="<?php echo $client_id; ?>">
                <span class="help-block"><?php echo $client_id_err; ?></span>
            </div>  
            <div class="form-group">
                <input type="submit" name="add_client" class="btn btn-primary" value="Add client">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>  
        </form>
    </div>

  <h4>List of all clients:</h4>
  <ul>
  <?php
    $sql = "SELECT * FROM clients ORDER BY client_name ASC";
    $i=0;
    if ($result=mysqli_query($conn,$sql)){
      while ($row = mysqli_fetch_array($result)){
        echo "<li> " . $row['client_name'] ." " . $row['client_id'];
        if($row['client_contacts_associated'] == '') { echo "<ul><li> No contacts linked </li></ul></li>"; }
        else{echo "<ul><li> " . $row['client_contacts_associated'] . "</li></ul></li>"; }
    $i++;  
    } }?>
    </ul>



    <h4>List of all contacts:</h4>
  <ul>
  <?php
    $sql = "SELECT * FROM users ORDER BY user_surname ASC";
    $i=0;
    if ($result=mysqli_query($conn,$sql)){
      while ($row = mysqli_fetch_array($result)){
        echo "<li> " . $row['user_name'] ." " . $row['user_surname'] . " " . $row['user_email'];
        if($row['user_clients_associated'] == '') { echo "<ul><li> No clients linked </li></ul></li>"; }
        else{echo "<ul><li> " . $row['user_clients_associated'] . "</li></ul></li>"; }
        echo " <form class='search_form' autocomplete='off'>";
        echo "<input type='text' class= 'search'>";
        echo "<button type='submit'>Link</button>";
        echo "</form>";
    $i++;  
    } }   
?>
</ul>


</body>



<script>
$( document ).ready(function() {
var final_id = ''
    $("#client_name").keyup(function () {
        var str = $("#client_name").val().toUpperCase()
        var words = str.split(" ");
        var id_str =[];
        for (var i = 0; i < words.length; i++) {
          id_str.push(words[i].charAt(0))
        }
        if (id_str.length == 1){
          if(words[0].charAt(1) == ""){
                id_str.splice(1, 0, "A");
                id_str.splice(2, 0, "B");
              }
              else if(words[0].charAt(2) == ""){
                id_str.splice(1, 0, "A");
              }else{
              id_str.splice(1, 0, words[0].charAt(1));
                id_str.splice(2, 0, words[0].charAt(2));
              }
            id_str.push(words[0].charAt(1))
            id_str.push(words[0].charAt(2))
            }
        else if (id_str.length == 2 ){
            if(words[1].charAt(0) == ""){
                id_str.splice(1, 0, words[0].charAt(1));
                id_str.splice(2, 0, words[0].charAt(2));
              }
            else{
              if(words[1].toString().length < 2){
                  id_str.splice(1, 0, words[0].charAt(1));
                  id_str.push(words[1].charAt(0))
                  }
              else{
                  id_str.push(words[1].charAt(1))
                  }
            }
        }
        else if (id_str.length == 3 ){
          if(words[2].charAt(0) == ""){
                id_str.splice(2, 0, words[1].charAt(1));
              }
          
        }
    final_id = id_str.slice(0, 3).join("")
});

  $('.form_add_client').submit(function(e) { 
    e.preventDefault();
    e.returnValue = false;
        $.ajax({ 
            type: 'post',
            url: "client_id.php", 
            success: function(data) { 
              var obj = JSON.parse(data);
              var highest_id = parseInt(obj) + 1;
              var highest_id_string_final = ("00" + highest_id).slice(-3);
              $("#client_id").val(final_id + highest_id_string_final )
            },
            complete: function() { 
              $(".form_add_client").off('submit');
              $('.form_add_client').submit();
            }
       });
  });



  $('.search_form').on("submit", function(e) {
        e.preventDefault()
        var contact_string = $(this).prev('li').text()
        console.log(contact_string)
        var re = /(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/;
        var contact_email = re.exec(contact_string);
        var client_name = this[0].value
        console.log(contact_email[0])
        $.ajax({ 
            type: 'get',
            url: "make_connection.php", 
            data: {client_name: client_name, contact_email: contact_email[0]}, 
            dataType: 'json',
            success: function(data) { 
              var obj = JSON.parse(data);
              console.log(obj)
              // var highest_id = parseInt(obj) + 1;
              // var highest_id_string_final = ("00" + highest_id).slice(-3);
              // $("#client_id").val(final_id + highest_id_string_final )
            },
            complete: function() { 
              // $(".form_add_client").off('submit');
              // $('.form_add_client').submit();
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
                data: {contact_name: name}, 
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
