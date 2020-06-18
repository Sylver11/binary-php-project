<html>
 <head>
  <title>Contacts</title>
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
          header("location: general.php");
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
                <input type="text" name="user_email" class="form-control" value="<?php echo $user_email; ?>">
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
    $i=0; // this refer every row..now its 0
    if ($result=mysqli_query($conn,$sql)){
      while ($row = mysqli_fetch_array($result)){
        echo "<li> " . $row['user_name'] ." " . $row['user_surname'] . " " . $row['user_email'];
        if($row['user_clients_associated'] == '') { echo " No clients linked </li>"; };
    $i++;  
    } }   
?>
</ul>
 </body>


</html>
