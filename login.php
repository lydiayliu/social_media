<?php
  include("dbconfig.php");
  session_start();

  if($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach($_POST as $key=>$value) {
      if(empty($_POST[$key])) {
        $message = "<div class=\"alert alert-danger\">" . ucwords($key) . " field is required!</div>";
        break;
      }
    }
    if (!isset($message) ){
      $user_email = mysqli_real_escape_string($conn, $_POST['email']);
      $password = mysqli_real_escape_string($conn, $_POST['password']);

      $sql = "SELECT * FROM account WHERE email_address = '$user_email'";
      $result = mysqli_query($conn, $sql);
      $count = mysqli_num_rows($result);
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
      $hashed_password = $row['password'];

      // If result matched $myusername and $mypassword, table row must be 1 row

      if( password_verify($password, $hashed_password) && $count == 1) {
        $_SESSION['login_user'] = $user_email;
        if ($row['isAdmin'] == 1)
          header("location:admin_welcome.php");
        else
          header("location: welcome.php");
      } else {
        $message = "<div class=\"alert alert-danger\">Your Login Name or Password is invalid!</div>";
      }
    }
  }
  $conn->close();
?>

<html>
  <head>
    <title>Login Page</title>
    <?php require_once('head.php');?>
  </head>
  <div class="container">
  <div class="col-md-4">
  <form action = "" method = "post" class = "form-signin" role = "form">
    <h1 class= "form-signin-heading">Login</h1>
    <label>Email :</label><input type="text" name="email" class="form-control"><br/>
    <label>Password :</label><input type="password" name="password" class="form-control"><br/>
    <?php if(isset($message)) echo $message; ?>
    <input class = "btn btn-lg btn-primary btn-block" type = "submit" value = "Submit"/>
  </form>
  <form action="register.php" target="_blank" action="">
    <button class="btn btn-primary">Register</button>
  </form>
  <form action="index.php">
    <button class="btn btn-primary">Back to homepage</button>
  </form>
  </div>
  </div>
  </body>
</html>
